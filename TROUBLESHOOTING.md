# Guía de Troubleshooting — POS (PHP 8 / MySQL / OpenSSL)

Esta guía recopila problemas frecuentes y sus soluciones prácticas basadas en la arquitectura del proyecto y el historial de commits.

---

## 0) Checklist rápido

- [ ] PHP 8.1.x con **`curl`** y **`openssl`** habilitados
- [ ] OpenSSL 3.x correctamente instalado y accesible
- [ ] MySQL con credenciales válidas y BD creada
- [ ] Permisos de escritura en `temp/`
- [ ] Permisos de escritura en `cms/xml/` y `certificados/`
- [ ] Zona horaria definida (`date.timezone`)
- [ ] Certificado **.p12** válido cargado en `informations`
- [ ] `password_certification_information` guardado como campo password y cifrado por el CMS
- [ ] `app_key` configurado en `cms/config/facturacion.config.php` o `POS_APP_KEY`
- [ ] `java` y `keytool` disponibles para validar el `.p12`
- [ ] Impresora local instalada y accesible desde el servidor
- [ ] Endpoint/URL de impresión local configurado
- [ ] Variables de entorno/archivos de config con rutas y claves correctas

---

## 1) Instalación y entorno

### 1.1 PHP: extensiones faltantes
**Síntomas**: errores de funciones `curl_*`, problemas de cifrado o llamadas externas.  
**Solución**: habilita en `php.ini`:  
```
extension=curl
extension=openssl
extension=mbstring
extension=mysqli
```
Reinicia el servidor PHP local o el servidor web usado en tu entorno.

### 1.2 OpenSSL 3: errores de firma/validación
**Síntomas**: la firma XAdES falla o el SRI rechaza el XML firmado.  
**Soluciones**:
- Verifica la ruta del ejecutable/JAR que firma y su compatibilidad con **OpenSSL 3.x**.
- Asegura que el certificado `.p12` no esté caducado y que la contraseña sea la correcta.
- Loggea la **salida de error** del proceso de firmado (commit menciona mejora de logs).

### 1.3 MySQL: errores de importación o SQL modes
**Síntomas**: errores de `STRICT_TRANS_TABLES`, fechas inválidas (`0000-00-00`).  
**Soluciones**:
- Ajusta `sql_mode` y valida defaults de fecha; algunas versiones son más estrictas con fechas `0000-00-00`.
- Usa `utf8mb4` y `utf8mb4_unicode_ci` para evitar problemas de caracteres.

---

## 2) CMS y API

### 2.1 Filtrado por sucursal no aplica
**Causa**: el usuario/admin no está asociado correctamente a una sucursal o falta parámetro en la consulta.  
**Solución**: valida la relación `id_admin ↔ id_office` y que los endpoints incorporen el filtro de sucursal.

### 2.2 Búsqueda de productos por código de barras falla
**Causa**: campo no indexado o parámetro ausente.  
**Solución**: confirma que el endpoint reciba el código y que exista índice en la columna; reindexa el array de categorías (hubo cambios al respecto).

### 2.3 Cierre de caja no permitido
**Causa**: validaciones añadidas impiden cierre con $0.00 o sin fecha final.  
**Solución**: corrige valores y registra fecha/hora final antes de cerrar; revisa reglas de negocio en el controlador de caja.

---

## 3) Impresión de tickets (local)

### 3.1 No imprime o imprime en blanco
- Verifica que la API apunte a la **impresora local** (migración desde impresión remota).
- Comprueba que el servicio/endpoint local esté en ejecución.
- Asegúrate de que el usuario del servicio tenga permisos sobre la impresora.

### 3.2 Tiempo de espera agotado
- Reduce tamaño de imagen/QR en el ticket.
- Revisa conectividad con el host local y firewalls.

---

## 4) Facturación electrónica (SRI)

### 4.1 XML rechazado por **fecha inválida**
**Causa**: “nuevo formato de fecha” — el SRI no acepta valores por defecto con ceros.  
**Solución**: valida y formatea fechas antes de generar el XML.

### 4.2 **IVA incorrecto** (12% vs 15%)
**Causa**: cambio regulatorio a **IVA 15%** desde 2025.  
**Solución**: verifica que el cálculo de impuestos y los códigos de tarifa reflejen el 15% en la generación del XML.

### 4.3 Error al **firmar** (JAR/ejecutable)
- Verifica **ruta** del ejecutable y permisos de ejecución.
- Habilita logs de **stdout/stderr** (refactor agregado en commits).
- Confirma que `java` esté instalado y accesible en `PATH` (si aplica).

