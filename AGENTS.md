# Guía para Agentes de IA — Proyecto POS

> Fuente única para Claude Code (`CLAUDE.md`), Codex (`AGENTS.md`) y cualquier otro agente. `CLAUDE.md` es un symlink a este archivo — edita solo aquí.

## 🛠️ Stack Tecnológico

### Backend
- **PHP 8.1.34** — Lenguaje principal
- **MySQL 9.7.1** — Base de datos (Homebrew)
- **OpenSSL 3.1.7** — Encriptación y firma electrónica

### Servidor Local
- **PHP Built-in Server** — Sin Apache/Nginx
  - CMS: `php -S 0.0.0.0:8000` desde carpeta `cms/`
  - API: `php -S 0.0.0.0:8001` desde carpeta `api/`
- **Dominios Virtuales**: Configurados en `/etc/hosts`
  - `127.0.0.1 cms.pos.com`
  - `127.0.0.1 api.pos.com`

### Credenciales Base de Datos
```
Usuario: root
Contraseña: root
BD: u590035688_pos2
Charset: utf8mb4
Collation: utf8mb4_unicode_ci
```

### Variables de Entorno
- Credenciales de BD y el token compartido CMS↔API ya **no están hardcodeados** en el código: se leen desde `.env` (raíz del proyecto, gitignored) vía `api/config/env.php` y `cms/config/env.php`.
- Primer setup: `cp .env.example .env` y completar `DB_DATABASE`, `DB_USER`, `DB_PASS`, `API_TOKEN`, `APP_ENV`.
- `APP_ENV=local` muestra errores en pantalla (`display_errors`); `APP_ENV=production` los oculta y solo los deja en `php_error_log`.

---

## 📁 Estructura del Proyecto

```
pos/
├── cms/                    # Interfaz web (CMS)
│   ├── controllers/        # Lógica de controladores
│   ├── views/              # Plantillas HTML
│   │   └── assets/         # CSS, JS, imágenes
│   ├── ajax/               # Endpoints AJAX
│   ├── GeneratePDFfromXML/ # Generación RIDE/PDF
│   ├── xml/                # XML no firmados, firmados, autorizados, PDFs y logs
│   ├── index.php           # Punto de entrada CMS
│   └── php_error_log       # Logs de PHP
├── api/                    # API REST
│   ├── controllers/        # Rutas y lógica
│   ├── models/             # Conexión y queries
│   │   └── connection.php  # Config DB (lee de .env)
│   ├── index.php           # Punto de entrada API
│   └── php_error_log       # Logs de PHP
├── lib/
│   └── phpqrcode/          # Generación de códigos QR
├── certificados/           # Certificados .p12 fuera del docroot (ignorado)
├── temp/                   # Archivos temporales (XML, tickets)
├── QR/                     # Recursos QR
└── u590035688_pos2.sql     # Dump de BD
```

---

## 🔧 Configuración Clave

### MySQL (Homebrew)
```bash
# Iniciar/Parar
brew services start mysql
brew services stop mysql
brew services restart mysql

# Acceder
mysql -u root -proot u590035688_pos2
```

### PHP (Homebrew)
```bash
# Versión instalada
php --version           # PHP 8.1.34

# Extensiones habilitadas
- curl
- openssl
- mbstring
- json
- mysqli
- PDO
```

### URLs de Acceso Local
| Servicio | URL | Puerto |
|----------|-----|--------|
| CMS | http://cms.pos.com:8000 | 8000 |
| API | http://api.pos.com:8001 | 8001 |
| MySQL | localhost | 3306 |

---

## 🧾 Facturación Electrónica

- **Datos del emisor**: se leen desde la tabla `informations`, no desde valores locales.
- **Campos clave**: `ruc_information`, `name_information`, `name_comercial_information`, `address_matriz_information`, `address_establishment_information`, `logo_information`, `certification_information`, `password_certification_information`.
- **Certificados `.p12`**: los nuevos uploads se guardan en `pos/certificados/`; `cms/certificados/` solo queda para compatibilidad de archivos antiguos.
- **Clave `.p12`**: se cifra de forma reversible con `SecretController`; requiere `app_key` en `cms/config/facturacion.config.php` o `POS_APP_KEY`.
- **Firma**: valida el `.p12` con `keytool` antes de ejecutar el firmador.
- **PDF/RIDE**: el logo se obtiene desde `informations`; no debe imprimirse como `Logo` en información adicional.
- **Worker**: `cms/workers/procesar_factura.php` autoriza en SRI, genera PDF, envía correo y actualiza `invoices`.

---

## 🔗 Conexión CMS ↔ API

- **Archivo**: `cms/controllers/curl.controller.php`
- **URL Base**: `http://127.0.0.1:8001/` (no `api.pos.com`)
- **Razón**: Evita deadlock cuando el mismo proceso PHP se llama a sí mismo
- **Auth Header**: `Authorization: <API_TOKEN de .env>` (valor local actual: `kbaksdhaisdh912312837sajhd12093ke`)

