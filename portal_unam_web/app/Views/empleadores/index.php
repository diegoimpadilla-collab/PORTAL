<div class="section-header">
    <h1 class="section-title">Empleadores Aliados</h1>
    <p class="section-sub"><?= $total ?> empresas e instituciones registradas</p>
</div>

<div class="cards-grid">
<?php foreach ($empleadores as $emp): ?>
    <a href="<?= base_url('empleadores/'.$emp['id']) ?>" class="card">
        <div class="card-logo">
            <?= mb_substr($emp['razon_social'], 0, 2) ?>
        </div>
        <div class="card-title"><?= esc($emp['razon_social']) ?></div>
        <div class="card-sub"><?= esc($emp['sector']) ?> · <?= esc($emp['ciudad']) ?></div>
        <?php if ($emp['representante']): ?>
        <div class="card-sub" style="font-size:.72rem">👤 <?= esc($emp['representante']) ?></div>
        <?php endif; ?>
        <div class="card-meta">
            <?php if ($emp['verificado']): ?>
                <span class="badge badge-teal">✓ Verificado</span>
            <?php endif; ?>
            <span class="badge badge-blue"><?= esc($emp['sector']) ?></span>
        </div>
    </a>
<?php endforeach; ?>
</div>
