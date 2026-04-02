<?php

use CarlosELarissa\Stockmaster\dao\CategoriaDAO;
use CarlosELarissa\Stockmaster\model\Categoria;
use PHPUnit\Framework\TestCase;

class CategoriaDAOTest extends TestCase
{
    public function testInserir()
    {
        $categoria = new Categoria();
        $categoria->setNome("Eletronicos");
        $categoria->setDescricao("categoria de eletronicos");
        $categoria->setSetor("tecnologia");
        $categoria->setCodigoInterno("CAT001");
        $categoria->setAtivo(true);

        $categoriaIserida = CategoriaDAO::salvar($categoria);

        $this->assertNotNull($categoriaIserida->getId());
    }

    public function testListar(){
        $categorias = CategoriaDAO::listar();

        foreach ($categorias as $categoria){
            echo $categoria->getNome() . "\n";
        }

        $this->assertNotNull($categorias);
    }

    public function testBuscarPorId(){

        $categoria = new Categoria();
        $categoria->setNome("Papelaria");
        $categoria->setDescricao("itens de papelaria");
        $categoria->setSetor("Escritorio");
        $categoria->setCodigoInterno("CAT010");
        $categoria->setAtivo(true);

        $categoria = categoriaDAO::salvar($categoria);

        $resultado = CategoriaDAO::buscarPorId($categoria->getId());

        $this->assertNotNull($categoria);
        $this->assertEquals("Papelaria", $resultado->getNome());
    }

    public function testAtualizar(){

        $categoria = new Categoria();
        $categoria->setNome("Limpeza");
        $categoria->setDescricao("Produtos de limpeza");
        $categoria->setSetor("Servico");
        $categoria->setCodigoInterno("CAT020");
        $categoria->setAtivo(true);

        $categoria = CategoriaDAO::salvar($categoria);

        $categoria->setNome("Limpeza Atualizada");
        $categoria->setDescricao("Produtos de limpeza Atualizado");

        $categoriaAtualizada = categoriaDAO::atualizar($categoria);

        $this->assertEquals("Limpeza Atualizada", $categoriaAtualizada->getNome());
        $this->assertEquals("Produtos de limpeza Atualizado", $categoriaAtualizada->getDescricao());
    }

    public function testDeletar(){
        $categoria = new Categoria();
        $categoria->setNome("Temporaria");
        $categoria->setDescricao("Excluir");
        $categoria->setSetor("Teste");
        $categoria->setCodigoInterno("CAT030");
        $categoria->setAtivo(true);

        $categoria = CategoriaDAO::salvar($categoria);

        $resultado = CategoriaDAO::deletar($categoria->getId());

        $this->assertTrue($resultado);
    }

    public function testBuscarPorNome()
    {
        $categoria = new Categoria();
        $categoria->setNome("Eletrônicos Busca");
        $categoria->setDescricao("Busca por nome");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT040");
        $categoria->setAtivo(true);

        CategoriaDAO::salvar($categoria);

        $resultado = CategoriaDAO::buscarPorNome("Eletrônicos Busca");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorSetor()
    {
        $categoria = new Categoria();
        $categoria->setNome("Setor Teste");
        $categoria->setDescricao("Busca por setor");
        $categoria->setSetor("Almoxarifado");
        $categoria->setCodigoInterno("CAT050");
        $categoria->setAtivo(true);

        CategoriaDAO::salvar($categoria);

        $resultado = CategoriaDAO::buscarPorSetor("Almoxarifado");

        $this->assertNotNull($resultado);
    }

    public function testListarAtivos()
    {
        $resultado = CategoriaDAO::listarAtivos();

        $this->assertNotNull($resultado);
    }



}