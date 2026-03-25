# PROYINSTAL — CSS Refactorizado

## Estructura de archivos

```
css/
├── main.css                    ← Punto de entrada (solo @imports)
├── landing.css                 ← Estilos de la landing pública
├── core/
│   ├── variables.css           ← ★ Variables globales del sistema
│   ├── reset.css               ← Reset y body base
│   └── responsive.css          ← Media queries globales
├── layouts/
│   ├── auth.css                ← Login/Registro (incluye .auth-footer)
│   ├── navbar.css              ← Navbar del dashboard
│   └── page-box.css            ← ★ NUEVO: Box azul de cabecera unificado
├── components/
│   ├── buttons.css             ← Todos los botones del sistema
│   ├── forms.css               ← Inputs, labels, checkboxes
│   ├── links.css               ← Estilos de enlaces
│   ├── alerts.css              ← Alertas + divider
│   ├── tables.css              ← Tablas genéricas
│   ├── badges.css              ← Badges de estado
│   └── select2.css             ← Placeholder para sobrescrituras select2
└── pages/
    ├── dashboard.css           ← Hero + stats cards
    ├── usuarios.css            ← Buscador de usuarios
    └── proyectos-chatbot.css   ← Layout tabla + chat
```

## Cambios principales

### Variables ampliadas (`core/variables.css`)
Se pasaron todos los colores y valores hardcoded a variables:
- **Paleta de marca**: `--color-primary`, `--color-primary-dark`, `--color-primary-light`
- **Escala de grises completa**: `--color-gray-50` → `--color-gray-900`
- **Semánticos de estado**: `--color-success-*`, `--color-error-*`, `--color-info-*`, `--color-warning-*`
- **Gradientes**: `--gradient-primary`, `--gradient-body`, `--gradient-icon`
- **Transparencias**: `--overlay-10` → `--overlay-25`
- **Sombras**: `--shadow-sm/md/lg/xl/card`
- **Radios**: `--radius-sm` → `--radius-full`
- **Tipografía**: `--text-xs` → `--text-hero`
- **Transiciones**: `--transition`, `--transition-fast`
- **Focus ring**: `--focus-ring`
- **Aliases legacy** (`--proyinstal-*`) para no romper vistas existentes

### Nuevo layout unificado (`layouts/page-box.css`)
`box-proyectos-inner` y `box-usuarios-inner` eran **idénticos**. Se creó la clase canónica `.page-box-inner` y se mantienen los aliases de compatibilidad.

### Badges unificados (`components/badges.css`)
Los badges en inglés (`.badge-pending`) y español (`.badge-pendiente`) se declaraban por separado en dos archivos distintos. Ahora están en una sola regla compartida.

### `auth-footer` reubicado
Estaba en `navbar.css` — se movió a `auth.css` donde tiene sentido semántico.

### `select2.css` limpiado
El archivo estaba incompleto (CSS roto). Se limpió y se deja como placeholder comentado.
