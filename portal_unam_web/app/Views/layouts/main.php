<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal de Egresados – UNAM') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/portal.css') ?>">
    <script>window.APP_BASE_URL = '<?= rtrim(base_url(), '/') ?>';</script>
</head>
<body>

<!-- ══════════════ SIDEBAR ══════════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
        <img src="img/logo.png" alt="Logo UNAM" class="brand-icon-img">
        </div>
        <div class="brand-text">
            <span class="brand-name">UNAM</span>
            <span class="brand-sub">Portal Egresados</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= base_url('/') ?>" class="nav-link <?= uri_string() === '' || uri_string() === 'dashboard' ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Dashboard</span>
        </a>
        <a href="<?= base_url('egresados') ?>" class="nav-link <?= strpos(uri_string(), 'egresados') === 0 ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Egresados</span>
        </a>
        <a href="<?= base_url('empleadores') ?>" class="nav-link <?= strpos(uri_string(), 'empleadores') === 0 ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
            <span>Empleadores</span>
        </a>
        <a href="<?= base_url('ofertas') ?>" class="nav-link <?= strpos(uri_string(), 'ofertas') === 0 ? 'active' : '' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            <span>Bolsa de Trabajo</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sede-badge">
            <span class="dot"></span>
            Moquegua · Ilo
        </div>
    </div>
</aside>

<!-- ══════════════ MAIN ══════════════ -->
<div class="main-wrapper">

    <!-- Topbar -->
    <header class="topbar">
        <button class="menu-toggle" id="menuToggle" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </button>
        <div class="topbar-title"><?= esc($title ?? 'Portal de Egresados') ?></div>
        <div class="topbar-right">
            <span class="topbar-badge">UNAM Moquegua</span>
        </div>
    </header>

    <!-- Content -->
    <main class="page-content">
        <?= $content ?>
    </main>

    <footer class="page-footer">
        <p>© <?= date('Y') ?> Universidad Nacional de Moquegua · Oficina de Seguimiento al Egresado</p>
    </footer>
</div>


<script src="<?= base_url('js/portal.js') ?>"></script>
</body>
</html>
