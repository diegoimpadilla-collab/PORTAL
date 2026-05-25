<?php
$totalPages = $per_page > 0 ? ceil($total / $per_page) : 1;
$baseUrl    = base_url('egresados/buscar?') . http_build_query(array_filter([
    'q'         => $filtros['busqueda'],
    'escuela'   => $filtros['escuela_id'],
    'sede'      => $filtros['sede'],
    'sexo'      => $filtros['sexo'],
    'bachiller' => $filtros['es_bachiller'],
    'titulado'  => $filtros['es_titulado'],
    'anio'      => $filtros['anio_egreso'],
])) . '&page=';
?>

<div class="section-header">
    <h1 class="section-title">Directorio de Egresados</h1>
    <p class="section-sub"><?= number_format($total) ?> registros encontrados</p>
</div>

<!-- Filtros -->
<form method="GET" action="<?= base_url('egresados/buscar') ?>" class="filter-bar">
    <div class="filter-row">
        <div class="filter-group" style="min-width:220px;flex:2">
            <label>Búsqueda</label>
            <input type="text" name="q" value="<?= esc($filtros['busqueda']) ?>" placeholder="Nombre, DNI o código…">
        </div>
        <div class="filter-group">
            <label>Escuela</label>
            <select name="escuela">
                <option value="">Todas</option>
                <?php foreach ($escuelas as $esc): ?>
                <option value="<?= $esc['id'] ?>" <?= $filtros['escuela_id'] == $esc['id'] ? 'selected' : '' ?>>
                    <?= esc($esc['codigo']) ?> – <?= esc($esc['nombre']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Sede</label>
            <select name="sede">
                <option value="">Todas</option>
                <option value="Moquegua" <?= $filtros['sede'] === 'Moquegua' ? 'selected' : '' ?>>Moquegua</option>
                <option value="Ilo"      <?= $filtros['sede'] === 'Ilo'      ? 'selected' : '' ?>>Ilo</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Sexo</label>
            <select name="sexo">
                <option value="">Todos</option>
                <option value="M" <?= $filtros['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= $filtros['sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Bachiller</label>
            <select name="bachiller">
                <option value="">Todos</option>
                <option value="1" <?= $filtros['es_bachiller'] === '1' ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= $filtros['es_bachiller'] === '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Titulado</label>
            <select name="titulado">
                <option value="">Todos</option>
                <option value="1" <?= $filtros['es_titulado'] === '1' ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= $filtros['es_titulado'] === '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Año Egreso</label>
            <select name="anio">
                <option value="">Todos</option>
                <?php foreach ($anios as $a): ?>
                <option value="<?= $a ?>" <?= $filtros['anio_egreso'] == $a ? 'selected' : '' ?>><?= $a ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group" style="min-width:auto;flex:0">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
        <div class="filter-group" style="min-width:auto;flex:0">
            <label>&nbsp;</label>
            <a href="<?= base_url('egresados') ?>" class="btn btn-outline">Limpiar</a>
        </div>
    </div>
</form>

<!-- Tabla -->
<div class="table-card">
    <div class="table-header">
        <span class="table-title">Resultados</span>
        <span class="badge badge-gray"><?= number_format($total) ?> egresados</span>
    </div>
    <div class="table-responsive">
    <?php if (empty($egresados)): ?>
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
            <p>No se encontraron egresados con los filtros seleccionados.</p>
        </div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>DNI</th>
                <th>Escuela</th>
                <th>Sede</th>
                <th>Sexo</th>
                <th>Año Egreso</th>
                <th>Bachiller</th>
                <th>Titulado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($egresados as $eg): ?>
            <tr>
                <td style="font-weight:600;max-width:200px"><?= esc($eg['nombre_completo']) ?></td>
                <td><code style="font-family:var(--font-mono);font-size:.75rem"><?= esc($eg['codigo_estudiante']) ?></code></td>
                <td style="font-family:var(--font-mono);font-size:.78rem"><?= esc($eg['dni']) ?></td>
                <td>
                    <span class="badge badge-blue"><?= esc($eg['escuela_codigo']) ?></span>
                </td>
                <td><?= esc($eg['sede']) ?></td>
                <td>
                    <?php if ($eg['sexo'] === 'M'): ?>
                        <span class="badge badge-blue">♂ M</span>
                    <?php else: ?>
                        <span class="badge badge-rose">♀ F</span>
                    <?php endif; ?>
                </td>
                <td style="font-family:var(--font-mono)"><?= esc($eg['anio_egreso'] ?? '—') ?></td>
                <td>
                    <?php if ($eg['es_bachiller']): ?>
                        <span class="badge badge-gold">✓ Sí</span>
                    <?php else: ?>
                        <span class="badge badge-gray">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($eg['es_titulado']): ?>
                        <span class="badge badge-teal">✓ Sí</span>
                    <?php else: ?>
                        <span class="badge badge-gray">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('egresados/'.$eg['id']) ?>" class="btn btn-outline btn-sm">Ver</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
    </div>
</div>

<!-- Paginación -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="<?= $baseUrl . ($page-1) ?>" class="page-btn">‹</a>
    <?php endif; ?>

    <?php
    $start = max(1, $page - 2);
    $end   = min($totalPages, $page + 2);
    if ($start > 1) echo '<span class="page-btn" style="border:none;background:none">…</span>';
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="<?= $baseUrl . $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor;
    if ($end < $totalPages) echo '<span class="page-btn" style="border:none;background:none">…</span>';
    ?>

    <?php if ($page < $totalPages): ?>
        <a href="<?= $baseUrl . ($page+1) ?>" class="page-btn">›</a>
    <?php endif; ?>
</div>
<?php endif; ?>
