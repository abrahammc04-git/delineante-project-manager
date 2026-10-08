<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROYINSTAL — Delineación &amp; Proyectos Técnicos</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

<!-- NAV -->
<nav class="landing-nav" id="mainNav">
    <div class="nav-inner">
        <a href="#landing-hero" class="landing-nav-logo">
            <span class="landing-nav-logo-dot"></span>
            PROYINSTAL
        </a>
        <ul class="landing-nav-links">
            <li><a href="#landing-hero">Inicio</a></li>
            <li><a href="#landing-servicios">Servicios</a></li>
            <li><a href="#landing-experiencia">Experiencia</a></li>
            <li><a href="#landing-proyectos">Proyectos</a></li>
            <li><a href="#landing-contacto">Contacto</a></li>
            <li>
                @if(Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="landing-nav-cta">Dashboard →</a>
                    @else
                        <a href="{{ route('login') }}" class="landing-nav-cta">Acceder →</a>
                    @endauth
                @endif
            </li>
        </ul>
        <button class="landing-nav-hamburger" id="hamburger" aria-label="Menú">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<div class="landing-nav-mobile" id="mobileNav">
    <a href="#landing-hero"         onclick="closeMobile()">Inicio</a>
    <a href="#landing-servicios"    onclick="closeMobile()">Servicios</a>
    <a href="#landing-experiencia"  onclick="closeMobile()">Experiencia</a>
    <a href="#landing-proyectos"    onclick="closeMobile()">Proyectos</a>
    <a href="#landing-contacto"     onclick="closeMobile()">Contacto</a>
    @if(Route::has('login'))
        @auth
            <a href="{{ url('/dashboard') }}">Ir al Dashboard →</a>
        @else
            <a href="{{ route('login') }}">Iniciar Sesión →</a>
        @endauth
    @endif
</div>

<!-- HERO -->
<section id="landing-hero" class="bp-grid-dark">
    <div class="hero-circle hero-circle-1"></div>
    <div class="hero-circle hero-circle-2"></div>
    <div class="hero-circle hero-circle-3"></div>
    <div class="hero-line"></div>

    <div class="hero-inner">
        <div>
            <p class="hero-tag">Delineación · Proyectos Técnicos</p>
            <h1 class="hero-h1">Precisión<br>en cada<br><em>línea.</em></h1>
            <p class="hero-sub">Especialistas en delineación industrial, comercial y residencial. Transformamos ideas en planos con la máxima exactitud técnica.</p>
            <div class="hero-btns">
                <a href="#landing-proyectos" class="btn-landing-primary">
                    Ver proyectos
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="#landing-contacto" class="btn-landing-ghost">Solicitar presupuesto</a>
            </div>
        </div>

        <div class="hero-stats">
            <div class="hero-stat-card">
                <div class="hero-stat-num">+120</div>
                <div class="hero-stat-label"><strong>Proyectos entregados</strong>Industrial, comercial y residencial</div>
            </div>
            <div class="hero-stat-card">
                <div class="hero-stat-num">15</div>
                <div class="hero-stat-label"><strong>Años de experiencia</strong>En delineación y diseño técnico</div>
            </div>
            <div class="hero-stat-card">
                <div class="hero-stat-num">98%</div>
                <div class="hero-stat-label"><strong>Clientes satisfechos</strong>Entrega en plazo garantizada</div>
            </div>
        </div>
    </div>

    <div class="hero-scroll">
        <div class="hero-scroll-line"></div>
        scroll
    </div>
</section>

<!-- SERVICIOS -->
<section id="landing-servicios">
    <div class="landing-section-inner" style="padding: 7rem 2rem">
        <p class="landing-eyebrow reveal">Qué hacemos</p>
        <h2 class="landing-title reveal">Servicios</h2>
        <p class="landing-subtitle reveal">Cobertura técnica completa desde el anteproyecto hasta la documentación final de obra.</p>

        <div class="services-grid">
            <div class="service-card reveal">
                <div class="service-num">01</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
                <h3 class="service-title">Planos de Planta y Alzado</h3>
                <p class="service-desc">Levantamientos 2D de planta, alzados y secciones con cotas y acabados según normativa vigente.</p>
            </div>
            <div class="service-card reveal reveal-delay-1">
                <div class="service-num">02</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
                <h3 class="service-title">Modelado BIM / 3D</h3>
                <p class="service-desc">Modelos tridimensionales en Revit, ArchiCAD y AutoCAD 3D para coordinación de instalaciones y visualización.</p>
            </div>
            <div class="service-card reveal reveal-delay-2">
                <div class="service-num">03</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                <h3 class="service-title">Documentación Técnica</h3>
                <p class="service-desc">Memorias descriptivas, especificaciones de materiales, pliegos de condiciones y reportes de seguimiento de obra.</p>
            </div>
            <div class="service-card reveal reveal-delay-1">
                <div class="service-num">04</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
                <h3 class="service-title">Reforma y Rehabilitación</h3>
                <p class="service-desc">As-built de edificios existentes, detalles constructivos y planos de reforma para licencias municipales.</p>
            </div>
            <div class="service-card reveal reveal-delay-2">
                <div class="service-num">05</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/><polyline points="13 2 13 9 20 9"/></svg></div>
                <h3 class="service-title">Proyectos de Instalaciones</h3>
                <p class="service-desc">Esquemas unifilares, planos de electricidad, fontanería, climatización y telecomunicaciones.</p>
            </div>
            <div class="service-card reveal reveal-delay-3">
                <div class="service-num">06</div>
                <div class="service-icon-wrap"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
                <h3 class="service-title">Urbanismo y Parcelación</h3>
                <p class="service-desc">Planos de parcelación, segregación, alineaciones y rasantes para trámites urbanísticos.</p>
            </div>
        </div>
    </div>
</section>

<!-- EXPERIENCIA -->
<section id="landing-experiencia" class="bp-grid-dark">
    <div class="exp-inner">
        <div>
            <p class="landing-eyebrow landing-eyebrow--light reveal">Quiénes somos</p>
            <h2 class="landing-title landing-title--light reveal">Experiencia<br>que respalda</h2>
            <p class="landing-subtitle landing-subtitle--light reveal">Más de quince años trabajando junto a arquitectos, ingenieros y promotores en toda la geografía nacional.</p>
            <p class="exp-text reveal">Nuestro equipo combina formación técnica rigurosa con el uso de las herramientas más actuales del sector: AutoCAD, Revit, ArchiCAD y Civil 3D. Entendemos los plazos del cliente y entregamos documentación limpia, ordenada y lista para visar.</p>
            <div class="exp-values reveal">
                <div class="exp-value">Rigor técnico y normativa siempre actualizada</div>
                <div class="exp-value">Entrega en plazo con seguimiento en tiempo real</div>
                <div class="exp-value">Confidencialidad y trato personalizado</div>
                <div class="exp-value">Compatibilidad con cualquier software BIM del cliente</div>
            </div>
            <a href="#landing-contacto" class="btn-landing-ghost reveal" style="display:inline-flex;align-items:center;gap:.5rem">
                Trabajemos juntos
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="exp-timeline reveal">
            <div class="exp-milestone">
                <div class="exp-milestone-year">2009</div>
                <div class="exp-milestone-content">
                    <p class="exp-milestone-title">Fundación del despacho</p>
                    <p class="exp-milestone-desc">Inicio de actividad especializada en delineación industrial y residencial.</p>
                </div>
            </div>
            <div class="exp-milestone">
                <div class="exp-milestone-year">2013</div>
                <div class="exp-milestone-content">
                    <p class="exp-milestone-title">Implementación BIM</p>
                    <p class="exp-milestone-desc">Adopción temprana de flujos de trabajo BIM con Revit y ArchiCAD.</p>
                </div>
            </div>
            <div class="exp-milestone">
                <div class="exp-milestone-year">2017</div>
                <div class="exp-milestone-content">
                    <p class="exp-milestone-title">Expansión a instalaciones</p>
                    <p class="exp-milestone-desc">Incorporación de proyectos de electricidad, climatización y fontanería.</p>
                </div>
            </div>
            <div class="exp-milestone">
                <div class="exp-milestone-year">2021</div>
                <div class="exp-milestone-content">
                    <p class="exp-milestone-title">Plataforma digital propia</p>
                    <p class="exp-milestone-desc">Lanzamiento de PROYINSTAL para gestión y seguimiento online de proyectos.</p>
                </div>
            </div>
            <div class="exp-milestone">
                <div class="exp-milestone-year">Hoy</div>
                <div class="exp-milestone-content">
                    <p class="exp-milestone-title">+120 proyectos entregados</p>
                    <p class="exp-milestone-desc">Clientes en toda España. Equipo de 6 delineantes especializados.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PROYECTOS -->
<section id="landing-proyectos">
    <div class="landing-section-inner" style="padding: 7rem 2rem">
        <p class="landing-eyebrow reveal">Portfolio</p>
        <h2 class="landing-title reveal">Proyectos destacados</h2>
        <p class="landing-subtitle reveal">Una selección de los trabajos más representativos de los últimos años.</p>

        <div class="projects-grid">
            <div class="project-card reveal">
                <div class="project-thumb">
                    <div class="project-thumb-grid"></div>
                    <svg class="project-thumb-shape thumb-floor-plan" viewBox="0 0 140 120"><rect x="10" y="10" width="120" height="100" rx="2"/><line x1="10" y1="50" x2="130" y2="50"/><line x1="70" y1="10" x2="70" y2="110"/><rect x="20" y="20" width="35" height="20" rx="1"/><rect x="75" y="20" width="45" height="20" rx="1"/><rect x="20" y="60" width="55" height="40" rx="1"/><rect x="85" y="60" width="35" height="20" rx="1"/><line x1="58" y1="50" x2="58" y2="10" stroke-dasharray="3,3"/></svg>
                    <span class="project-badge">Industrial</span>
                </div>
                <div class="project-body">
                    <p class="project-type">Nave industrial · 2023</p>
                    <h3 class="project-name">Nave Logística Sector Norte</h3>
                    <p class="project-desc">Planos completos de arquitectura, estructura e instalaciones para nave de 4.200 m² con muelles de carga.</p>
                    <div class="project-meta">
                        <div class="project-meta-item">Superficie<strong>4.200 m²</strong></div>
                        <div class="project-meta-item">Software<strong>AutoCAD + Revit</strong></div>
                        <div class="project-meta-item">Plazo<strong>6 semanas</strong></div>
                    </div>
                </div>
            </div>

            <div class="project-card reveal reveal-delay-1">
                <div class="project-thumb">
                    <div class="project-thumb-grid"></div>
                    <svg class="project-thumb-shape thumb-floor-plan" viewBox="0 0 140 120"><polygon points="70,10 130,50 130,110 10,110 10,50"/><line x1="10" y1="70" x2="130" y2="70"/><rect x="25" y="78" width="30" height="25" rx="1"/><rect x="65" y="78" width="50" height="25" rx="1"/><rect x="40" y="52" width="60" height="15" rx="1"/><line x1="70" y1="10" x2="70" y2="70" stroke-dasharray="4,3"/></svg>
                    <span class="project-badge">Residencial</span>
                </div>
                <div class="project-body">
                    <p class="project-type">Vivienda unifamiliar · 2023</p>
                    <h3 class="project-name">Chalet Urbanización Las Encinas</h3>
                    <p class="project-desc">Proyecto completo de vivienda unifamiliar con sótano, planta baja y planta primera. Incluye detalle de carpinterías.</p>
                    <div class="project-meta">
                        <div class="project-meta-item">Superficie<strong>320 m²</strong></div>
                        <div class="project-meta-item">Software<strong>ArchiCAD</strong></div>
                        <div class="project-meta-item">Plazo<strong>3 semanas</strong></div>
                    </div>
                </div>
            </div>

            <div class="project-card reveal reveal-delay-2">
                <div class="project-thumb">
                    <div class="project-thumb-grid"></div>
                    <svg class="project-thumb-shape thumb-floor-plan" viewBox="0 0 140 120"><rect x="10" y="10" width="120" height="100" rx="2"/><line x1="10" y1="40" x2="130" y2="40"/><line x1="10" y1="80" x2="130" y2="80"/><line x1="45" y1="10" x2="45" y2="110"/><line x1="95" y1="10" x2="95" y2="110"/><circle cx="27" cy="25" r="8" stroke-dasharray="2,2"/><circle cx="70" cy="60" r="10" stroke-dasharray="2,2"/><rect x="100" y="85" width="25" height="18" rx="1"/></svg>
                    <span class="project-badge">Comercial</span>
                </div>
                <div class="project-body">
                    <p class="project-type">Local comercial · 2022</p>
                    <h3 class="project-name">Reforma Centro Comercial Plaza</h3>
                    <p class="project-desc">As-built y proyecto de reforma para locales de 800 m², con redistribución completa e instalaciones nuevas.</p>
                    <div class="project-meta">
                        <div class="project-meta-item">Superficie<strong>800 m²</strong></div>
                        <div class="project-meta-item">Software<strong>AutoCAD</strong></div>
                        <div class="project-meta-item">Plazo<strong>2 semanas</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACTO -->
<section id="landing-contacto">
    <div class="contact-inner" style="padding: 7rem 2rem">
        <div>
            <p class="landing-eyebrow reveal">Hablemos</p>
            <h2 class="landing-title reveal">¿Tienes un<br>proyecto?</h2>
            <p class="landing-subtitle reveal">Cuéntanos en qué consiste y te responderemos con un presupuesto en menos de 24 horas.</p>

            <div class="contact-info reveal">
                <div class="contact-item">
                    <div class="contact-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <div><p class="contact-item-label">Email</p><p class="contact-item-value">info@proyinstal.com</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg></div>
                    <div><p class="contact-item-label">Teléfono</p><p class="contact-item-value">+34 900 000 000</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <div><p class="contact-item-label">Ubicación</p><p class="contact-item-value">Madrid, España</p></div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
                    <div><p class="contact-item-label">Horario</p><p class="contact-item-value">Lun–Vie, 8:00–18:00</p></div>
                </div>
            </div>
        </div>

        <div class="contact-form-card reveal">
            <form action="#" method="POST">
                @csrf
                <div class="landing-form-row">
                    <div class="landing-form-field">
                        <label class="form-label" for="c_nombre">Nombre</label>
                        <input class="form-input" id="c_nombre" name="nombre" type="text" placeholder="Juan García" required>
                    </div>
                    <div class="landing-form-field">
                        <label class="form-label" for="c_email">Email</label>
                        <input class="form-input" id="c_email" name="email" type="email" placeholder="tu@email.com" required>
                    </div>
                </div>
                <div class="landing-form-row">
                    <div class="landing-form-field">
                        <label class="form-label" for="c_tel">Teléfono</label>
                        <input class="form-input" id="c_tel" name="telefono" type="tel" placeholder="+34 600 000 000">
                    </div>
                    <div class="landing-form-field">
                        <label class="form-label" for="c_tipo">Tipo de proyecto</label>
                        <select class="form-input" id="c_tipo" name="tipo">
                            <option value="">Selecciona...</option>
                            <option>Residencial</option>
                            <option>Comercial</option>
                            <option>Industrial</option>
                            <option>Reforma</option>
                            <option>Instalaciones</option>
                            <option>Urbanismo</option>
                        </select>
                    </div>
                </div>
                <div class="landing-form-row">
                    <div class="landing-form-field full">
                        <label class="form-label" for="c_msg">Descripción del proyecto</label>
                        <textarea class="form-input" id="c_msg" name="mensaje" rows="4" placeholder="Cuéntanos en qué consiste tu proyecto, superficie aproximada, plazo y cualquier detalle relevante..." required></textarea>
                    </div>
                </div>
                <div class="landing-form-submit">
                    <button type="submit" class="btn-landing-submit">
                        Enviar consulta
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="landing-footer">
    <div class="landing-footer-inner">
        <a href="#landing-hero" class="landing-footer-logo">PROYINSTAL</a>
        <ul class="landing-footer-links">
            <li><a href="#landing-servicios">Servicios</a></li>
            <li><a href="#landing-experiencia">Experiencia</a></li>
            <li><a href="#landing-proyectos">Proyectos</a></li>
            <li><a href="#landing-contacto">Contacto</a></li>
            @if(Route::has('login'))
                @auth
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}">Acceder</a></li>
                @endauth
            @endif
        </ul>
        <p class="landing-footer-copy">© {{ date('Y') }} PROYINSTAL · Todos los derechos reservados</p>
    </div>
</footer>

<script>
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40), { passive: true });

const burger = document.getElementById('hamburger');
const mobileNav = document.getElementById('mobileNav');
burger.addEventListener('click', () => mobileNav.classList.toggle('open'));
function closeMobile() { mobileNav.classList.remove('open'); }

const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>