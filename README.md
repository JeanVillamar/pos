# POS — Sistema de Punto de Venta (PHP 8, MySQL, OpenSSL)

Sistema POS utilizando **PHP 8** e integrando varios recursos útiles para gestionar ventas, productos, caja y procesos asociados (impresión de tickets, códigos QR y facturación electrónica).  
Repositorio: https://github.com/JeanVillamar/pos

> **Estado**: Proyecto activo con commits recientes (2025). Este README resume el funcionamiento general, la instalación y los flujos críticos basados en el código y el historial de commits provistos por el autor.

---

## ✨ Características principales

- **Panel de órdenes y caja**: creación/actualización de órdenes, control de apertura/cierre de caja con validaciones.
- **Gestión de productos y catálogos**: búsqueda por nombre/código de barras, descuentos, productos más vendidos.
- **Sucursales y roles**: filtrado de datos por sucursal del administrador; lógica para restringir el acceso a información de la sucursal.
- **Impresión de tickets**: soporte para impresión **local** (preferida) y migración desde impresión remota.
- **Códigos QR**: generación de QR usando `phpqrcode` (incluida en `lib/phpqrcode/`).
- **Envío de correos**: mejoras en envío y manejo de notificaciones.
- **Facturación electrónica (EC)**: generación de **XML**, firma y autorización SRI (actualizado a IVA 15%).
- **Compatibilidad de entorno**: adaptaciones para **PHP 8.1.30**, **OpenSSL 3.1.7**, **MySQL 5.7.39** y uso de **Laragon** como servidor local.

> Varias de estas capacidades constan en el log de commits (2025-02 a 2025-08), p. ej. cambios en impresión local, IVA 15%, firma/autorización XML, validaciones de caja y filtrados por sucursal.

---

## 🗂️ Estructura del repositorio

Estructura a alto nivel (carpetas verificadas en el repo):

```
pos/
├─ QR/                 # Recursos/handlers relacionados a códigos QR
├─ api/                # Lógica de aplicación y endpoints (PHP)
├─ cms/                # Interfaz (panel) del CMS/administración
├─ lib/
│  └─ phpqrcode/      # Librería para generar códigos QR
├─ temp/               # Archivos temporales (XML, impresiones, etc.)
├─ .gitignore
└─ README.md
```

> Nota: la organización interna de `api/` y `cms/` puede incluir controladores, vistas y utilitarios específicos (p. ej., `OrdersController`, `CurlController`, `SecuencialController`, `XMLController`), según se desprende de los mensajes de commits.

---

## 🧰 Requisitos

- **PHP 8.1.30+** (extensiones sugeridas: `curl`, `openssl`, `mbstring`, `json`, `mysqli`)
- **OpenSSL 3.1.7+**
- **MySQL 5.7.39+**
- Servidor web local recomendado: **Laragon** (o Apache/Nginx equivalente)
- Acceso a **impresora local** (para tickets) y configuración SMTP (si se usan notificaciones por correo).

---

## ⚙️ Instalación (Desarrollo local con Laragon)

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

4. **Configurar virtual host (Laragon)**  
   - Sitúa el proyecto bajo `C:\laragon\www\pos` o agrega un vhost apuntando al directorio `cms/` si funciona como front/admin.  
   - Reinicia servicios de Laragon.

5. **Permisos de escritura**  
   - Asegura permisos de escritura para la carpeta `temp/` (y cualquier carpeta que reciba archivos generados: XML, tickets, etc.).

6. **Verificar extensiones PHP**  
   - Activa `curl` y `openssl` en `php.ini`.  
   - Reinicia Apache/Nginx desde Laragon.

---

## 🚚 Uso básico

- **Panel (CMS)**: Accede via navegador al vhost configurado (p. ej., `http://pos.test/`).  
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

1. **Generación de XML** con datos del cliente, emisor, productos, totales e impuestos (IVA **15%** desde 2025-04).  
2. **Firma electrónica** del XML (XAdES) mediante el ejecutable/JAR y certificado (`.p12`).  
3. **Autorización SRI** (validación y almacenamiento de la respuesta).  
4. **Almacenamiento** en `temp/` y/o envío al cliente si aplica.  
5. **Ticket**: impresión local con totales y código QR correspondiente.

> Validaciones destacadas: manejo de fecha (nuevo formato que no permite ceros), cálculo de unitarios, descuentos e impuestos.

---

## 🖨️ Impresión local

- El proyecto migró de **impresión remota** a **impresión local**, simplificando el flujo y reduciendo puntos de falla.  
- Ajusta en el controlador de impresión (p. ej., `CurlController`/config) la URL/local endpoint para que apunte al host local.  
- Asegura drivers y permisos de la impresora.

---

## 🔐 Seguridad y acceso por sucursal

- Filtrado de datos para limitar a cada administrador a su **sucursal** correspondiente.  
- Las consultas y endpoints deben validar el `id_office`/`id_admin` y roles antes de devolver datos sensibles.

---

## 🧪 Datos de ejemplo y pruebas

- Registra **clientes** (inicialización incluida en los primeros commits de 2025-02).  
- Crea **productos**, define **descuentos**, y prueba el **panel de órdenes**.
- Abre **caja**, registra **ingresos/gastos** y realiza **ventas** para validar métricas.  
- Prueba el **flujo SRI** con un certificado de pruebas y endpoints de homologación.

---

## 🐛 Registro de cambios (extracto, 2025)

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

- **PHP 8.1.x**, **MySQL 5.7.x**, **OpenSSL 3.x**
- **Laragon** (dev), **cURL**, **phpqrcode**
- Integración con **SRI** (firma XAdES + autorización)

---

## 🤝 Contribuir

1. Crea una rama desde `main`.
2. Realiza cambios con mensajes de commit claros.
3. Abre un Pull Request describiendo el contexto y las pruebas.

---

## 📄 Licencia

Este proyecto se distribuye con la licencia especificada en el repositorio (si aplica). Si no existe, añade la licencia que corresponda a tu organización.