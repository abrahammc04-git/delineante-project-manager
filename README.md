# Delineante Project Manager

Aplicación web de gestión de proyectos para un despacho de delineación. El perfil **admin** gestiona empresas cliente, sus usuarios, proyectos y documentos técnicos. Los **clientes** acceden únicamente a sus propios proyectos y documentos. Incluye chat interno en tiempo real y un asistente IA solo visible para el admin.

---

## Funcionalidades

- **Autenticación** — registro, login, verificación de correo y reseteo de contraseña (Laravel Breeze)
- **Gestión de empresas** — CRUD completo solo para el admin: crear, listar, editar y eliminar empresas con sus datos fiscales
- **Gestión de proyectos** — CRUD con tipo de proyecto, estado, localización y notas; los clientes solo ven los proyectos de su empresa
- **Gestión de documentos** — subida, descarga y eliminación de archivos por proyecto; control de visibilidad manual o programada (ocultar/publicar en fecha y hora concretas)
- **Gestión de usuarios** — listado, edición y baja de usuarios; filtrado por empresa
- **Dashboard** — resumen de proyectos por estado (total, en proceso, completados, pendientes) con tabla de proyectos recientes
- **Chat interno en tiempo real** — mensajería privada admin ↔ cliente con soporte de adjuntos; canal WebSocket privado por conversación (Laravel Reverb + Echo); archivar/desarchivar conversaciones
- **Chatbot IA** — asistente basado en Groq (`llama-3.3-70b-versatile`, capa gratuita) visible únicamente para el admin; permite filtrar proyectos y hacer consultas en lenguaje natural
- **Tareas programadas** — dos comandos Artisan se ejecutan cada minuto para aplicar la visibilidad programada de los documentos

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
| Alertas UI | SweetAlert2 | [POR CONFIRMAR versión] |
| IA | Groq API | — |
| Testing | PHPUnit | ^11.5.3 |

---

## Requisitos previos

- PHP 8.2 o superior con extensiones: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `bcmath`, `tokenizer`, `xml`
- Composer 2.x
- Node.js 18+ y npm
- MySQL 8+ (crear la base de datos antes de migrar)

---

## Instalación paso a paso

### 1. Clonar e instalar dependencias

```bash
git clone <url-del-repo>
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

### 3. Generar claves de Reverb

Si las variables `REVERB_APP_ID`, `REVERB_APP_KEY` y `REVERB_APP_SECRET` están vacías en tu `.env`, ejecútalas:

```bash
php artisan reverb:install
```

Esto genera las claves y las añade automáticamente al `.env`.

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

# Correo (proyecto configurado con Mailtrap)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Cómo obtener la clave de Groq

1. Crea una cuenta gratuita en [console.groq.com](https://console.groq.com).
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

> **Aviso:** No uses estas credenciales en producción.

---

## Estructura de carpetas relevante

```
delineante-project-manager/
├── app/
│   ├── Console/Commands/          # OcultarArchivosProgramados, MostrarArchivosProgramados
│   ├── Events/                    # MensajeEnviado, MensajeEliminado (broadcasting Reverb)
│   ├── Http/
│   │   ├── Controllers/           # DashboardController, ProyectoController, ChatController,
│   │   │                          # ChatbotController, EmpresaController, UsuarioController…
│   │   └── Middleware/            # AdminMiddleware, CheckEmpresaAccess
│   └── Models/                    # User, Proyecto, Documento, Empresa,
│                                  # Conversacion, Mensaje, ArchivoChat
├── config/
│   ├── filesystems.php            # Disco "proyectos" → storage/app/private/proyectos
│   └── services.php               # Clave GROQ_API_KEY
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

Los tests usan SQLite en memoria (configurado en `phpunit.xml`). Actualmente cubren:

- **Feature/Auth** — login, registro, verificación de correo, reseteo y actualización de contraseña, confirmación de contraseña
- **Feature/ProfileTest** — edición y eliminación de perfil
- **Feature/ExampleTest** y **Unit/ExampleTest** — scaffolding por defecto de Laravel (sin lógica de negocio propia)

---

## Capturas de pantalla

> Añade las imágenes en la carpeta `docs/` y descomenta estas líneas.

![Dashboard](docs/captura-dashboard.png)
![Listado de proyectos](docs/captura-proyectos.png)
![Detalle de proyecto con documentos](docs/captura-proyecto-detalle.png)
![Chat interno en tiempo real](docs/captura-chat.png)
![Chatbot IA (solo admin)](docs/captura-chatbot.png)
![Gestión de empresas](docs/captura-empresas.png)

---

## Equipo

- **Abraham**
- **Roberto**
- **Moisés**
