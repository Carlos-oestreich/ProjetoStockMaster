<?php

use dao\UsuarioDAO;
use model\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioDAOTest extends TestCase
{
    public function testInserir()
    {
        $usuario = new Usuario();
        $usuario->setNome("Carlos");
        $usuario->setEmail("carlos@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("ADMIN");
        $usuario->setMatricula("2025001");
        $usuario->setAtivo(true);

        $usuarioInserido = UsuarioDAO::salvar($usuario);

        $this->assertNotNull($usuarioInserido->getId());
    }

    public function testListar()
    {
        $usuarios = UsuarioDAO::listar();

        foreach ($usuarios as $usuario){
            echo $usuario->getNome() . "\n";
        }

        $this->assertNotNull($usuarios);
    }

    public function testBuscarPorId()
    {
        $usuario = new Usuario();
        $usuario->setNome("Usuario ID");
        $usuario->setEmail("usuarioid@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025010");
        $usuario->setAtivo(true);

        $usuario = UsuarioDAO::salvar($usuario);

        $resultado = UsuarioDAO::buscarPorId($usuario->getId());

        $this->assertNotNull($resultado);
        $this->assertEquals("Usuario ID", $resultado->getNome());
    }

    public function testAtualizar()
    {
        $usuario = new Usuario();
        $usuario->setNome("Usuario Antigo");
        $usuario->setEmail("usuarioantigo@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025011");
        $usuario->setAtivo(true);

        $usuario = UsuarioDAO::salvar($usuario);

        $usuario->setNome("Usuario Atualizado");
        $usuario->setPerfil("ADMIN");

        $usuarioAtualizado = UsuarioDAO::atualizar($usuario);

        $this->assertEquals("Usuario Atualizado", $usuarioAtualizado->getNome());
        $this->assertEquals("ADMIN", $usuarioAtualizado->getPerfil());
    }

    public function testDeletar()
    {
        $usuario = new Usuario();
        $usuario->setNome("Usuario Excluir");
        $usuario->setEmail("usuarioexcluir@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025012");
        $usuario->setAtivo(true);

        $usuario = UsuarioDAO::salvar($usuario);

        $resultado = UsuarioDAO::deletar($usuario->getId());

        $this->assertTrue($resultado);
    }

    public function testBuscarPorNome()
    {
        $usuario = new Usuario();
        $usuario->setNome("Carlos Busca");
        $usuario->setEmail("carlosbusca@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("ADMIN");
        $usuario->setMatricula("2025013");
        $usuario->setAtivo(true);

        UsuarioDAO::salvar($usuario);

        $resultado = UsuarioDAO::buscarPorNome("Carlos Busca");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorEmail()
    {
        $usuario = new Usuario();
        $usuario->setNome("Carlos Email");
        $usuario->setEmail("carlosemail@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("ADMIN");
        $usuario->setMatricula("2025014");
        $usuario->setAtivo(true);

        UsuarioDAO::salvar($usuario);

        $resultado = UsuarioDAO::buscarPorEmail("carlosemail@email.com");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorPerfil()
    {
        $resultado = UsuarioDAO::buscarPorPerfil("ADMIN");

        $this->assertNotNull($resultado);
    }

}