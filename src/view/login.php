<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockMaster – Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-page);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
        }
        .brand-icon {
            width: 64px;
            height: 64px;
            background: #4f46e5;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
            font-size: 2rem;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="login-card px-3">

    <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-link text-secondary p-2" id="btn-tema" type="button" onclick="toggleTema()">
            <i class="bi bi-sun-fill" id="icon-tema"></i>
        </button>
    </div>

    <!-- Ícone + Título -->
    <div class="text-center mb-4">
        <div class="brand-icon"><i class="bi bi-boxes"></i></div>
        <h1 class="fs-2 fw-bold text-white">StockMaster</h1>
        <p class="text-secondary">Sistema de Gestão de Estoque</p>
    </div>

    <!-- Card do formulário -->
    <div class="card border-secondary shadow-lg">
        <div class="card-body p-4">

            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['tipo'] ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?= $_SESSION['flash']['tipo'] === 'danger' ? 'x-circle' : 'check-circle' ?> me-2"></i>
                    <?= htmlspecialchars($_SESSION['flash']['mensagem']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login/entrar" method="POST" id="form-login" novalidate>

                <!-- E-mail -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="seu@email.com"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required autofocus>
                        <div class="invalid-feedback">Informe um e-mail válido.</div>
                    </div>
                </div>

                <!-- Senha -->
                <div class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="senha" name="senha"
                               placeholder="••••••••" required>
                        <button class="btn btn-outline-secondary" type="button" id="btn-toggle-senha"
                                title="Mostrar/ocultar senha">
                            <i class="bi bi-eye" id="icon-olho"></i>
                        </button>
                        <div class="invalid-feedback">Informe a senha.</div>
                    </div>
                </div>

                <!-- Botão -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar no sistema
                    </button>
                </div>

                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>/cadastro-inicial" class="btn btn-link text-primary">
                        <i class="bi bi-plus-circle me-1"></i>Cadastrar nova empresa
                    </a>
                </div>

            </form>
        </div>
    </div>



</div><!-- /login-card -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/app.js"></script>
<script>
    // Toggle mostrar/ocultar senha
    document.getElementById('btn-toggle-senha').addEventListener('click', function () {
        const input = document.getElementById('senha');
        const icon = document.getElementById('icon-olho');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    // Validação Bootstrap antes de submeter
    document.getElementById('form-login').addEventListener('submit', function (e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        this.classList.add('was-validated');
    });
</script>
</body>
</html>