### 4.4 "No hay certificado .p12 configurado"
**Causa**: el registro de `informations` del RUC no tiene un campo de certificado válido o apunta a una ruta inexistente.  
**Solución**:
- Sube el `.p12` desde el CMS en el campo de certificado.
- Confirma que la BD guarde una ruta tipo `certificados/archivo.p12`.
- Verifica que el archivo exista en `pos/certificados/`.
- Si la ruta antigua apunta a `cms/certificados/`, migra el archivo a `pos/certificados/` y actualiza el campo.

### 4.5 "La clave del certificado .p12 es incorrecta o el archivo no es válido"
**Causa**: clave incorrecta, archivo `.p12` corrupto/incompatible o contraseña guardada como hash irreversible.  
**Solución**:
- Valida el archivo con `keytool` o una herramienta como FirmaEC.
- Reingresa la clave desde el CMS para que quede cifrada de forma reversible.
- Si la clave inicia con `$2a$`, está guardada como hash y debe volver a ingresarse.
- Verifica que `app_key` no cambie después de cifrar la clave.

### 4.6 Error al **autorizar** (timeout o rechazo)
- Implementa reintentos con backoff.
- Guarda la **respuesta del SRI** para diagnóstico.
- Valida campos obligatorios (RUC, secuencial, clave de acceso, totales por tarifa).

### 4.7 Logo aparece como texto en "Información adicional"
**Causa**: XML antiguo o generador RIDE anterior enviaba `Logo` dentro de `infoAdicional`.  
**Solución**:
- El XML nuevo ya no agrega `Logo` como `campoAdicional`.
- El generador PDF ignora `Logo` si aparece en XMLs antiguos.
- Configura el logo en `informations` para que se use solo en el RIDE/PDF.

### 4.8 La venta se completa pero la orden queda en pantalla
**Causa**: el panel POS puede renderizar la orden pendiente antes de ejecutar el POST de cierre.  
**Solución**:
- El controlador de órdenes emite una limpieza de UI al confirmar venta exitosa.
- Si persiste, limpia caché del navegador y confirma que `cms/views/assets/js/pos/pos.js` esté actualizado.

---

## 5) QR y recursos estáticos

### 5.1 QR no se muestra
- Confirma inclusión correcta de `lib/phpqrcode/` y rutas relativas.
- Evita enviar encabezados duplicados si generas la imagen en caliente.
- Revisa permisos de la carpeta de salida (si se guarda a disco).

---

## 6) Email/Notificaciones

### 6.1 No se envía correo
- Verifica configuración SMTP (servidor, puerto, TLS/SSL, credenciales).
- Asegura `From` y `Reply-To` válidos, y que el servidor no bloquee `mail()`.
- Revisa manejo de errores en el controlador (se mejoró envío en commits).

---

## 7) Git y despliegues

### 7.1 `php_error_log` apareció en el repo
- Se añadió al `.gitignore` la exclusión para `cms/` y `api/`.
- Ejecuta:
  ```bash
  git rm -r --cached cms/php_error_log api/php_error_log
  git commit -m "Stop tracking php_error_log files"
  ```

### 7.2 Eliminación de archivos de prueba sensibles
- Se eliminó `test.php` (verificación de `curl_init`). Evita exponer archivos de prueba en producción.

### 7.3 Certificados o configuración sensible aparecen como cambios
- `cms/config/facturacion.config.php` debe permanecer ignorado.
- `certificados/*` y `cms/certificados/*` deben permanecer ignorados.
- No subas `.p12`, claves, `firmado_log.txt`, XML/PDF generados ni logs de worker.

---

## 8) Secuencial y concurrencia

**Síntomas**: duplicación de números de comprobante.  
**Soluciones**:
- En la consulta de obtención del siguiente secuencial, usa **bloqueo transaccional** (`SELECT ... FOR UPDATE`) o una **tabla de secuencias** con `AUTO_INCREMENT` y control de transacciones.
- Registra auditoría del último valor emitido y del usuario que lo tomó.

---

## 9) Dónde mirar logs

- **PHP/Servidor**: salida del PHP built-in server o `error.log` del servidor web usado.  
- **Aplicación**: archivos de log propios si existen (buscar en `api/` y `cms/`).  
- **Firma SRI**: captura `stdout/stderr` del proceso de firmado.  
- **Autorización SRI**: persistir la respuesta cruda (XML/JSON) para diagnóstico.
- **Worker de facturas**: `cms/xml/logs/{claveAcceso}.log`.
- **Firmador**: `cms/firmado_log.txt` (la clave se oculta en logs).

---

## 10) Buenas prácticas de producción

- Rotación de logs y backups automáticos de BD.  
- Variables sensibles fuera del repo (certificados, claves).  
- HTTPS en el panel (CMS) y CORS controlado si la API se expone públicamente.  
- Monitoreo de disponibilidad (healthchecks) para API, impresora local y firmador.
