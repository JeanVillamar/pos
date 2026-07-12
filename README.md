# POS — Sistema de Punto de Venta (PHP 8, MySQL, OpenSSL)

Sistema POS utilizando **PHP 8** e integrando varios recursos útiles para gestionar ventas, productos, caja y procesos asociados (impresión de tickets, códigos QR y facturación electrónica).  
Repositorio: https://github.com/JeanVillamar/pos

> **Estado**: Proyecto activo. Este README resume el funcionamiento general, la instalación local y los flujos críticos actualizados a julio de 2026.

---

## ✨ Características principales

- **Panel de órdenes y caja**: creación/actualización de órdenes, control de apertura/cierre de caja con validaciones.
- **Gestión de productos y catálogos**: búsqueda por nombre/código de barras, descuentos, productos más vendidos.
- **Sucursales y roles**: filtrado de datos por sucursal del administrador; lógica para restringir el acceso a información de la sucursal.
- **Impresión de tickets**: soporte para impresión **local** (preferida) y migración desde impresión remota.
- **Códigos QR**: generación de QR usando `phpqrcode` (incluida en `lib/phpqrcode/`).
- **Envío de correos**: mejoras en envío y manejo de notificaciones.
- **Facturación electrónica (EC)**: generación de **XML**, firma XAdES, autorización SRI, RIDE/PDF y envío por correo.
- **Certificados digitales**: carga de archivos `.p12` desde el CMS, almacenamiento fuera del docroot y clave cifrada en `informations`.
- **RIDE/PDF**: logo y datos del emisor tomados desde `informations`; la ruta del logo no se imprime como información adicional.
- **Compatibilidad de entorno**: desarrollo local en macOS con PHP built-in server, PHP 8.1.34, MySQL Homebrew y OpenSSL 3.1.7.

> Varias de estas capacidades constan en el log de commits y cambios recientes, p. ej. impresión local, IVA 15%, firma/autorización XML, certificados `.p12`, validaciones de caja y filtrados por sucursal.

---

## 🗂️ Estructura del repositorio

Estructura a alto nivel (carpetas verificadas en el repo):

```
pos/
├─ certificados/        # Certificados .p12 fuera del docroot CMS (ignorado por git)
├─ QR/                 # Recursos/handlers relacionados a códigos QR
├─ api/                # Lógica de aplicación y endpoints (PHP)
├─ cms/                # Interfaz (panel) del CMS/administración
│  ├─ GeneratePDFfromXML/ # Generador RIDE/PDF
│  ├─ config/          # Configuración local de facturación
│  └─ xml/             # XML generados, firmados, autorizados y PDFs
├─ lib/
│  └─ phpqrcode/      # Librería para generar códigos QR
├─ temp/               # Archivos temporales (XML, impresiones, etc.)
├─ .gitignore
└─ README.md
```

> Nota: la organización interna de `api/` y `cms/` puede incluir controladores, vistas y utilitarios específicos (p. ej., `OrdersController`, `CurlController`, `SecuencialController`, `XMLController`), según se desprende de los mensajes de commits.

---

## 🧰 Requisitos

- **PHP 8.1.34+** (extensiones sugeridas: `curl`, `openssl`, `mbstring`, `json`, `mysqli`)
- **OpenSSL 3.1.7+**
- **MySQL** compatible con el esquema del proyecto (desarrollo local actual: MySQL Homebrew)
- **Java/JDK** con `java` y `keytool` disponibles para validar y usar certificados `.p12`
- Servidor local actual: **PHP Built-in Server** para CMS y API
- Acceso a **impresora local** (para tickets) y configuración SMTP (si se usan notificaciones por correo).

---

## ⚙️ Instalación local

1. **Clonar el repositorio**  
   ```bash
   git clone https://github.com/JeanVillamar/pos
   cd pos
   ```