---

## ⚠️ Cambios Mínimos Respecto al Original

Cambios locales/base para macOS:

1. **`curl.controller.php`** → URL API a `127.0.0.1:8001` + timeout 30s
2. **`install.controller.php`** → `CREATE TABLE IF NOT EXISTS` (reintentos seguros)
3. **`template.php`** → Validar `$adminTable` null antes de acceder
4. **`.gitignore`** → Ignorar `.DS_Store`, config local, certificados, XML/logs generados
5. **Facturación EC** → Datos desde `informations`, certificado `.p12`, worker SRI y RIDE/PDF

---

## 🚀 Arranque Manual

```bash
# 0. Primera vez: copiar y completar variables de entorno
cp .env.example .env

# 1. Iniciar MySQL
brew services start mysql

# 2. Levantar CMS (puerto 8000)
cd /Users/jeanfri/Documents/Proyectos/pos/cms
php -S 0.0.0.0:8000 &

# 3. Levantar API (puerto 8001)
cd /Users/jeanfri/Documents/Proyectos/pos/api
php -S 0.0.0.0:8001 &

# 4. Acceder
# CMS: http://cms.pos.com:8000
# API: http://api.pos.com:8001
```

---

## 📝 Credenciales de Acceso (CMS)

| Email | Contraseña | Rol |
|-------|-----------|-----|
| superadmin@pos.com | 123456 | superadmin |
| admin@pos.com | 123456 | admin |
| supervisor@pos.com | 123456 | editor |

---

## 🔍 Troubleshooting Común

### CMS muestra "Instalación Dashboard"
- Verificar que existe `.env` (copiado de `.env.example`) con `API_TOKEN` completo
- Verificar que API responde: `curl http://api.pos.com:8001/admins -H "Authorization: $(grep API_TOKEN .env | cut -d= -f2)"`
- Verificar MySQL: `mysql -u root -proot u590035688_pos2 -e "SELECT 1;"`

### Assets (CSS/JS) no cargan
- Verificar rutas en `cms/views/template.php` — deben ser `/views/assets/` (no `/cms/views/assets/`)

### Firma SRI falla por certificado
- Verificar `certification_information` y `password_certification_information` en `informations`
- Verificar que el archivo exista en `pos/certificados/`
- Verificar `app_key` en `cms/config/facturacion.config.php`
- Confirmar que `java` y `keytool` estén disponibles

### MySQL no conecta
- Verificar estado: `brew services list | grep mysql`
- Reiniciar: `brew services restart mysql`

### Puerto 8000/8001 ya en uso
```bash
# Encontrar proceso
lsof -i :8000
lsof -i :8001

# Matar proceso
kill -9 <PID>
```

---

## 📋 Checklist Pre-Desarrollo

- [ ] `.env` existe (copiado de `.env.example` y completado)
- [ ] `brew services start mysql` (MySQL corriendo)
- [ ] `php --version` (PHP 8.1.34 disponible)
- [ ] `mysql -u root -proot u590035688_pos2 -e "SELECT 1;"` (BD accesible)
- [ ] `/etc/hosts` tiene `cms.pos.com` y `api.pos.com`
- [ ] CMS responde: `http://cms.pos.com:8000`
- [ ] API responde: `http://api.pos.com:8001/admins`

---

## 🎯 Próximas Tareas Pendientes

- [ ] Refactorizar código duplicado (ej. patrón `CurlController::request` repetido en ~40 lugares)
- [ ] Crear script único de arranque (start.sh)
- [ ] Documentar endpoints de API
- [ ] Tests automatizados
- [ ] CSRF en formularios del CMS
- [ ] Migrar de `php -S` a PHP-FPM + Nginx/Apache antes de producción
- [ ] HTTPS antes de salir de producción

### ✅ Deuda técnica resuelta (2026-07-12)
- Token de API y credenciales de BD movidos de hardcodeados a `.env`
- Queries con interpolación directa (`install.controller.php`, `connection.php`) parametrizadas con PDO
- `display_errors` gateado por `APP_ENV` (antes forzado a `1` siempre)
- `vendor/` (api, cms/mail, cms/extensions) sacado del tracking de git — ya no puede romperse como gitlink corrupto
- Confirmado: bloqueo transaccional (`FOR UPDATE`) en generación de secuencial ya estaba implementado

---

## 📚 Documentación Relacionada

- [README.md](README.md) — Funcionalidades del proyecto
- [ARCHITECTURE.md](ARCHITECTURE.md) — Arquitectura del sistema
- [TROUBLESHOOTING.md](TROUBLESHOOTING.md) — Guía de problemas

---

**Última actualización**: Julio 2026
**Plataforma**: macOS con Homebrew
