<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;

class LoginController
{
    /**
     * GET /login
     * Exibe a tela de login
     */
    public function index()
    {
        // Se já está logado, redireciona ao dashboard
        if (!empty($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        require __DIR__ . '/../view/login.php';
    }

    /**
     * POST /login/entrar
     * Processa o login: valida credenciais, inicia sessão
     */
    public function entrar()
    {
        try {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($email) || empty($senha)) {
                throw new Exception('Preencha e-mail e senha.');
            }

            $usuario = UsuarioDAO::buscarPorEmail($email);

            if (empty($usuario)) {
                throw new Exception('Credenciais inválidas.');
            }

            // Verifica senha (password_hash / password_verify)
            if (!password_verify($senha, $usuario->getSenha())) {
                throw new Exception('Credenciais inválidas.');
            }

            if (!$usuario->getAtivo()) {
                throw new Exception('Usuário inativo. Contate o administrador.');
            }

            $empresa = $usuario->getEmpresa();

            // Grava sessão
            $_SESSION['usuario'] = [
                'id'               => $usuario->getId(),
                'nome'             => $usuario->getNome(),
                'email'            => $usuario->getEmail(),
                'perfil'           => $usuario->getPerfil(),
                'empresaId'        => $empresa?->getId(),
                'empresaNome'      => $empresa?->getNome(),
                'empresaLogo'      => $empresa?->getLogo(),
                'empresaCnpj'      => $empresa?->getCnpj(),
                'empresaEmail'     => $empresa?->getEmail(),
                'empresaTelefone'  => $empresa?->getTelefone(),
                'empresaEndereco'  => $empresa?->getEndereco(),
                'senhaTemporaria'  => (bool)$usuario->getSenhaTemporaria(),
            ];

            if ($usuario->getSenhaTemporaria()) {
                $_SESSION['flash'] = [
                    'tipo' => 'warning',
                    'mensagem' => 'Sua senha e temporaria. Defina uma nova senha para continuar.'
                ];
                header('Location: ' . BASE_URL . '/alterar-senha');
                exit;
            }

            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Bem-vindo, ' . $usuario->getNome() . '!'];
            header('Location: ' . BASE_URL . '/dashboard');
            exit;

        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * POST /login/sair
     * Encerra a sessão e redireciona ao login
     */
    public function sair()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