2. **Configurar la base de datos**  
   - Crear una base de datos en MySQL (p. ej., `pos_db`).  
   - Importar el esquema/datos del proyecto si dispones del dump SQL correspondiente.  
   - Ajustar collation/charset (recomendado: `utf8mb4` + `utf8mb4_unicode_ci`).

3. **Configurar credenciales y entorno**  
   - En `api/` y/o `cms/`, ubica el archivo de configuración de BD (p. ej., `config.php`, `database.php` o similar).  
   - Define host, usuario, contraseña y nombre de BD.  
   - Verifica zona horaria (`date.timezone`) y `openssl.cafile` si corresponde.

4. **Configurar hosts locales**  
   Agrega los dominios locales en `/etc/hosts`:
   ```text
   127.0.0.1 cms.pos.com
   127.0.0.1 api.pos.com
   ```

5. **Permisos de escritura**  
   - Asegura permisos de escritura para `temp/`, `cms/xml/` y `certificados/`.
   - Los certificados `.p12` deben quedar en `pos/certificados/`, no dentro de `cms/certificados/`.

6. **Verificar extensiones PHP**  
   - Activa `curl` y `openssl` en `php.ini`.  
   - Verifica que `java` y `keytool` estén disponibles si se usará firma electrónica.

7. **Levantar servicios locales**
   ```bash
   cd /Users/jeanfri/Documents/Proyectos/pos/cms
   php -S 0.0.0.0:8000

   cd /Users/jeanfri/Documents/Proyectos/pos/api
   php -S 0.0.0.0:8001
   ```

---

## 🚚 Uso básico

- **Panel (CMS)**: Accede vía navegador al host configurado (p. ej., `http://cms.pos.com:8000`).  
  - Inicia sesión con las credenciales del administrador.  
  - Explora **Órdenes**, **Productos**, **Clientes**, **Caja**, **Reportes**.

- **API**: Los endpoints residen en `api/`. Ejemplos comunes:
  - Listar productos (filtrados por sucursal).
  - Crear/actualizar órdenes, métodos de pago y totales.
  - Generar **XML** para facturación, firmar y autorizar ante el **SRI**.
  - Disparar **impresión local** del ticket de venta.

> Revisa los controladores en `api/` para ver rutas y parámetros esperados (p. ej., `OrdersController`, `CurlController`, `SecuencialController`, `XMLController`).

---

## 🧾 Flujo de Facturación Electrónica (EC)

1. **Datos del emisor**: se toman desde la tabla `informations` por sucursal/RUC.
2. **Generación de XML** con cliente, emisor, productos, totales e impuestos (IVA **15%**).
3. **Firma electrónica** del XML con certificado `.p12` y clave configurados en `informations`.
4. **Validación previa**: si falta el `.p12`, falta la clave o la clave no corresponde, la firma se detiene con error claro.
5. **Autorización SRI**: se ejecuta en segundo plano mediante `cms/workers/procesar_factura.php`.
6. **RIDE/PDF**: se genera con `cms/GeneratePDFfromXML`; usa logo y datos del emisor desde `informations`.
7. **Correo y estado**: el worker guarda XML autorizado, PDF y actualiza `invoices`; si el cliente tiene email válido, envía los adjuntos.
8. **POS**: al completar la venta, la orden se limpia visualmente y deja el panel listo para una nueva orden.

> Validaciones destacadas: manejo de fecha (nuevo formato que no permite ceros), cálculo de unitarios, descuentos e impuestos.

### Configuración de `informations`

Campos usados por facturación:

- `ruc_information`: RUC del emisor, 13 dígitos.
- `name_information`: razón social.
- `name_comercial_information`: nombre comercial.
- `address_matriz_information`: dirección matriz.
- `address_establishment_information`: dirección del establecimiento.
- `email_information` y `phone_information`: datos adicionales del emisor.
- `logo_information` (o campo equivalente de imagen): logo para el PDF/RIDE.
- `certification_information` (o campo equivalente): ruta del certificado `.p12`.
- `password_certification_information`: clave del `.p12`, guardada cifrada por el CMS.
- `obligado_contabilidad_information`: `SI` o `NO`.

