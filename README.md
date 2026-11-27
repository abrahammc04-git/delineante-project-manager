# Delineante Project Manager

Desarrollada en equipo durante las prácticas (FCT) del Grado Superior en DAW para un delineante autónomo, como cliente real.

Aplicación web de gestión de proyectos para un despacho de delineación. El perfil **admin** gestiona empresas cliente, sus usuarios, proyectos y documentos técnicos. Los **clientes** acceden a los proyectos de su empresa; si no tienen empresa asignada, solo ven los proyectos asignados directamente a ellos. Incluye chat interno en tiempo real y un asistente IA solo visible para el admin.

---

## Funcionalidades

- **Autenticación** — login y registro público en `/register` (formulario Breeze adaptado al esquema propio: `nombre`, `apellidos`, `email`). Verificación de correo y reseteo de contraseña disponibles.
- **Gestión de empresas** — CRUD completo: listar, crear, editar y eliminar empresas.
- **Gestión de proyectos** — CRUD con tipo de proyecto, estado, localización y notas; los clientes solo ven los proyectos de su empresa.
- **Gestión de documentos** — subida, descarga y eliminación de archivos por proyecto; control de visibilidad manual o programada (ocultar/publicar en fecha y hora concretas).
- **Gestión de usuarios** — listado, edición y baja de usuarios; filtrado por empresa.
- **Dashboard** — resumen de proyectos por estado (total, en proceso, completados, pendientes) con tabla de proyectos recientes.
- **Chat interno en tiempo real** — mensajería privada admin ↔ cliente con soporte de adjuntos; canal WebSocket privado por conversación (Laravel Reverb + Echo); archivar/desarchivar conversaciones.
- **Chatbot IA** — asistente basado en la API de Groq (modelo configurable en `config/services.php`) visible únicamente para el admin; permite filtrar proyectos y hacer consultas en lenguaje natural.
- **Tareas programadas** — dos comandos Artisan se ejecutan cada minuto para aplicar la visibilidad programada de los documentos.

---

## Stack tecnológico

| Capa | Tecnología | Versión (requerida) |
|---|---|---|
| Backend | PHP | ^8.2 |
| Framework | Laravel | ^12.0 |
| WebSockets | Laravel Reverb | ^1.0 |
| Base de datos | MySQL | — |
| Frontend CSS | TailwindCSS | ^3.1.0 |
| Frontend JS | Alpine.js | ^3.4.2 |
| Bundler | Vite | ^7.0.7 |
| WS client | Laravel Echo | ^2.3.4 |
| WS client | Pusher.js | ^8.5.0 |
| HTTP cliente JS | Axios | ^1.11.0 |
| Alertas UI | SweetAlert2 | 11 (CDN) |
| IA | Groq API | — |
| Testing | PHPUnit | ^11.5.3 |

---

## Requisitos previos

- PHP 8.2 o superior con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `bcmath`, `tokenizer`, `xml`
- `pdo_sqlite` habilitada (los tests usan SQLite en memoria). En Windows, descomenta la línea `extension=pdo_sqlite` en tu `php.ini` y reinicia PHP.
- Composer 2.x
- Node.js 18+ y npm
- MySQL 8+ (crear la base de datos antes de migrar)

---

## Instalación paso a paso

### 1. Clonar e instalar dependencias

```bash
git clone https://github.com/abrahammc04-git/delineante-project-manager.git
cd delineante-project-manager
composer install
npm install
```

### 2. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

Abre `.env` y rellena al menos:

```
DB_DATABASE=delineante_projects
DB_USERNAME=tu_usuario_mysql
DB_PASSWORD=tu_contraseña_mysql

GROQ_API_KEY=gsk_...
```

