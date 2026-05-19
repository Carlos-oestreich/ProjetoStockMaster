<?php
/**
 * Template cabeçalho com sidebar lateral
 * Variáveis esperadas:
 * @var string $paginaAtiva  — slug da página ativa (ex: 'produtos', 'dashboard')
 * @var string $tituloPagina — título exibido no header
 */

$usuario   = $_SESSION['usuario'] ?? [];
$nomeUsuario = htmlspecialchars($usuario['nome'] ?? 'Usuario');
$perfil    = $usuario['perfil'] ?? 'OPERADOR';
$isAdmin   = in_array($perfil, ['ADM', 'DONO'], true);
$iniciais  = strtoupper(substr($nomeUsuario, 0, 1));
$empresaNome = htmlspecialchars($usuario['empresaNome'] ?? 'StockMaster');
$empresaLogo = $usuario['empresaLogo'] ?? null;
$empresaLogoUrl = null;
if (!empty($empresaLogo)) {
    $empresaLogoUrl = preg_match('/^https?:\\/\\//', $empresaLogo)
        ? $empresaLogo
        : (BASE_URL . $empresaLogo);
}
$paginaAtiva = $paginaAtiva ?? '';
$tituloPagina = $tituloPagina ?? 'StockMaster';

// Itens de navegação
$navItems = [
    ['path' => '/dashboard',      'icon' => 'bi-speedometer2',    'label' => 'Dashboard',       'admin' => false],
    ['path' => '/produtos',       'icon' => 'bi-box-seam',        'label' => 'Produtos',         'admin' => false],
    ['path' => '/movimentacoes',  'icon' => 'bi-arrow-left-right','label' => 'Movimentações',    'admin' => false],
    ['path' => '/alertas',        'icon' => 'bi-bell',            'label' => 'Alertas',          'admin' => false],
    ['path' => '/categorias',     'icon' => 'bi-tags',            'label' => 'Categorias',       'admin' => true],
    ['path' => '/fornecedores',   'icon' => 'bi-truck',           'label' => 'Fornecedores',     'admin' => true],
    ['path' => '/relatorios',     'icon' => 'bi-bar-chart-line',  'label' => 'Relatórios',       'admin' => true],
    ['path' => '/usuarios',       'icon' => 'bi-people',          'label' => 'Usuários',         'admin' => true],
    ['path' => '/configuracoes',  'icon' => 'bi-gear',            'label' => 'Configuracoes',    'admin' => false],
];
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $tituloPagina ?> – StockMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body class="d-flex" style="min-height:100vh; background:#0f172a;">

<!-- ===================== SIDEBAR ===================== -->
<aside id="sidebar" class="d-flex flex-column flex-shrink-0"
       style="width:256px; background:#1e293b; border-right:1px solid #334155; height:100vh; position:sticky; top:0; overflow-y:auto;">

    <!-- Logo -->
    <div class="d-flex align-items-center gap-2 px-4 py-3 border-bottom border-secondary"
         style="min-height:64px;">
        <?php if ($empresaLogoUrl): ?>
            <img src="<?= $empresaLogoUrl ?>" alt="Logo" style="height:32px; width:auto; max-width:120px; object-fit:contain;">
        <?php else: ?>
            <i class="bi bi-boxes text-primary fs-4"></i>
        <?php endif; ?>
        <span class="fw-bold fs-6 text-white text-truncate" style="max-width:140px;">
            <?= $empresaNome ?>
        </span>
    </div>

    <!-- Nav -->
    <nav class="flex-grow-1 px-2 py-3">
        <?php foreach ($navItems as $item): ?>
            <?php if ($item['admin'] && !$isAdmin) continue; ?>
            <?php
            $slug = ltrim($item['path'], '/');
            $ativo = ($paginaAtiva === $slug);
            ?>
            <a href="<?= BASE_URL . $item['path'] ?>"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 mb-1 rounded-3 fw-medium
                      <?= $ativo ? 'bg-primary text-white' : 'text-secondary' ?>"
               style="<?= $ativo ? '' : '' ?> transition: background .15s;">
                <i class="<?= $item['icon'] ?> fs-5"></i>
                <?= $item['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Usuário + Sair -->
    <div class="px-3 py-3 border-top border-secondary">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                 style="width:40px;height:40px;background:#4f46e5;flex-shrink:0;">
                <?= $iniciais ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-medium text-white text-truncate" style="max-width:150px;"><?= $nomeUsuario ?></div>
                <small class="text-secondary"><?= $perfil ?></small>
            </div>
        </div>
        <form action="<?= BASE_URL ?>/login/sair" method="POST">
            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right me-2"></i>Sair
            </button>
        </form>
    </div>

</aside>
<!-- ===================== /SIDEBAR ===================== -->

<!-- ===================== CONTEÚDO ===================== -->
<div class="d-flex flex-column flex-grow-1" style="min-height:100vh; overflow-x:hidden;">

    <!-- Header superior -->
    <header class="d-flex align-items-center justify-content-between px-4"
            style="height:64px; background:#1e293b; border-bottom:1px solid #334155; flex-shrink:0; position:sticky; top:0; z-index:100;">

        <!-- Botão menu mobile -->
        <button class="btn btn-link text-secondary d-md-none p-0 me-3" id="btn-toggle-sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <h1 class="fs-5 fw-semibold text-white mb-0"><?= $tituloPagina ?></h1>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link text-secondary p-2" id="btn-tema" type="button" onclick="toggleTema()">
                <i class="bi bi-sun-fill" id="icon-tema"></i>
            </button>

            <!-- Sino de alertas -->
            <a href="<?= BASE_URL ?>/alertas" class="btn btn-link text-secondary position-relative p-2">
                <i class="bi bi-bell fs-5"></i>
                <?php if (!empty($totalAlertas) && $totalAlertas > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">
                        <?= $totalAlertas ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>

    </header>

    <!-- Flash message -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="px-4 pt-3">
            <div class="alert alert-<?= $_SESSION['flash']['tipo'] ?> alert-dismissible fade show mb-0" role="alert">
                <i class="bi bi-<?= $_SESSION['flash']['tipo'] === 'danger' ? 'x-circle' : 'check-circle' ?> me-2"></i>
                <?= htmlspecialchars($_SESSION['flash']['mensagem']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- CONTEÚDO DA PÁGINA -->
    <main class="flex-grow-1 p-4">