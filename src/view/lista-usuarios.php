<?php
/** @var model\Usuario[] $usuarios */
include_once 'template-cabecalho.php';

$meuId = $_SESSION['usuario']['id'] ?? null;
$perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Usuarios</h2>
            <small class="text-secondary"><?= count($usuarios) ?> usuario(s)</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-usuarios">
                <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
            </button>
            <a href="<?= BASE_URL ?>/usuarios/novo" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Novo Usuario
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filtro-busca" class="form-control" placeholder="Buscar usuario...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="filtro-perfil" class="form-select">
                <option value="">Todos os Perfis</option>
                <option value="ADM">Administrador</option>
                <option value="OPERADOR">Operador</option>
            </select>
        </div>
    </div>

    <div class="table-stockmaster">
        <table class="table table-dark table-hover mb-0" id="tabela-usuarios">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Matricula</th>
                <th>Perfil</th>
                <th>Status</th>
                <th class="text-end">Acoes</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="7" class="text-center text-secondary py-5">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                        Nenhum usuario cadastrado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                    <?php $podeGerenciar = $perfilLogado === 'DONO' || ($perfilLogado === 'ADM' && $u->getPerfil() === 'OPERADOR'); ?>
                    <tr data-busca="<?= strtolower(htmlspecialchars($u->getNome() . ' ' . $u->getEmail())) ?>"
                        data-perfil="<?= htmlspecialchars($u->getPerfil()) ?>">
                        <td class="text-secondary"><?= $u->getId() ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                     style="width:32px;height:32px;background:#4f46e5;font-size:.85rem;flex-shrink:0;">
                                    <?= strtoupper(substr($u->getNome(), 0, 1)) ?>
                                </div>
                                <span class="fw-medium text-white">
                                    <?= htmlspecialchars($u->getNome()) ?>
                                    <?php if ($u->getId() == $meuId): ?>
                                        <span class="badge bg-secondary ms-1" style="font-size:.65rem;">Voce</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </td>
                        <td class="text-secondary"><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td><code class="text-secondary"><?= htmlspecialchars($u->getMatricula() ?? '—') ?></code></td>
                        <td>
                            <?php if ($u->getPerfil() === 'DONO'): ?>
                                <span class="badge" style="background:rgba(99,102,241,.2);color:#818cf8;">
                                    <i class="bi bi-crown me-1"></i>Dono
                                </span>
                            <?php elseif ($u->getPerfil() === 'ADM'): ?>
                                <span class="badge" style="background:rgba(99,102,241,.2);color:#818cf8;">
                                    <i class="bi bi-shield-check me-1"></i>Administrador
                                </span>
                            <?php else: ?>
                                <span class="badge" style="background:rgba(100,116,139,.2);color:#94a3b8;">
                                    <i class="bi bi-person me-1"></i>Operador
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u->getAtivo()): ?>
                                <span class="badge badge-normal">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-sem-estoque">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if ($podeGerenciar): ?>
                                <a href="<?= BASE_URL ?>/usuarios/<?= $u->getId() ?>"
                                   class="btn btn-icon btn-outline-primary me-1" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($u->getId() != $meuId): ?>
                                    <form action="<?= BASE_URL ?>/usuarios/<?= $u->getId() ?>/deletar"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirmarExclusao('Remover o usuario')">
                                        <button class="btn btn-icon btn-outline-danger" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-icon btn-outline-secondary" disabled title="Nao e possivel excluir seu proprio usuario">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-icon btn-outline-secondary" disabled title="Acesso restrito">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        (function () {
            const busca    = document.getElementById('filtro-busca');
            const perfilSel = document.getElementById('filtro-perfil');
            const linhas   = document.querySelectorAll('#tabela-usuarios tbody tr[data-busca]');

            function filtrar() {
                const termo  = busca.value.toLowerCase();
                const perfil = perfilSel.value;
                linhas.forEach(tr => {
                    const okBusca  = !termo  || tr.dataset.busca.includes(termo);
                    const okPerfil = !perfil || tr.dataset.perfil === perfil;
                    tr.style.display = (okBusca && okPerfil) ? '' : 'none';
                });
            }



            busca.addEventListener('input', filtrar);
            perfilSel.addEventListener('change', filtrar);
        })();


        (function () {
            const btn = document.getElementById('btn-pdf-usuarios');
            if (!btn) return;

            const colunas = <?= json_encode(['ID', 'Nome', 'E-mail', 'Matricula', 'Perfil', 'Status']) ?>;
            const linhas = <?= json_encode(array_map(function ($u) {
            return [
                    $u->getId(),
                    $u->getNome(),
                    $u->getEmail(),
                    $u->getMatricula() ?? '—',
                    $u->getPerfil(),
                    $u->getAtivo() ? 'Ativo' : 'Inativo',
            ];
        }, $usuarios), JSON_UNESCAPED_UNICODE) ?>;

            btn.addEventListener('click', function () {
            gerarPDF({
            titulo: 'Usuarios',
            orientacao: 'portrait',
            colunas: colunas,
            linhas: linhas,
            empresa: window.stockmasterEmpresa || {},
            logo: window.stockmasterEmpresa?.logo || null,
        });
        });
        })();
    </script>


<?php include_once 'template-rodape.php'; ?>