Para el chat en tiempo real, cambia `BROADCAST_CONNECTION=log` por `reverb` y rellena las variables `REVERB_*` (ver sección [Variables de entorno](#variables-de-entorno)).

### 3. Configurar las claves de Reverb

El proyecto ya incluye `config/reverb.php`. No ejecutes `php artisan reverb:install` (sobrescribiría la configuración). Simplemente asigna en tu `.env` los valores de `REVERB_APP_ID`, `REVERB_APP_KEY` y `REVERB_APP_SECRET` — en desarrollo puedes usar cualquier cadena, por ejemplo:

```env
REVERB_APP_ID=local-app
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

Los valores deben coincidir entre servidor (`.env`) y cliente (`VITE_REVERB_*`).

### 4. Migraciones y seeders

```bash
php artisan migrate
php artisan db:seed
```

### 5. Enlace de almacenamiento público

```bash
php artisan storage:link
```

### 6. Compilar assets

```bash
# Producción
npm run build

# Desarrollo (watch)
npm run dev
```

### 7. Arrancar los procesos

La app requiere **cinco procesos** corriendo en paralelo:

| Proceso | Comando | Para qué sirve |
|---|---|---|
| Servidor web | `php artisan serve` | Sirve la aplicación en `localhost:8000` |
| WebSocket server | `php artisan reverb:start` | Chat en tiempo real (puerto 8080 por defecto) |
| Cola de trabajos | `php artisan queue:listen --tries=1` | Broadcasting y jobs asíncronos |
| Scheduler | `php artisan schedule:work` | Visibilidad programada de documentos (ejecuta comandos cada minuto) |
| Vite (dev) | `npm run dev` | Hot-reload de assets en desarrollo |

> Sin el scheduler, la ocultación/publicación programada de documentos **no se aplica**.

**Atajo en Linux / macOS:** el script `composer dev` arranca servidor, cola, visor de logs (Pail) y Vite con colores, **pero no arranca Reverb ni el scheduler**:

```bash
# Terminal 1
php artisan reverb:start

# Terminal 2
php artisan schedule:work

# Terminal 3 (servidor + cola + Pail + Vite)
composer dev
```

**Atajo en Windows:** Pail requiere la extensión `pcntl` (solo Linux). Usa `composer dev:win`, que incluye scheduler y omite Pail:

```bash
# Terminal 1 (PowerShell o CMD)
php artisan reverb:start

# Terminal 2 (servidor + cola + scheduler + Vite)
composer dev:win
```

> Si necesitas los logs en Windows puedes abrir un tercer terminal y ejecutar `php artisan pail` — o simplemente revisar `storage/logs/laravel.log`.

---

## Variables de entorno

Solo se listan los nombres, sin valores. El archivo `.env.example` incluido en el repositorio ya contiene todas estas variables vacías.

```env
# Aplicación
APP_NAME=
APP_ENV=
APP_KEY=
APP_DEBUG=
APP_URL=http://localhost:8000
APP_LOCALE=es

# Base de datos
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=delineante_projects
DB_USERNAME=
DB_PASSWORD=

# Broadcasting / WebSockets (Reverb)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Variables Vite (referencian los valores de arriba)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Cola de trabajos
QUEUE_CONNECTION=database

# Sesiones
SESSION_DRIVER=database

# IA Chatbot
GROQ_API_KEY=
GROQ_MODEL=

# Correo (proyecto configurado con Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=
```

`GROQ_MODEL` es opcional; si se deja en blanco, el código usa `openai/gpt-oss-120b` como valor por defecto (ver `config/services.php`).

### Cómo obtener la clave de Groq

1. Crea una cuenta en [console.groq.com](https://console.groq.com).
2. Ve a **API Keys → Create API key**.
3. Copia la clave (empieza por `gsk_`) y pégala como valor de `GROQ_API_KEY=` en tu `.env`.

---

## Usuarios de ejemplo

Después de ejecutar `php artisan db:seed` se crean automáticamente:

| Rol | Email | Contraseña | Empresa |
|---|---|---|---|
| admin | admin@gmail.com | `1234` | — |
| cliente | cliente1@gmail.com | `1234` | Tech Solutions S.L. |
| cliente | cliente2@test.com | `1234` | Tech Solutions S.L. |
| cliente | cliente3@test.com | `1234` | Funeraria JJ S.A. |
| cliente | cliente4@test.com | `1234` | Funeraria JJ S.A. |

> **Aviso:** Credenciales solo para pruebas locales; cámbialas o elimina el seeder antes de cualquier despliegue real.

---

## Estructura de carpetas relevante

```
delineante-project-manager/
├── app/
│   ├── Console/Commands/          # OcultarArchivosProgramados, MostrarArchivosProgramados,
│   │                              # MigrarCarpetasArchivos (archivos:migrar-carpetas --dry-run)
│   ├── Events/                    # MensajeEnviado, MensajeEliminado (broadcasting Reverb)
│   ├── Http/
│   │   ├── Controllers/           # DashboardController, ProyectoController, ChatController,
│   │   │                          # ChatbotController, EmpresaController, UsuarioController…
│   │   └── Middleware/            # AdminMiddleware, CheckEmpresaAccess
│   └── Models/                    # User, Proyecto, Documento, Empresa,
│                                  # Conversacion, Mensaje, ArchivoChat
├── config/
│   ├── filesystems.php            # Disco "proyectos" → storage/app/private/proyectos
│   └── services.php               # Claves GROQ_API_KEY y GROQ_MODEL (modelo del chatbot)
├── database/
│   ├── migrations/                # 17 migraciones (tablas propias + Laravel defaults)
│   └── seeders/DatabaseSeeder.php # 2 empresas + 1 admin + 4 clientes de prueba
├── resources/
│   ├── js/
│   │   ├── app.js                 # Bootstrap + Alpine.js
│   │   ├── bootstrap.js           # Axios + importa echo.js
│   │   └── echo.js                # Laravel Echo configurado con broadcaster Reverb
│   └── views/                     # Plantillas Blade (dashboard, proyectos, chat, usuarios…)
├── routes/
│   ├── web.php                    # Rutas principales
│   ├── channels.php               # Canal privado chat.{id_conversacion}
│   └── console.php                # Tareas programadas cada minuto
└── storage/
    └── app/private/proyectos/     # Archivos de proyectos (disco privado, no accesible por URL)
```

---

## Ejecutar los tests

```bash
composer test
# o directamente:
php artisan test
```

Los tests usan SQLite en memoria (configurado en `phpunit.xml`). Requiere la extensión `pdo_sqlite` habilitada (ver [Requisitos previos](#requisitos-previos)).

### Estado actual de los tests

**52 tests, 104 aserciones — todos pasan.**

| Suite | Tests |
|---|---|
| `Unit\ExampleTest` | 1 |
| `Feature\ExampleTest` | 1 |
| `Feature\Auth\AuthenticationTest` | 4 |
| `Feature\Auth\EmailVerificationTest` | 3 |
| `Feature\Auth\PasswordConfirmationTest` | 3 |
| `Feature\Auth\PasswordResetTest` | 4 |
| `Feature\Auth\PasswordUpdateTest` | 2 |
| `Feature\Auth\RegistrationTest` | 2 |
| `Feature\Auth\RegistroSeguridadTest` | 2 |
| `Feature\ProfileTest` | 5 |
| `Feature\AutorizacionTest` | 3 |
| `Feature\PermisosTest` | 13 |
| `Feature\EmpresaCrudTest` | 7 |
| `Feature\EnlaceEmpresasTest` | 2 |

`AutorizacionTest` cubre la lógica de negocio propia: aislamiento entre empresas, 403 en el chatbot para clientes y acceso del admin a empresas. `RegistroSeguridadTest` verifica que el registro público siempre asigna `rol=cliente` aunque se envíe `rol=admin` por POST. `PermisosTest` cubre los controles de acceso sobre usuarios, archivos de proyecto, visibilidad y chat. `EmpresaCrudTest` cubre el CRUD de empresas (crear, editar, acceso denegado a clientes). `EnlaceEmpresasTest` verifica que el admin ve el enlace a Empresas en el dashboard y el cliente no lo ve.

---

## Limitaciones y mejoras futuras

- **Escalabilidad del chatbot:** en cada consulta se envían todos los proyectos de la base de datos al modelo. Esto no escala con volúmenes grandes; la mejora natural sería filtrar en base de datos antes de llamar a la API, o implementar búsqueda semántica.
- **Cobertura de tests:** los tests de autenticación y Breeze están adaptados y pasan; la lógica de negocio propia está cubierta por `AutorizacionTest`, `RegistroSeguridadTest` y `PermisosTest`. Queda pendiente cubrir el flujo completo del chatbot y los CRUD de proyectos y empresas.
- **Procesos adicionales en local:** el chat requiere `reverb:start` y la cola; la visibilidad programada requiere `schedule:work`. Son tres procesos extra además del servidor y Vite — `composer dev:win` facilita esto en Windows.
- **Modelos de Groq cambian:** durante el desarrollo hubo que migrar el modelo tras la retirada de `llama-3.3-70b-versatile` (agosto 2026). Por eso el modelo se configura mediante `GROQ_MODEL` en `.env` y `config/services.php`, sin hardcodear.

---

## Problemas frecuentes en Windows

### `composer dev` falla por Pail / pcntl

Pail requiere la extensión `pcntl`, que solo existe en Linux. En Windows usa el script alternativo:

```bash
php artisan reverb:start   # Terminal 1
composer dev:win           # Terminal 2
```

`dev:win` incluye servidor, cola, scheduler y Vite, sin Pail.

---

### Chatbot devuelve "cURL error 60: SSL certificate problem"

PHP no incluye el bundle de certificados de CA por defecto en Windows. Solución:

1. Descarga el bundle de Mozilla: [https://curl.se/ca/cacert.pem](https://curl.se/ca/cacert.pem)
2. Guárdalo en una ruta estable, por ejemplo `C:\php84\extras\ssl\cacert.pem`.
3. Edita tu `php.ini` y descomenta/ajusta estas dos líneas:
   ```ini
   curl.cainfo = "C:\php84\extras\ssl\cacert.pem"
   openssl.cafile = "C:\php84\extras\ssl\cacert.pem"
   ```
4. Reinicia los procesos de PHP.

> La aplicación desactiva automáticamente la verificación SSL cuando `APP_ENV=local` (workaround integrado en `ChatbotController`). En entornos no-locales la verificación siempre está activa. Si prefieres tener el bundle correcto también en local, sigue los pasos anteriores y el workaround quedará inactivo.

---

### "There are no commands defined in the reverb namespace"

Significa que el paquete `laravel/reverb` no está instalado. Ejecuta:

```bash
composer install
```

---

### Errores de tablas inexistentes tras un `git pull`

Si ves errores como "Table X doesn't exist" al arrancar la app después de sincronizar el repositorio, ejecuta las migraciones pendientes:

```bash
php artisan migrate
```

> Asegúrate de que MySQL está arrancado antes de migrar.

---

## Equipo

- **[Abraham](https://github.com/abrahammc04-git)**
- **Roberto**
- **Moisés**
