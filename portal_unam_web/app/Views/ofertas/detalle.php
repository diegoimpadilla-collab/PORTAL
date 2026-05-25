<?php $o = $oferta; ?>

<div style="margin-bottom:16px">
    <a href="<?= base_url('ofertas') ?>" class="btn btn-outline btn-sm">← Bolsa de Trabajo</a>
</div>

<div class="detail-header">
    <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap">
        <div style="width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#fff;flex-shrink:0">
            <?= mb_substr($o['empresa'],0,2) ?>
        </div>
        <div style="flex:1">
            <div class="detail-name"><?= esc($o['titulo']) ?></div>
            <div class="detail-code"><?= esc($o['empresa']) ?> · <?= esc($o['ubicacion']) ?></div>
        </div>
        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
            <span class="badge badge-green">✓ Activa</span>
            <?php if ($o['fecha_cierre']): ?>
            <span style="font-size:.72rem;color:rgba(255,255,255,.6)">Cierra <?= date('d/m/Y',strtotime($o['fecha_cierre'])) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="detail-grid">

    <div class="info-card">
        <h3>📋 Detalles</h3>
        <div class="info-row"><span class="info-label">Empresa</span><span class="info-value"><?= esc($o['empresa']) ?></span></div>
        <div class="info-row"><span class="info-label">Sector</span><span class="info-value"><?= esc($o['sector'] ?? '—') ?></span></div>
        <div class="info-row"><span class="info-label">Ubicación</span><span class="info-value"><?= esc($o['ubicacion']) ?></span></div>
        <div class="info-row"><span class="info-label">Modalidad</span>
            <span class="info-value"><span class="badge badge-blue"><?= esc($o['modalidad']) ?></span></span>
        </div>
        <div class="info-row"><span class="info-label">Vacantes</span><span class="info-value"><?= $o['vacantes'] ?></span></div>
        <?php if ($o['escuela_nombre']): ?>
        <div class="info-row"><span class="info-label">Perfil</span>
            <span class="info-value"><span class="badge badge-gold"><?= esc($o['escuela_codigo']) ?></span> <?= esc($o['escuela_nombre']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($o['salario_min']): ?>
        <div class="info-row">
            <span class="info-label">Salario</span>
            <span class="info-value" style="color:var(--c-teal);font-family:var(--font-mono);font-weight:700">
                S/ <?= number_format($o['salario_min'],0) ?> – S/ <?= number_format($o['salario_max'],0) ?>
            </span>
        </div>
        <?php endif; ?>
        <div class="info-row"><span class="info-label">Publicado</span><span class="info-value"><?= date('d/m/Y',strtotime($o['fecha_pub'])) ?></span></div>
    </div>

    <?php if ($o['descripcion']): ?>
    <div class="info-card" style="grid-column:span 2">
        <h3>📝 Descripción del Puesto</h3>
        <p style="font-size:.82rem;line-height:1.65;color:var(--c-text);margin-top:8px"><?= nl2br(esc($o['descripcion'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($o['requisitos']): ?>
    <div class="info-card">
        <h3>✅ Requisitos</h3>
        <p style="font-size:.82rem;line-height:1.65;color:var(--c-text);margin-top:8px"><?= nl2br(esc($o['requisitos'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($o['beneficios']): ?>
    <div class="info-card">
        <h3>🎁 Beneficios</h3>
        <p style="font-size:.82rem;line-height:1.65;color:var(--c-text);margin-top:8px"><?= nl2br(esc($o['beneficios'])) ?></p>
    </div>
    <?php endif; ?>

</div>

<div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
    <a href="mailto:<?= esc($o['ciudad_empresa'] ?? '') ?>" class="btn btn-gold">Postular</a>
    <a href="<?= base_url('empleadores') ?>" class="btn btn-outline">Ver empleador</a>
    <a href="<?= base_url('ofertas') ?>" class="btn btn-outline">← Todas las ofertas</a>
</div>
