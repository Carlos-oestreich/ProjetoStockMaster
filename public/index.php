<?php

# Arquivo mestre que controla todas as requisições enviadas ao .htaccess
# E redireciona aos caminhos correspondentes

# Inicia o sistema de sessões
session_start();

# Carrega o composer autoload com todas as dependências
require "../vendor/autoload.php";

// Carrega variaveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// E-mail unico de suporte (TI)
define('SUPORTE_EMAIL', $_ENV['SUPORTE_EMAIL'] ?? 'suporte@stockmaster.com');

# Caminho base do projeto no XAMPP (ajuste se necessário)
define('BASE_URL', '/StockMasterWeb');

// ================================================================
// ROTAS
// ================================================================
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    // --- Login / Auth ---
    $r->get('/',              'LoginController@index');
    $r->get('/login',         'LoginController@index');
    $r->post('/login/entrar', 'LoginController@entrar');
    $r->post('/login/sair',   'LoginController@sair');

    // --- Cadastro inicial (nova empresa) ---
    $r->get('/cadastro-inicial',        'cadastroInicialController@index');
    $r->post('/cadastro-inicial/salvar','cadastroInicialController@salvar');

    // --- Alterar senha (senha temporaria) ---
    $r->get('/alterar-senha',        'alterarSenhaController@index');
    $r->post('/alterar-senha/salvar','alterarSenhaController@salvar');

    // --- Dashboard ---
    $r->get('/dashboard', 'DashboardController@index');

    // --- Produtos ---
    $r->get('/produtos',              'ProdutoController@listar');
    $r->get('/produtos/novo',         'ProdutoController@novo');
    $r->get('/produtos/{id}',         'ProdutoController@buscar');
    $r->post('/produtos/salvar',      'ProdutoController@salvar');
    $r->post('/produtos/{id}/salvar', 'ProdutoController@salvar');
    $r->post('/produtos/{id}/deletar','ProdutoController@deletar');

    // --- Categorias ---
    $r->get('/categorias',               'CategoriaController@listar');
    $r->get('/categorias/novo',          'CategoriaController@novo');
    $r->get('/categorias/{id}',          'CategoriaController@buscar');
    $r->post('/categorias/salvar',       'CategoriaController@salvar');
    $r->post('/categorias/{id}/salvar',  'CategoriaController@salvar');
    $r->post('/categorias/{id}/deletar', 'CategoriaController@deletar');

    // --- Fornecedores ---
    $r->get('/fornecedores',               'FornecedorController@listar');
    $r->get('/fornecedores/novo',          'FornecedorController@novo');
    $r->get('/fornecedores/{id}',          'FornecedorController@buscar');
    $r->post('/fornecedores/salvar',       'FornecedorController@salvar');
    $r->post('/fornecedores/{id}/salvar',  'FornecedorController@salvar');
    $r->post('/fornecedores/{id}/deletar', 'FornecedorController@deletar');

    // --- Movimentações ---
    $r->get('/movimentacoes',          'MovimentacaoController@listar');
    $r->get('/movimentacoes/nova',     'MovimentacaoController@nova');
    $r->post('/movimentacoes/salvar',  'MovimentacaoController@salvar');

    // --- Alertas ---
    $r->get('/alertas', 'AlertaController@index');

    // --- Usuários (admin) ---
    $r->get('/usuarios',               'UsuarioController@listar');
    $r->get('/usuarios/novo',          'UsuarioController@novo');
    $r->get('/usuarios/{id}',          'UsuarioController@buscar');
    $r->post('/usuarios/salvar',       'UsuarioController@salvar');
    $r->post('/usuarios/{id}/salvar',  'UsuarioController@salvar');
    $r->post('/usuarios/{id}/deletar', 'UsuarioController@deletar');

    // --- Relatórios (admin) ---
    $r->get('/relatorios', 'RelatorioController@index');

    // --- Configuracoes ---
    $r->get('/configuracoes',                'ConfiguracaoController@index');
    $r->post('/configuracoes/salvar-empresa','ConfiguracaoController@salvarEmpresa');
    $r->post('/configuracoes/salvar-perfil', 'ConfiguracaoController@salvarPerfil');

});

// ================================================================
// RESOLVE URI
// ================================================================
$uri      = parse_url($_SERVER['REQUEST_URI'])['path'];
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
$uri      = substr($uri, strlen($basePath)) ?: '/';
$method   = $_SERVER['REQUEST_METHOD'];

$route = $dispatcher->dispatch($method, $uri);

// ================================================================
// ROTAS PROTEGIDAS — requer login
// ================================================================
$rotasPublicas = ['/login', '/login/entrar', '/', '/cadastro-inicial', '/cadastro-inicial/salvar'];

function requerLogin(): void
{
    global $uri;
    if (empty($_SESSION['usuario'])) {
        $_SESSION['flash'] = ['tipo' => 'warning', 'mensagem' => 'Faça login para continuar.'];
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    $rotasSenhaTemporaria = ['/alterar-senha', '/alterar-senha/salvar', '/login/sair'];
    if (!empty($_SESSION['usuario']['senhaTemporaria'])
        && !in_array($uri, $rotasSenhaTemporaria, true)) {
        $_SESSION['flash'] = ['tipo' => 'warning', 'mensagem' => 'Defina uma nova senha para continuar.'];
        header('Location: ' . BASE_URL . '/alterar-senha');
        exit;
    }
}

function requerAdmin(): void
{
    requerLogin();
    if (!in_array(($_SESSION['usuario']['perfil'] ?? ''), ['ADM', 'DONO'], true)) {
        $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => 'Acesso restrito a administradores.'];
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
}

// ================================================================
// DESPACHO
// ================================================================
switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo "<h1>404 – Rota não encontrada</h1>";
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "<h1>405 – Método não permitido</h1>";
        break;

    case FastRoute\Dispatcher::FOUND:
        [$controllerClass, $action] = explode('@', $route[1]);
        $params = $route[2];

        // Proteção de rotas
        $rotasAdmin = ['CategoriaController', 'FornecedorController', 'UsuarioController', 'RelatorioController'];

        if (!in_array($uri, $rotasPublicas, true)) {
            requerLogin();
        }

        if (in_array($controllerClass, $rotasAdmin)) {
            requerAdmin();
        }

        $controllerNamespace = "controller\\{$controllerClass}";
        $controller = new $controllerNamespace();
        $controller->$action($params);
        break;
}