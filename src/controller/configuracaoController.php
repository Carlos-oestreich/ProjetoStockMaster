<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;
use dao\EmpresaDAO;
use dao\ProdutoDAO;
use model\Usuario;
use utils\Conexao;
use Cloudinary\Cloudinary;

class ConfiguracaoController
{
    protected static $modelClass = Usuario::class;

    public function index(): void
    {
        requerLogin();

        $empresaId = $_SESSION['usuario']['empresaId'] ?? null;
        $empresa   = $empresaId ? EmpresaDAO::buscarPorId($empresaId) : null;
        $usuario   = UsuarioDAO::buscarPorId($_SESSION['usuario']['id']);
        $dono      = $empresaId ? UsuarioDAO::buscarDonoDaEmpresa($empresaId) : null;
        $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        $emailSuporte = SUPORTE_EMAIL;

        $paginaAtiva  = 'configuracoes';
        $tituloPagina = 'Configuracoes';
        require __DIR__ . '/../view/configuracao.php';
    }

    public function salvarEmpresa(): void
    {
        requerLogin();

        try {
            $perfil = $_SESSION['usuario']['perfil'] ?? '';
            if ($perfil !== 'DONO') {
                throw new Exception('Apenas o dono pode alterar dados da empresa.');
            }

            $empresaId = $_SESSION['usuario']['empresaId'] ?? null;
            $empresa = $empresaId ? EmpresaDAO::buscarPorId($empresaId) : null;
            if (!$empresa) {
                throw new Exception('Empresa nao encontrada.');
            }

            $nome      = filter_input(INPUT_POST, 'nome_empresa', FILTER_SANITIZE_SPECIAL_CHARS);
            $cnpj      = filter_input(INPUT_POST, 'cnpj_empresa', FILTER_SANITIZE_SPECIAL_CHARS);
            $email     = filter_input(INPUT_POST, 'email_empresa', FILTER_SANITIZE_EMAIL);
            $telefone  = filter_input(INPUT_POST, 'telefone_empresa', FILTER_SANITIZE_SPECIAL_CHARS);
            $endereco  = filter_input(INPUT_POST, 'endereco_empresa', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($nome)) {
                throw new Exception('Informe o nome da empresa.');
            }

            $cnpjLimpo = preg_replace('/\D/', '', $cnpj ?? '');
            if (!empty($cnpjLimpo) && strlen($cnpjLimpo) !== 14) {
                throw new Exception('CNPJ invalido. Informe 14 digitos.');
            }

            $empresa->setNome($nome);
            $empresa->setCnpj($cnpjLimpo ?: null);
            $empresa->setEmail($email ?: null);
            $empresa->setTelefone($telefone ?: null);
            $empresa->setEndereco($endereco ?: null);


            if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($ext, $permitidas, true)) {
                    throw new Exception('Formato de logo invalido.');
                }

                $cloudName = $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '';
                $apiKey = $_ENV['CLOUDINARY_API_KEY'] ?? '';
                $apiSecret = $_ENV['CLOUDINARY_API_SECRET'] ?? '';
                if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
                    throw new Exception('Cloudinary nao configurado.');
                }

                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => $cloudName,
                        'api_key' => $apiKey,
                        'api_secret' => $apiSecret,
                    ],
                    'url' => ['secure' => true],
                ]);

                $upload = $cloudinary->uploadApi()->upload(
                    $_FILES['logo']['tmp_name'],
                    [
                        'folder' => 'stockmaster/logos',
                        'public_id' => 'empresa_' . $empresa->getId(),
                        'overwrite' => true,
                        'invalidate' => true,
                        'resource_type' => 'image',
                    ]
                );

                $empresa->setLogo($upload['secure_url']);
            }

            EmpresaDAO::atualizar($empresa);
            $_SESSION['usuario']['empresaNome'] = $empresa->getNome();
            $_SESSION['usuario']['empresaLogo'] = $empresa->getLogo();
            $_SESSION['usuario']['empresaCnpj'] = $empresa->getCnpj();
            $_SESSION['usuario']['empresaEmail'] = $empresa->getEmail();
            $_SESSION['usuario']['empresaTelefone'] = $empresa->getTelefone();
            $_SESSION['usuario']['empresaEndereco'] = $empresa->getEndereco();

            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Dados da empresa atualizados!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        }

        header('Location: ' . BASE_URL . '/configuracoes');
        exit;
    }

    public function salvarPerfil(): void
    {
        requerLogin();

        try {
            $nome   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email  = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $cpf    = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
            $senhaAtual = filter_input(INPUT_POST, 'senha_atual', FILTER_SANITIZE_SPECIAL_CHARS);
            $novaSenha  = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);
            $confirma   = filter_input(INPUT_POST, 'confirma_senha', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($nome) || empty($email)) {
                throw new Exception('Nome e e-mail sao obrigatorios.');
            }

            $usuario = UsuarioDAO::buscarPorId($_SESSION['usuario']['id']);
            if (!$usuario) throw new Exception('Usuario nao encontrado.');

            if ($email !== $usuario->getEmail()) {
                $existente = UsuarioDAO::buscarPorEmail($email);
                if ($existente && $existente->getId() !== $usuario->getId()) {
                    throw new Exception('Este e-mail ja esta cadastrado no sistema.');
                }
            }

            $cpfLimpo = preg_replace('/\D/', '', $cpf ?? '');
            if (empty($cpfLimpo)) {
                throw new Exception('CPF e obrigatorio.');
            }
            if (strlen($cpfLimpo) !== 11) {
                throw new Exception('CPF invalido. Informe os 11 digitos.');
            }
            if (!empty($cpfLimpo)) {
                $existenteCpf = UsuarioDAO::buscarPorCpf($cpfLimpo);
                if ($existenteCpf && $existenteCpf->getId() !== $usuario->getId()) {
                    throw new Exception('Este CPF ja esta cadastrado.');
                }
            }

            $usuario->setNome($nome);
            $usuario->setEmail($email);
            $usuario->setCpf($cpfLimpo ?: null);

            $informouSenha = !empty($novaSenha) || !empty($confirma) || !empty($senhaAtual);
            if ($informouSenha) {
                if (empty($senhaAtual) || empty($novaSenha) || empty($confirma)) {
                    throw new Exception('Preencha os campos de senha corretamente.');
                }
                if (!password_verify($senhaAtual, $usuario->getSenha())) {
                    throw new Exception('Senha atual incorreta.');
                }
                if ($novaSenha !== $confirma) {
                    throw new Exception('As senhas nao conferem.');
                }
                if (password_verify($novaSenha, $usuario->getSenha())) {
                    throw new Exception('A nova senha nao pode ser igual a senha atual.');
                }

                self::validarForcaSenha($novaSenha);
                $usuario->setSenha(password_hash($novaSenha, PASSWORD_DEFAULT));
                $usuario->setSenhaTemporaria(false);
            }

            UsuarioDAO::atualizar($usuario);
            $_SESSION['usuario']['nome'] = $usuario->getNome();
            $_SESSION['usuario']['email'] = $usuario->getEmail();
            if ($informouSenha) {
                $_SESSION['usuario']['senhaTemporaria'] = false;
            }

            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Perfil atualizado com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        }

        header('Location: ' . BASE_URL . '/configuracoes');
        exit;
    }

    public static function validarForcaSenha(string $senha): void
    {
        if (strlen($senha) < 8) {
            throw new Exception('A senha deve ter pelo menos 8 caracteres.');
        }
        if (!preg_match('/[A-Z]/', $senha)) {
            throw new Exception('A senha deve conter pelo menos uma letra maiuscula.');
        }
        if (!preg_match('/[a-z]/', $senha)) {
            throw new Exception('A senha deve conter pelo menos uma letra minuscula.');
        }
        if (!preg_match('/\d/', $senha)) {
            throw new Exception('A senha deve conter pelo menos um numero.');
        }
        if (!preg_match('/[\W_]/', $senha)) {
            throw new Exception('A senha deve conter pelo menos um simbolo.');
        }
    }

    public static function buscarPorEmail(string $email): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['email' => $email]);
    }

    public static function buscarPorEmailEEmpresa(string $email, int $empresaId): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'email'   => $email,
            'empresa' => $empresaId,
        ]);
    }

    public static function buscarPorCpf(string $cpf): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['cpf' => $cpf]);
    }

    public static function listarPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['empresa' => $empresaId]);
    }

    public static function listarPorEmpresaEPerfil(int $empresaId, string $perfil): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy([
            'empresa' => $empresaId,
            'perfil'  => $perfil,
        ]);
    }

    public static function existeDonoPorEmpresa(int $empresaId): bool
    {
        $donos = self::listarPorEmpresaEPerfil($empresaId, 'DONO');
        return !empty($donos);
    }

    public static function buscarDonoDaEmpresa(int $empresaId): ?Usuario
    {
        $donos = self::listarPorEmpresaEPerfil($empresaId, 'DONO');
        return $donos[0] ?? null;
    }

    public static function existeDono(): bool
    {
        $em = Conexao::getEntityManager();
        $donos = $em->getRepository(self::$modelClass)->findBy(['perfil' => 'DONO']);
        return !empty($donos);
    }
}