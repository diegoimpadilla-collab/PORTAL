<?php
$totalPages = $per_page > 0 ? ceil($total / $per_page) : 1;
$baseUrl    = base_url('ofertas?') . http_build_query(array_filter([
    'q'        => $filtros['busqueda'],
    'escuela'  => $filtros['escuela_id'],
    'modalidad'=> $filtros['modalidad'],
])) . '&page=';

$modalidades = ['Tiempo completo','Tiempo parcial','Por proyecto','Régimen 14x7','Régimen 20x10','Remoto','Híbrido'];
?>

<div class="section-header">
    <h1 class="section-title">Bolsa de Trabajo</h1>
    <p class="section-sub"><?= number_format($total) ?> ofertas activas para egresados UNAM</p>
</div>

<form method="GET" action="<?= base_url('ofertas') ?>" class="filter-bar">
    <div class="filter-row">
        <div class="filter-group" style="flex:2;min-width:220px">
            <label>Búsqueda</label>
            <input type="text" name="q" value="<?= esc($filtros['busqueda']) ?>" placeholder="Puesto, empresa, ciudad…">
        </div>
        <div class="filter-group">
            <label>Escuela</label>
            <select name="escuela">
                <option value="">Todas</option>
                <?php foreach ($escuelas as $esc): ?>
                <option value="<?= $esc['id'] ?>" <?= $filtros['escuela_id'] == $esc['id'] ? 'selected' : '' ?>>
                    <?= esc($esc['codigo']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Modalidad</label>
            <select name="modalidad">
                <option value="">Todas</option>
                <?php foreach ($modalidades as $m): ?>
                <option value="<?= $m ?>" <?= $filtros['modalidad'] === $m ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group" style="flex:0;min-width:auto">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
        <div class="filter-group" style="flex:0;min-width:auto">
            <label>&nbsp;</label>
            <a href="<?= base_url('ofertas') ?>" class="btn btn-outline">Limpiar</a>
        </div>
    </div>
</form>

<?php if (empty($ofertas)): ?>
<div class="empty-state">
    <div class="empty-icon">💼</div>
    <p>No se encontraron ofertas con los filtros seleccionados.</p>
</div>
<?php else: ?>
<div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px">
<?php foreach ($ofertas as $o): ?>
    <a href="<?= base_url('ofertas/'.$o['id']) ?>" class="oferta-card" style="flex-direction:row;align-items:flex-start;gap:16px">
        <div style="width:44px;height:44px;background:var(--c-light);border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;color:var(--c-mid);font-size:.9rem;flex-shrink:0">
            <?= mb_substr($o['empresa'],0,2) ?>
        </div>
        <div style="flex:1;min-width:0">
            <div class="oferta-title"><?= esc($o['titulo']) ?></div>
            <div class="oferta-empresa"><?= esc($o['empresa']) ?> · <?= esc($o['ubicacion']) ?></div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-top:6px">
                <?php if ($o['salario_min']): ?>
                <span class="oferta-salary">S/ <?= number_format($o['salario_min'],0) ?> – <?= number_format($o['salario_max'],0) ?></span>
                <?php endif; ?>
                <span class="badge badge-blue"><?= esc($o['modalidad']) ?></span>
                <?php if ($o['escuela_codigo']): ?>
                <span class="badge badge-gold"><?= esc($o['escuela_codigo']) ?></span>
                <?php endif; ?>
                <span class="badge badge-gray"><?= $o['vacantes'] ?> vacante(s)</span>
            </div>
        </div>
        <div style="flex-shrink:0;text-align:right">
            <div style="font-size:.7rem;color:var(--c-muted);font-family:var(--font-mono)">
                <?= date('d/m/Y', strtotime($o['fecha_pub'])) ?>
            </div>
            <?php if ($o['fecha_cierre']): ?>
            <div style="font-size:.68rem;color:var(--c-rose);margin-top:2px">
                Cierra <?= date('d/m/Y', strtotime($o['fecha_cierre'])) ?>
            </div>
            <?php endif; ?>
        </div>
    </a>
<?php endforeach; ?>
</div>

<!-- Paginación -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="<?= $baseUrl.($page-1) ?>" class="page-btn">‹</a>
    <?php endif; ?>
    <?php for ($i = max(1,$page-2); $i <= min($totalPages,$page+2); $i++): ?>
        <a href="<?= $baseUrl.$i ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl.($page+1) ?>" class="page-btn">›</a>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
