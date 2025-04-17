import argparse
import base64
import logging
import sys
from zeep import Client
from zeep.transports import Transport
from requests import Session


def get_soap_client(wsdl_url, verify=True):
    session = Session()
    session.verify = verify
    transport = Transport(session=session)
    return Client(wsdl=wsdl_url, transport=transport)


def validate_xml(xml_base64):
    """Valida el XML firmado mediante el servicio de validación del SRI."""
    wsdl_validacion = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"
    client = get_soap_client(wsdl_validacion)
    return client.service.validarComprobante(xml_base64)


def authorize_xml(clave_acceso):
    """Solicita la autorización del comprobante mediante el servicio del SRI."""
    wsdl_autorizacion = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
    client = get_soap_client(wsdl_autorizacion)
    return client.service.autorizacionComprobante(clave_acceso)


def main():
    parser = argparse.ArgumentParser(description="Valida y autoriza el XML firmado del SRI")
    parser.add_argument("--xml", required=True, help="Ruta del XML firmado")
    parser.add_argument("--clave", required=True, help="Clave de acceso del comprobante")
    args = parser.parse_args()

    # Cargar y codificar el XML en base64
    try:
        with open(args.xml, "rb") as f:
            xml_content = f.read()
    except Exception as e:
        logging.exception("Error al leer el XML")
        print("Error al leer el XML:", e, file=sys.stderr)
        sys.exit(1)

    xml_base64 = base64.b64encode(xml_content).decode('utf-8')

    # Validar el XML
    try:
        validation_response = validate_xml(xml_base64)
    except Exception as e:
        logging.exception("Error en la validación")
        print("Error en la validación del XML:", e, file=sys.stderr)
        sys.exit(1)

    print("\n--- Respuesta del SRI en validación ---")
    print("Estado:", validation_response.estado)
    try:
        if hasattr(validation_response, 'comprobantes') and validation_response.comprobantes:
            for comprobante in validation_response.comprobantes.comprobante:
                print("\nComprobante:", comprobante.claveAcceso)
                for mensaje in comprobante.mensajes.mensaje:
                    adicional = mensaje.informacionAdicional if hasattr(mensaje, 'informacionAdicional') else ""
                    print("- Mensaje:", mensaje.mensaje)
                    print("- Información Adicional:", adicional)
                    print("- Tipo:", mensaje.tipo)
    except Exception as e:
        logging.exception("Error en la validación")
        print("No se recibió ningún comprobante en la validación:", e, file=sys.stderr)
        sys.exit(1)




    # Autorizar el XML usando la clave enviada por parámetro
    try:
        authorization_response = authorize_xml(args.clave)
    except Exception as e:
        logging.exception("Error en la autorización")
        print("Error en la autorización del XML:", e, file=sys.stderr)
        sys.exit(1)

    print("\n--- Respuesta de Autorización ---")
    try:
        if authorization_response.autorizaciones:
            for autorizacion in authorization_response.autorizaciones.autorizacion:
                print("Estado:", autorizacion.estado)
                print("Fecha Autorización:", autorizacion.fechaAutorizacion)
                if autorizacion.estado == "AUTORIZADO":
                    print("Comprobante XML:")
                    print(autorizacion.comprobante)
                else:
                    print("Comprobante NO AUTORIZADO")
                    if hasattr(autorizacion, 'mensajes'):
                        for msg in autorizacion.mensajes.mensaje:
                            print("- Mensaje:", msg.mensaje)
                            if hasattr(msg, 'informacionAdicional'):
                                print("- Info Adicional:", msg.informacionAdicional)
                            print("- Tipo:", msg.tipo)
    except Exception as e:
        logging.exception("Error en la autorización")
        print("No se recibió ninguna autorización:", e, file=sys.stderr)
        sys.exit(1)
        

if __name__ == '__main__':
    main()

