<?php $e = $empleador; ?>

<div style="margin-bottom:16px">
    <a href="<?= base_url('empleadores') ?>" class="btn btn-outline btn-sm">← Empleadores</a>
</div>

<div class="detail-header">
    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;color:#fff">
            <?= mb_substr($e['razon_social'],0,2) ?>
        </div>
        <div>
            <div class="detail-name"><?= esc($e['razon_social']) ?></div>
            <div class="detail-code">RUC <?= esc($e['ruc']) ?> · <?= esc($e['sector']) ?> · <?= esc($e['ciudad']) ?></div>
        </div>
        <?php if ($e['verificado']): ?>
        <span class="badge badge-teal" style="margin-left:auto">✓ Verificado</span>
        <?php endif; ?>
    </div>
</div>

<div class="detail-grid">

    <div class="info-card">
        <h3>📋 Información General</h3>
        <div class="info-row"><span class="info-label">Sector</span><span class="info-value"><?= esc($e['sector']) ?></span></div>
        <div class="info-row"><span class="info-label">Ciudad</span><span class="info-value"><?= esc($e['ciudad']) ?></span></div>
        <?php if ($e['direccion']): ?>
        <div class="info-row"><span class="info-label">Dirección</span><span class="info-value" style="font-size:.76rem"><?= esc($e['direccion']) ?></span></div>
        <?php endif; ?>
        <?php if ($e['web']): ?>
        <div class="info-row"><span class="info-label">Web</span><span class="info-value"><a href="http://<?= esc($e['web']) ?>" target="_blank" style="color:var(--c-sky)"><?= esc($e['web']) ?></a></span></div>
        <?php endif; ?>
    </div>

    <div class="info-card">
        <h3>👤 Representante</h3>
        <div class="info-row"><span class="info-label">Nombre</span><span class="info-value"><?= esc($e['representante'] ?? '—') ?></span></div>
        <div class="info-row"><span class="info-label">Cargo</span><span class="info-value"><?= esc($e['cargo_rep'] ?? '—') ?></span></div>
        <div class="info-row"><span class="info-label">Teléfono</span><span class="info-value" style="font-family:var(--font-mono)"><?= esc($e['telefono'] ?? '—') ?></span></div>
        <div class="info-row"><span class="info-label">Email</span><span class="info-value" style="font-size:.76rem"><?= esc($e['email'] ?? '—') ?></span></div>
    </div>

    <?php if (!empty($e['escuelas'])): ?>
    <div class="info-card">
        <h3>🎓 Escuelas que Contrata</h3>
        <?php foreach ($e['escuelas'] as $esc): ?>
        <div class="info-row">
            <span class="info-label"><span class="badge badge-blue"><?= esc($esc['codigo']) ?></span> <?= esc($esc['nombre']) ?></span>
            <span class="info-value" style="font-family:var(--font-mono);color:var(--c-gold)"><?= $esc['egresados_contratados'] ?> egr.</span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<?php if (!empty($e['ofertas'])): ?>
<div class="table-card" style="margin-top:8px">
    <div class="table-header">
        <span class="table-title">💼 Ofertas Activas</span>
        <span class="badge badge-green"><?= count($e['ofertas']) ?> activas</span>
    </div>
    <div style="padding:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:10px">
        <?php foreach ($e['ofertas'] as $o): ?>
        <a href="<?= base_url('ofertas/'.$o['id']) ?>" class="oferta-card">
            <div class="oferta-title"><?= esc($o['titulo']) ?></div>
            <div class="oferta-empresa"><?= esc($o['ubicacion']) ?></div>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
                <?php if ($o['salario_min']): ?>
                <span class="oferta-salary">S/ <?= number_format($o['salario_min'],0) ?></span>
                <?php endif; ?>
                <span class="badge badge-blue"><?= esc($o['modalidad']) ?></span>
                <span class="badge badge-gray"><?= $o['vacantes'] ?> vacante(s)</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