La clave del certificado necesita cifrado reversible porque debe enviarse al firmador. Se usa `cms/controllers/secret.controller.php` con `app_key` definido en `cms/config/facturacion.config.php` o en la variable de entorno `POS_APP_KEY`.

---

## 🖨️ Impresión local

- El proyecto migró de **impresión remota** a **impresión local**, simplificando el flujo y reduciendo puntos de falla.  
- Ajusta en el controlador de impresión (p. ej., `CurlController`/config) la URL/local endpoint para que apunte al host local.  
- Asegura drivers y permisos de la impresora.

---

## 🔐 Seguridad y acceso por sucursal

- Filtrado de datos para limitar a cada administrador a su **sucursal** correspondiente.  
- Las consultas y endpoints deben validar el `id_office`/`id_admin` y roles antes de devolver datos sensibles.
- `cms/config/facturacion.config.php` está ignorado por git y no debe subirse.
- `certificados/*` y `cms/certificados/*` están ignorados por git.
- Los nuevos `.p12` deben guardarse en `pos/certificados/`; `cms/certificados/` queda como compatibilidad para archivos antiguos.
- `firmado_log.txt` oculta la clave del certificado al registrar comandos/salida del firmador.

---

## 🧪 Datos de ejemplo y pruebas

- Registra **clientes** (inicialización incluida en los primeros commits de 2025-02).  
- Crea **productos**, define **descuentos**, y prueba el **panel de órdenes**.
- Abre **caja**, registra **ingresos/gastos** y realiza **ventas** para validar métricas.  
- Prueba el **flujo SRI** con un certificado de pruebas y endpoints de homologación.

---

## 🐛 Registro de cambios (extracto, 2025-2026)

- **2026-07-12**: Facturación electrónica desde `informations`, carga de `.p12`, cifrado de clave, validación de certificado, RIDE/PDF con logo del emisor y limpieza de orden POS al completar venta.
- **2025-08-14**: Limpieza (`php_error_log` ignorados en `cms/` y `api/`), eliminación de `test.php`.  
- **2025-07-24**: Compatibilidad con **PHP 8.1.30**, **OpenSSL 3.1.7**, **MySQL 5.7.39** (Laragon). Validación de **nuevo formato de fecha**.  
- **2025-07-15**: Modificar precios manualmente (con validación), mejora de cierres de caja.  
- **2025-04-18**: Impresión de tickets **local**, mejoras en gestión de órdenes y en envío de correos.  
- **2025-04-17**: Ajuste al cálculo de precios unitarios; mejoras de `.gitignore` (zip).  
- **2025-04-15**: Refactor de órdenes; generación de XML; integración de validación y autorización **SRI**; ejecución de script Python de soporte con manejo de errores.  
- **2025-04-13**: **IVA 15%** en XML; manejo de productos en comprobantes.  
- **2025-04-11**: Generación de **clave de acceso** y firma electrónica.  
- **2025-03 a 2025-02**: Panel de órdenes, filtros por sucursal, métricas, catálogos, códigos de barras, métodos de pago y totales.

> Basado en el log de commits compartido por el autor. Consulta `git log` para el detalle completo.

---

## 🧩 Tecnologías

- **PHP 8.1.x**, **MySQL**, **OpenSSL 3.x**, **Java/keytool**
- **PHP Built-in Server** (dev actual), **cURL**, **phpqrcode**
- Integración con **SRI** (firma XAdES + autorización)

---

## 🤝 Contribuir

1. Crea una rama desde `main`.
2. Realiza cambios con mensajes de commit claros.
3. Abre un Pull Request describiendo el contexto y las pruebas.

---

## 📄 Licencia

Este proyecto se distribuye con la licencia especificada en el repositorio (si aplica). Si no existe, añade la licencia que corresponda a tu organización.
