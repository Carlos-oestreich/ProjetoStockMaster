<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockMaster – Alterar Senha</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
    <style>body { background:var(--bg-page); }</style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="w-100 px-3" style="max-width:440px;">

    <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-link text-secondary p-2" id="btn-tema" type="button" onclick="toggleTema()">
            <i class="bi bi-sun-fill" id="icon-tema"></i>
        </button>
    </div>

    <!-- Alerta de senha temporaria -->
    <div class="alert border-0 mb-4 d-flex gap-3 align-items-start"
         style="background:rgba(245,158,11,.12);border-left:3px solid #f59e0b!important;border-radius:10px;">
        <i class="bi bi-key-fill text-warning fs-5 mt-1"></i>
        <div>
            <div class="fw-semibold text-warning mb-1">Senha temporaria detectada</div>
            <small class="text-secondary">
                Sua conta foi criada com uma senha temporaria.
                Defina uma nova senha pessoal para continuar usando o sistema.
            </small>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['tipo'] ?> alert-dismissible fade show">
            <i class="bi bi-<?= $_SESSION['flash']['tipo'] === 'danger' ? 'x-circle' : 'check-circle' ?> me-2"></i>
            <?= htmlspecialchars($_SESSION['flash']['mensagem']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card border-secondary shadow" style="background:#1e293b;">
        <div class="card-header border-secondary py-3">
            <h4 class="mb-0 fw-semibold text-white">
                <i class="bi bi-shield-lock me-2 text-primary"></i>Definir Nova Senha
            </h4>
        </div>
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3"
                 style="background:rgba(79,70,229,.1);border:1px solid rgba(79,70,229,.2);">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width:36px;height:36px;background:#4f46e5;flex-shrink:0;font-size:.9rem;">
                    <?= strtoupper(substr($_SESSION['usuario']['nome'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-medium text-white" style="font-size:.9rem;">
                        <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? '') ?>
                    </div>
                    <small class="text-secondary"><?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?></small>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/alterar-senha/salvar" method="POST"
                  class="needs-validation" novalidate>

                <div class="mb-3">
                    <label for="senha_atual" class="form-label">Senha Atual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="senha_atual" name="senha_atual"
                               placeholder="Senha definida pelo administrador" required>
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleSenha('senha_atual','o1')">
                            <i class="bi bi-eye" id="o1"></i>
                        </button>
                        <div class="invalid-feedback">Informe a senha atual.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nova_senha" class="form-label">Nova Senha <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha"
                               placeholder="Minimo 8 caracteres" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleSenha('nova_senha','o2')">
                            <i class="bi bi-eye" id="o2"></i>
                        </button>
                        <div class="invalid-feedback">Informe a nova senha (minimo 8 caracteres).</div>
                    </div>
                    <div id="msg-forca" class="form-text mt-1"></div>
                </div>

                <div class="mb-4">
                    <label for="confirma_senha" class="form-label">Confirmar Nova Senha <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirma_senha" name="confirma_senha"
                               placeholder="Repita a nova senha" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleSenha('confirma_senha','o3')">
                            <i class="bi bi-eye" id="o3"></i>
                        </button>
                        <div class="invalid-feedback">Confirme a nova senha.</div>
                    </div>
                    <div id="msg-confirma" class="form-text mt-1"></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-lg me-2"></i>Salvar nova senha
                    </button>
                    <form action="<?= BASE_URL ?>/login/sair" method="POST" class="d-grid">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="bi bi-box-arrow-right me-2"></i>Sair do sistema
                        </button>
                    </form>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script>
    document.querySelector('form.needs-validation').addEventListener('submit', function(e) {
        const n = document.getElementById('nova_senha').value;
        const c = document.getElementById('confirma_senha').value;
        if (n !== c) {
            e.preventDefault(); e.stopPropagation();
            document.getElementById('confirma_senha').setCustomValidity('invalido');
            document.getElementById('msg-confirma').innerHTML =
                '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>As senhas nao conferem.</span>';
        }
        if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        this.classList.add('was-validated');
    });
    if (typeof monitorarForcaSenha === 'function') {
        monitorarForcaSenha('nova_senha', 'msg-forca');
    }
    if (typeof monitorarConfirmaSenha === 'function') {
        monitorarConfirmaSenha('nova_senha', 'confirma_senha', 'msg-confirma');
    }
</script>
</body>
</html>