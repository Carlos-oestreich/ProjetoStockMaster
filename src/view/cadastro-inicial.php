<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockMaster – Cadastro da Empresa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
    <style>
        body { background:#0f172a; }
        .brand-icon { width:64px;height:64px;background:#4f46e5;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;box-shadow:0 8px 24px rgba(79,70,229,.4);font-size:2rem;color:#fff; }
        .step-badge { width:28px;height:28px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
<div class="w-100 px-3" style="max-width:560px;">

    <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-link text-secondary p-2" id="btn-tema" type="button" onclick="toggleTema()">
            <i class="bi bi-sun-fill" id="icon-tema"></i>
        </button>
    </div>

    <div class="text-center mb-4">
        <div class="brand-icon"><i class="bi bi-boxes"></i></div>
        <h1 class="fs-2 fw-bold text-white">StockMaster</h1>
        <p class="text-secondary mb-0">Configuracao inicial do sistema</p>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['tipo'] ?> alert-dismissible fade show">
            <i class="bi bi-<?= $_SESSION['flash']['tipo'] === 'danger' ? 'x-circle' : 'check-circle' ?> me-2"></i>
            <?= htmlspecialchars($_SESSION['flash']['mensagem']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/cadastro-inicial/salvar" method="POST"
          id="form-cadastro" class="needs-validation" novalidate>

        <!-- SECAO 1: Empresa -->
        <div class="card border-secondary mb-3" style="background:#1e293b;">
            <div class="card-header border-secondary py-3 d-flex align-items-center gap-3">
                <span class="step-badge bg-primary text-white">1</span>
                <h5 class="mb-0 fw-semibold text-white">Dados da Empresa</h5>
            </div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label for="nome_empresa" class="form-label">Nome da Empresa <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                               placeholder="Razao social ou nome fantasia" required
                               value="<?= htmlspecialchars($_POST['nome_empresa'] ?? '') ?>">
                        <div class="invalid-feedback">Informe o nome da empresa.</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cnpj_empresa" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj_empresa" name="cnpj_empresa"
                               placeholder="00.000.000/0000-00" maxlength="18"
                               oninput="mascaraCNPJ(this)"
                               value="<?= htmlspecialchars($_POST['cnpj_empresa'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="telefone_empresa" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone_empresa" name="telefone_empresa"
                               placeholder="(00) 00000-0000" maxlength="15"
                               oninput="mascaraTelefone(this)"
                               value="<?= htmlspecialchars($_POST['telefone_empresa'] ?? '') ?>">
                    </div>
                </div>

                <div class="mt-3">
                    <label for="email_empresa" class="form-label">E-mail da Empresa</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email_empresa" name="email_empresa"
                               placeholder="contato@empresa.com"
                               value="<?= htmlspecialchars($_POST['email_empresa'] ?? '') ?>">
                    </div>
                </div>

            </div>
        </div>

        <!-- SECAO 2: Dono -->
        <div class="card border-secondary mb-3" style="background:#1e293b;">
            <div class="card-header border-secondary py-3 d-flex align-items-center gap-3">
                <span class="step-badge bg-primary text-white">2</span>
                <h5 class="mb-0 fw-semibold text-white">Dados do Responsavel (Dono)</h5>
            </div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome completo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="nome" name="nome"
                               placeholder="Seu nome completo" required
                               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        <div class="invalid-feedback">Informe o nome.</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="dono@empresa.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        <div class="invalid-feedback">Informe um e-mail valido.</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cpf" name="cpf"
                               placeholder="000.000.000-00" maxlength="14"
                               oninput="mascaraCPF(this)" required
                               value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
                        <div class="invalid-feedback">Informe o CPF.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="matricula" class="form-label">Matricula</label>
                        <input type="text" class="form-control" id="matricula" name="matricula"
                               placeholder="Ex: DON001"
                               value="<?= htmlspecialchars($_POST['matricula'] ?? '') ?>">
                    </div>
                </div>

                <hr class="border-secondary">

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="senha" name="senha"
                               placeholder="Minimo 8 caracteres" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleSenha('senha','olho1')">
                            <i class="bi bi-eye" id="olho1"></i>
                        </button>
                        <div class="invalid-feedback">Informe a senha (minimo 8 caracteres).</div>
                    </div>
                    <div id="msg-forca" class="form-text mt-1"></div>
                </div>

                <div class="mb-2">
                    <label for="confirma_senha" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirma_senha" name="confirma_senha"
                               placeholder="Repita a senha" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="toggleSenha('confirma_senha','olho2')">
                            <i class="bi bi-eye" id="olho2"></i>
                        </button>
                        <div class="invalid-feedback">Confirme a senha.</div>
                    </div>
                    <div id="msg-confirma" class="form-text mt-1"></div>
                </div>

            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-rocket-takeoff me-2"></i>Criar empresa e acessar o sistema
            </button>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline-secondary btn-lg">
                Cancelar
            </a>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof monitorarForcaSenha === 'function') {
            monitorarForcaSenha('senha', 'msg-forca');
        }
        if (typeof monitorarConfirmaSenha === 'function') {
            monitorarConfirmaSenha('senha', 'confirma_senha', 'msg-confirma');
        }
    });
</script>
</body>
</html>