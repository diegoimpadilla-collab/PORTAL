<?php
// ── helpers ──────────────────────────────────────────────────
$total_egresados   = $total_egresados   ?? 0;
$total_bachilleres = $total_bachilleres ?? 0;
$total_titulados   = $total_titulados   ?? 0;
$total_ofertas     = $total_ofertas     ?? 0;
$pct_bach = $total_egresados > 0 ? round($total_bachilleres / $total_egresados * 100) : 0;
$pct_tit  = $total_egresados > 0 ? round($total_titulados   / $total_egresados * 100) : 0;
?>

<!-- ── KPI Cards ──────────────────────────────────────────── -->
<div class="section-header">
    <h1 class="section-title">Dashboard de Seguimiento</h1>
    <p class="section-sub">Indicadores en tiempo real · Base de datos actualizada</p>
</div>

<div class="kpi-grid">
    <div class="kpi-card blue">
        <div class="kpi-icon">👥</div>
        <div class="kpi-value" data-target="<?= $total_egresados ?>">0</div>
        <div class="kpi-label">Total Egresados</div>
    </div>
    <div class="kpi-card gold">
        <div class="kpi-icon">🎓</div>
        <div class="kpi-value" data-target="<?= $total_bachilleres ?>">0</div>
        <div class="kpi-label">Bachilleres (<?= $pct_bach ?>%)</div>
    </div>
    <div class="kpi-card teal">
        <div class="kpi-icon">📜</div>
        <div class="kpi-value" data-target="<?= $total_titulados ?>">0</div>
        <div class="kpi-label">Titulados (<?= $pct_tit ?>%)</div>
    </div>
    <div class="kpi-card sky">
        <div class="kpi-icon">💼</div>
        <div class="kpi-value" data-target="<?= $total_ofertas ?>">0</div>
        <div class="kpi-label">Ofertas Activas</div>
    </div>
</div>

<!-- ── Gráficos fila 1 ────────────────────────────────────── -->
<div class="charts-grid">
    <div class="chart-card full">
        <div class="chart-title">
            <span class="dot-color" style="background:#2456a4"></span>
            Egresados, Bachilleres y Titulados por Escuela Profesional
        </div>
        <div class="chart-wrap" style="height:280px">
            <canvas id="chartEscuela"></canvas>
        </div>
    </div>
</div>

<!-- ── Gráficos fila 2 ────────────────────────────────────── -->
<div class="charts-grid">
    <div class="chart-card" style="grid-column: span 2">
        <div class="chart-title">
            <span class="dot-color" style="background:#3b82f6"></span>
            Evolución por Año de Egreso
        </div>
        <div class="chart-wrap" style="height:220px">
            <canvas id="chartAnio"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-title">
            <span class="dot-color" style="background:#e8a020"></span>
            Distribución por Sede
        </div>
        <div class="chart-wrap" style="height:220px">
            <canvas id="chartSede"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <div class="chart-title">
            <span class="dot-color" style="background:#3b82f6"></span>
            Distribución por Sexo
        </div>
        <div class="chart-wrap" style="height:220px">
            <canvas id="chartSexo"></canvas>
        </div>
    </div>
</div>

<!-- ── Gráfico titulados apilado ──────────────────────────── -->
<div class="charts-grid">
    <div class="chart-card full">
        <div class="chart-title">
            <span class="dot-color" style="background:#0d9488"></span>
            Titulados y Bachilleres por Escuela (Apilado)
        </div>
        <div class="chart-wrap" style="height:240px">
            <canvas id="chartTitulados"></canvas>
        </div>
    </div>
</div>

<!-- ── Fila inferior: top empleadores + ofertas recientes ─── -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:4px">

    <!-- Top empleadores -->
    <div class="table-card">
        <div class="table-header">
            <span class="table-title">🏆 Top Empleadores</span>
            <a href="<?= base_url('empleadores') ?>" class="btn btn-outline btn-sm">Ver todos</a>
        </div>
        <?php foreach ($top_empleadores as $i => $emp): ?>
        <div class="empleador-item">
            <div class="empleador-rank"><?= $i + 1 ?></div>
            <div class="empleador-info">
                <div class="empleador-name"><?= esc($emp['razon_social']) ?></div>
                <div class="empleador-sector"><?= esc($emp['sector']) ?> · <?= esc($emp['ciudad']) ?></div>
                <div class="progress-bar" style="margin-top:5px">
                    <div class="progress-fill" style="width:<?= min(100, round($emp['total'] / max(array_column($top_empleadores, 'total')) * 100)) ?>%"></div>
                </div>
            </div>
            <div class="empleador-count"><?= number_format($emp['total']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Ofertas recientes -->
    <div class="table-card">
        <div class="table-header">
            <span class="table-title">💼 Ofertas Recientes</span>
            <a href="<?= base_url('ofertas') ?>" class="btn btn-outline btn-sm">Ver todas</a>
        </div>
        <div style="padding:12px;display:flex;flex-direction:column;gap:10px">
        <?php foreach ($ofertas_recientes as $o): ?>
            <a href="<?= base_url('ofertas/'.$o['id']) ?>" class="oferta-card">
                <div class="oferta-title"><?= esc($o['titulo']) ?></div>
                <div class="oferta-empresa"><?= esc($o['empresa']) ?> · <?= esc($o['ubicacion']) ?></div>
                <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                    <?php if ($o['salario_min']): ?>
                    <span class="oferta-salary">S/ <?= number_format($o['salario_min'],0) ?> – <?= number_format($o['salario_max'],0) ?></span>
                    <?php endif; ?>
                    <span class="badge badge-blue"><?= esc($o['modalidad']) ?></span>
                </div>
            </a>
        <?php endforeach; ?>
        </div>
    </div>

</div>
