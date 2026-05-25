<?php $e = $egresado; ?>

<div style="margin-bottom:16px">
    <a href="<?= base_url('egresados') ?>" class="btn btn-outline btn-sm">← Volver</a>
</div>

<!-- Header -->
<div class="detail-header" style="margin-bottom:24px">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.8rem">
            <?= $e['sexo'] === 'M' ? '👨‍🎓' : '👩‍🎓' ?>
        </div>
        <div>
            <div class="detail-name"><?= esc($e['nombre_completo']) ?></div>
            <div class="detail-code"><?= esc($e['codigo_estudiante']) ?> · DNI <?= esc($e['dni']) ?></div>
        </div>
        <div style="margin-left:auto;display:flex;gap:8px;flex-wrap:wrap">
            <?php if ($e['es_bachiller']): ?>
                <span class="badge badge-gold" style="font-size:.75rem">🎓 Bachiller</span>
            <?php endif; ?>
            <?php if ($e['es_titulado']): ?>
                <span class="badge badge-teal" style="font-size:.75rem">📜 Titulado</span>
            <?php endif; ?>
            <span class="badge" style="background:rgba(255,255,255,.15);color:#fff;font-size:.75rem"><?= esc($e['sede']) ?></span>
        </div>
    </div>
</div>

<!-- Info grid -->
<div class="detail-grid">

    <!-- Datos académicos -->
    <div class="info-card">
        <h3>🏫 Datos Académicos</h3>
        <div class="info-row">
            <span class="info-label">Escuela</span>
            <span class="info-value"><?= esc($e['escuela_nombre']) ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Código</span>
            <span class="info-value"><span class="badge badge-blue"><?= esc($e['escuela_codigo']) ?></span></span>
        </div>
        <div class="info-row">
            <span class="info-label">Facultad</span>
            <span class="info-value"><?= esc($e['facultad'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Año de Ingreso</span>
            <span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['anio_ingreso'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Año de Egreso</span>
            <span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['anio_egreso'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Semestre</span>
            <span class="info-value"><?= esc($e['semestre_egreso'] ?? '—') ?></span>
        </div>
        <?php if ($e['anios_estudiados']): ?>
        <div class="info-row">
            <span class="info-label">Años Estudiados</span>
            <span class="info-value"><?= $e['anios_estudiados'] ?> años</span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Titulaciones -->
    <div class="info-card">
        <h3>🎓 Titulaciones</h3>
        <div class="info-row">
            <span class="info-label">Es Bachiller</span>
            <span class="info-value">
                <?= $e['es_bachiller'] ? '<span class="badge badge-gold">✓ Sí</span>' : '<span class="badge badge-gray">No</span>' ?>
            </span>
        </div>
        <?php if ($e['es_bachiller']): ?>
        <div class="info-row">
            <span class="info-label">Año Bachiller</span>
            <span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['anio_bachiller'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Diploma Bachiller</span>
            <span class="info-value"><?= $e['fecha_diploma_bachiller'] ? date('d/m/Y', strtotime($e['fecha_diploma_bachiller'])) : '—' ?></span>
        </div>
        <?php endif; ?>
        <div class="info-row" style="margin-top:8px">
            <span class="info-label">Es Titulado</span>
            <span class="info-value">
                <?= $e['es_titulado'] ? '<span class="badge badge-teal">✓ Sí</span>' : '<span class="badge badge-gray">No</span>' ?>
            </span>
        </div>
        <?php if ($e['es_titulado']): ?>
        <div class="info-row">
            <span class="info-label">Año Titulación</span>
            <span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['anio_titulacion'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Diploma Título</span>
            <span class="info-value"><?= $e['fecha_diploma_titulo'] ? date('d/m/Y', strtotime($e['fecha_diploma_titulo'])) : '—' ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Contacto -->
    <div class="info-card">
        <h3>📞 Contacto</h3>
        <div class="info-row">
            <span class="info-label">Correo Inst.</span>
            <span class="info-value" style="font-size:.76rem"><?= esc($e['correo_institucional'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Email Personal</span>
            <span class="info-value" style="font-size:.76rem"><?= esc($e['email_personal'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Teléfono</span>
            <span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['telefono'] ?? '—') ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Sexo</span>
            <span class="info-value"><?= $e['sexo'] === 'M' ? '♂ Masculino' : '♀ Femenino' ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Sede</span>
            <span class="info-value"><?= esc($e['sede']) ?></span>
        </div>
    </div>

</div>

<div style="margin-top:8px">
    <a href="<?= base_url('egresados') ?>" class="btn btn-outline">← Regresar al listado</a>
</div>
