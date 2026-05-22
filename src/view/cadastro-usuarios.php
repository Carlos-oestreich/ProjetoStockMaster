<?php
/**
 * @var model\Usuario $usuario
 * @var int                                       $totalAlertas
 */
$usuarioModel = $usuario ?? null;
include_once 'template-cabecalho.php';

$perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
$somenteOperador = $somenteOperador ?? ($perfilLogado === 'ADM');

$isEdicao = $usuarioModel?->getId() !== null;
$action   = $isEdicao
    ? BASE_URL . "/usuarios/{$usuarioModel->getId()}/salvar"
    : BASE_URL . "/usuarios/salvar";
?>

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/usuarios" class="text-primary">Usuarios</a></li>
            <li class="breadcrumb-item active text-secondary">
                <?= $isEdicao ? htmlspecialchars($usuarioModel?->getNome() ?? '') : 'Novo Usuario' ?>
            </li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            <div class="card border-secondary shadow" style="background:#1e293b;">
                <div class="card-header border-secondary py-3">
                    <h4 class="card-title mb-0 fw-semibold text-white">
                        <i class="bi bi-<?= $isEdicao ? 'pencil-square' : 'plus-circle' ?> me-2 text-primary"></i>
                        <?= $isEdicao ? 'Editar Usuario' : 'Novo Usuario' ?>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $action ?>" method="POST" class="needs-validation" novalidate>

                        <?php if ($somenteOperador): ?>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Administradores podem cadastrar e editar apenas operadores.
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   value="<?= htmlspecialchars($usuarioModel?->getNome() ?? '') ?>"
                                   placeholder="Nome completo" required maxlength="200">
                            <div class="invalid-feedback">Informe o nome.</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= htmlspecialchars($usuarioModel?->getEmail() ?? '') ?>"
                                       placeholder="usuario@email.com" required>
                                <div class="invalid-feedback">Informe um e-mail valido.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="matricula" class="form-label">Matricula</label>
                                <input type="text" class="form-control" id="matricula" name="matricula"
                                       value="<?= htmlspecialchars($usuarioModel?->getMatricula() ?? '') ?>"
                                       placeholder="Ex: 2024001">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                    <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cpf" name="cpf"
                                       value="<?= htmlspecialchars($usuarioModel?->getCpf() ?? '') ?>"
                                       placeholder="000.000.000-00" maxlength="14"
                                        oninput="mascaraCPF(this)" required>
                                    <div class="invalid-feedback">Informe o CPF.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="perfil" class="form-label">Perfil <span class="text-danger">*</span></label>
                            <select class="form-select" id="perfil" name="perfil" required>
                                <option value="">Selecione...</option>
                                <?php if (!$somenteOperador): ?>
                                    <option value="ADM"      <?= ($usuarioModel?->getPerfil() ?? '') === 'ADM'      ? 'selected' : '' ?>>Administrador (acesso total)</option>
                                <?php endif; ?>
                                <option value="OPERADOR" <?= ($usuarioModel?->getPerfil() ?? '') === 'OPERADOR' ? 'selected' : '' ?>>Operador (acesso limitado)</option>
                            </select>
                            <div class="invalid-feedback">Selecione o perfil.</div>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">
                                Senha <?= !$isEdicao ? '<span class="text-danger">*</span>' : '' ?>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="senha" name="senha"
                                       placeholder="<?= $isEdicao ? 'Deixe em branco para nao alterar' : 'Minimo 8 caracteres' ?>"
                                    <?= !$isEdicao ? 'required minlength="8"' : 'minlength="8"' ?>>
                                <button class="btn btn-outline-secondary" type="button" id="btn-toggle-senha">
                                    <i class="bi bi-eye" id="icon-olho"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Informe a senha (minimo 8 caracteres).</div>
                            <div id="msg-senha" class="form-text mt-1"></div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ativo" name="ativo"
                                        value="1" <?= ($usuarioModel?->getAtivo() ?? true) !== false ? 'checked' : '' ?>>
                                <label class="form-check-label text-white" for="ativo">Usuario ativo</label>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i>Salvar
                            </button>
                            <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btn-toggle-senha').addEventListener('click', function () {
            const input = document.getElementById('senha');
            const icon  = document.getElementById('icon-olho');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof monitorarForcaSenha === 'function') {
                monitorarForcaSenha('senha', 'msg-senha');
            }
        });
    </script>

<?php include_once 'template-rodape.php'; ?>