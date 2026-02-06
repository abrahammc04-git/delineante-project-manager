# 📂 Estructura CSS Modular - PROYINSTAL

Esta es la estructura profesional del CSS de PROYINSTAL, organizada en módulos independientes y reutilizables.

## 📁 Estructura de Carpetas

```
css/
├── main.css                          # ⭐ Archivo principal (importa todos)
│
├── core/                             # 🔧 Configuración base
│   ├── variables.css                 # Variables CSS (colores, sombras, transiciones)
│   ├── reset.css                     # Reset y estilos base del body
│   └── responsive.css                # Media queries (siempre al final)
│
├── layouts/                          # 🏗️ Estructuras de página
│   ├── auth.css                      # Layout de login/register (guest-layout)
│   └── navbar.css                    # Navbar y navegación
│
├── components/                       # 🧩 Componentes reutilizables
│   ├── buttons.css                   # Todos los botones (.btn, .btn-primary, etc.)
│   ├── forms.css                     # Formularios (.form-input, .form-label, etc.)
│   ├── links.css                     # Enlaces (.link)
│   ├── alerts.css                    # Alertas y dividers
│   ├── tables.css                    # Tablas (.table-container, etc.)
│   ├── badges.css                    # Badges de estado
│   └── select2.css                   # Estilos para Select2
│
└── pages/                            # 📄 Estilos específicos por página
    ├── dashboard.css                 # Dashboard (.dashboard-hero, .stat-card, etc.)
    ├── usuarios.css                  # Página de usuarios (.box-usuarios, etc.)
    └── proyectos-chatbot.css         # Chatbot de proyectos

```

## 🚀 Uso

### Opción 1: Importar todo desde main.css (Recomendado)
```html
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
```

### Opción 2: Importar módulos específicos
Si solo necesitas ciertos módulos en una página:

```html
<!-- Core siempre -->
<link rel="stylesheet" href="{{ asset('css/core/variables.css') }}">
<link rel="stylesheet" href="{{ asset('css/core/reset.css') }}">

<!-- Componentes necesarios -->
<link rel="stylesheet" href="{{ asset('css/components/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/forms.css') }}">

<!-- Responsive al final -->
<link rel="stylesheet" href="{{ asset('css/core/responsive.css') }}">
```

## 📝 Contenido de cada módulo

### Core
- **variables.css**: `:root` con colores PROYINSTAL, sombras, transiciones
- **reset.css**: Reset CSS, estilos del `body`, `.app-bg`
- **responsive.css**: Todos los `@media` queries

### Layouts
- **auth.css**: `.guest-layout`, `.auth-container`, animaciones de login
- **navbar.css**: `.navbar`, `.navbar-container`, `.navbar-link`, `.navbar-right`

### Components
- **buttons.css**: `.btn`, `.btn-primary`, `.btn-secondary`, `.btn-hero`, etc.
- **forms.css**: `.form-input`, `.form-label`, `.form-error`, `.form-grid`, etc.
- **links.css**: `.link` con efecto de subrayado animado
- **alerts.css**: `.alert`, `.alert-success`, `.divider`
- **tables.css**: `.table-container`, `.table-header`, estilos de `<table>`
- **badges.css**: `.badge-pending`, `.badge-completed`, etc.
- **select2.css**: Personalización de Select2

### Pages
- **dashboard.css**: `.dashboard-hero`, `.stats-grid`, `.stat-card`
- **usuarios.css**: `.box-usuarios`, `.usuarios-buscador-wrap`, filtros
- **proyectos-chatbot.css**: Chatbot con estilo Tuenti, `.chat-panel`, etc.

## 🎯 Ventajas de esta estructura

✅ **Modularidad**: Cada archivo tiene una responsabilidad única
✅ **Mantenibilidad**: Fácil encontrar y modificar estilos específicos
✅ **Reutilización**: Componentes independientes usables en cualquier página
✅ **Performance**: Puedes cargar solo lo que necesitas
✅ **Escalabilidad**: Agregar nuevas páginas/componentes es simple
✅ **Profesional**: Estructura estándar en proyectos enterprise

## 🔄 Migración desde la versión anterior

**Antes:**
```html
<link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/chatbot-styles.css') }}">
```

**Ahora:**
```html
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
```

Todo funciona exactamente igual, solo que ahora el código está mejor organizado.

## 📦 Archivos generados desde

- `proyinstal-styles.css` (1225 líneas) → dividido en 14 módulos
- `chatbot-styles.css` (525 líneas) → renombrado a `pages/proyectos-chatbot.css`

**Total**: 1750 líneas → 15 archivos organizados profesionalmente
