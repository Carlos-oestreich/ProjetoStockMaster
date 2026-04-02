<?php

use CarlosELarissa\Stockmaster\dao\FornecedorDAO;
use CarlosELarissa\Stockmaster\model\Fornecedor;
use PHPUnit\Framework\TestCase;

class FornecedorDAOTest extends TestCase
{
    public function testInserir()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor ABC");
        $fornecedor->setCnpj("12345678000199");
        $fornecedor->setEmail("fornecedor@email.com");
        $fornecedor->setTelefone("46999999999");
        $fornecedor->setAtivo(true);

        $fornecedorInserido = FornecedorDAO::salvar($fornecedor);

        $this->assertNotNull($fornecedorInserido->getId());
    }

    public function testListar()
    {
        $fornecedores = FornecedorDAO::listar();

        foreach ($fornecedores as $fornecedor){
            echo $fornecedor->getNome() . "\n";
        }

        $this->assertNotNull($fornecedores);
    }

    public function testBuscarPorId()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor ID");
        $fornecedor->setCnpj("12312312300011");
        $fornecedor->setEmail("id@email.com");
        $fornecedor->setTelefone("46911111111");
        $fornecedor->setAtivo(true);

        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $resultado = FornecedorDAO::buscarPorId($fornecedor->getId());

        $this->assertNotNull($resultado);
        $this->assertEquals("Fornecedor ID", $resultado->getNome());
    }

    public function testAtualizar()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Antigo");
        $fornecedor->setCnpj("32132132100011");
        $fornecedor->setEmail("antigo@email.com");
        $fornecedor->setTelefone("46922222222");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $fornecedor->setNome("Fornecedor Atualizado");
        $fornecedor->setEmail("novo@email.com");

        $fornecedorAtualizado = FornecedorDAO::atualizar($fornecedor);

        $this->assertEquals("Fornecedor Atualizado", $fornecedorAtualizado->getNome());
        $this->assertEquals("novo@email.com", $fornecedorAtualizado->getEmail());
    }

    public function testDeletar()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Excluir");
        $fornecedor->setCnpj("99988877700011");
        $fornecedor->setEmail("excluir@email.com");
        $fornecedor->setTelefone("46933333333");
        $fornecedor->setAtivo(true);

        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $resultado = FornecedorDAO::deletar($fornecedor->getId());

        $this->assertTrue($resultado);
    }

    public function testBuscarPorNome()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Busca");
        $fornecedor->setCnpj("45645645600011");
        $fornecedor->setEmail("busca@email.com");
        $fornecedor->setTelefone("46944444444");
        $fornecedor->setAtivo(true);

        FornecedorDAO::salvar($fornecedor);

        $resultado = FornecedorDAO::buscarPorNome("Fornecedor Busca");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorCnpj()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor CNPJ");
        $fornecedor->setCnpj("55544433300011");
        $fornecedor->setEmail("cnpj@email.com");
        $fornecedor->setTelefone("46955555555");
        $fornecedor->setAtivo(true);

        FornecedorDAO::salvar($fornecedor);

        $resultado = FornecedorDAO::buscarPorCnpj("55544433300011");

        $this->assertNotNull($resultado);
    }

    public function testListarAtivos()
    {
        $resultado = FornecedorDAO::listarAtivos();

        $this->assertNotNull($resultado);
    }
}