import argparse
import base64
import sys
from zeep import Client
from zeep.transports import Transport
from requests import Session
from xml.etree.ElementTree import Element, SubElement, tostring
from xml.dom import minidom
import html
import os
from xml.sax.saxutils import XMLGenerator
from io import StringIO


def get_soap_client(wsdl_url, verify=True):
    session = Session()
    session.verify = verify
    transport = Transport(session=session)
    return Client(wsdl=wsdl_url, transport=transport)


def validate_xml(xml_base64):
    wsdl_validacion = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"
    client = get_soap_client(wsdl_validacion)
    return client.service.validarComprobante(xml_base64)


def authorize_xml(clave_acceso):
    wsdl_autorizacion = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
    client = get_soap_client(wsdl_autorizacion)
    return client.service.autorizacionComprobante(clave_acceso)


def main():
    parser = argparse.ArgumentParser(description="Valida y autoriza el XML firmado del SRI")
    parser.add_argument("--xml", required=True, help="Ruta del XML firmado")
    parser.add_argument("--clave", required=True, help="Clave de acceso del comprobante")
    args = parser.parse_args()

    # Leer XML
    try:
        with open(args.xml, "rb") as f:
            xml_content = f.read()
    except Exception as e:
        msg = f"[ERROR] No se pudo leer el archivo XML: {e}"
        print(msg, file=sys.stderr)
        sys.exit(10)

    xml_base64 = base64.b64encode(xml_content).decode('utf-8')

    # Validar
    try:
        validation_response = validate_xml(xml_base64)
    except Exception as e:
        msg = f"[ERROR] Falló la validación con el SRI: {e}"
        print(msg, file=sys.stderr)
        sys.exit(20)

    if validation_response.estado != "RECIBIDA":
        razones = getattr(validation_response, 'mensajes', [])
        # Armás un string con cada línea
        detalle = "\n".join(f"- {r}" for r in razones) if razones else "Sin detalles."
        msg = (
            f"[ERROR] Estado del SRI: {validation_response.estado}. "
            "El XML no fue recibido correctamente.\n"
            f"Detalles:\n{detalle}"
        )
        print(msg, file=sys.stderr)
        sys.exit(21)


    # Autorizar
    try:
        authorization_response = authorize_xml(args.clave)
    except Exception as e:
        msg = f"[ERROR] Falló la autorización con el SRI: {e}"
        print(msg, file=sys.stderr)
        sys.exit(30)

    if not authorization_response.autorizaciones or not authorization_response.autorizaciones.autorizacion:
        print("[ERROR] No se recibió ninguna autorización del SRI.", file=sys.stderr)
        print(authorization_response, file=sys.stderr)
        sys.exit(31)

    autorizacion = authorization_response.autorizaciones.autorizacion[0]
    if autorizacion.estado != "AUTORIZADO":
        msg = f"[ERROR] El comprobante fue procesado pero no autorizado. Estado: {autorizacion.estado}"
        print(msg, file=sys.stderr)
        if hasattr(autorizacion, 'mensajes'):
            for msg_obj in autorizacion.mensajes.mensaje:
                print(f"- {msg_obj.mensaje}", file=sys.stderr)
                if hasattr(msg_obj, 'informacionAdicional'):
                    print(f"  Info: {msg_obj.informacionAdicional}", file=sys.stderr)
        sys.exit(32)

    # Guardar el XML autorizado
    nombre_archivo = f"{args.clave}.xml"
    # Guardar el XML autorizado
    try:
        aut = authorization_response.autorizaciones.autorizacion[0]

                # Crear carpeta si no existe
        output_dir = "xml/autorizados"
        os.makedirs(output_dir, exist_ok=True)

        # Crear archivo XML con CDATA sin escapar
        output = StringIO()
        xml = XMLGenerator(output, encoding="utf-8")
        xml.startDocument()
        xml.startElement("autorizacion", {})

        xml.startElement("estado", {})
        xml.characters(aut.estado)
        xml.endElement("estado")

        xml.startElement("numeroAutorizacion", {})
        xml.characters(aut.numeroAutorizacion)
        xml.endElement("numeroAutorizacion")

        xml.startElement("fechaAutorizacion", {})
        xml.characters(aut.fechaAutorizacion.isoformat())
        xml.endElement("fechaAutorizacion")

        xml.startElement("ambiente", {})
        xml.characters(aut.ambiente)
        xml.endElement("ambiente")

        # CDATA del comprobante
        xml.startElement("comprobante", {})
        xml._write('<![CDATA[' + html.unescape(aut.comprobante) + ']]>')
        xml.endElement("comprobante")

        xml.endElement("autorizacion")
        xml.endDocument()

        # Guardar archivo
        nombre_archivo = os.path.join(output_dir, f"{aut.numeroAutorizacion}.xml")
        with open(nombre_archivo, "w", encoding="utf-8") as f:
            f.write(output.getvalue())

        print(f"[OK] XML autorizado guardado correctamente en: {nombre_archivo}")
        sys.exit(0)

    except Exception as e:
        print(f"[ERROR] Falló el guardado del XML autorizado: {e}", file=sys.stderr)
        sys.exit(40)


if __name__ == '__main__':
    main()
