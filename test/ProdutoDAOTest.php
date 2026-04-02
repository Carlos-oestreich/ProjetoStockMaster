<?php

use CarlosELarissa\Stockmaster\dao\CategoriaDAO;
use CarlosELarissa\Stockmaster\dao\FornecedorDAO;
use CarlosELarissa\Stockmaster\dao\ProdutoDAO;
use CarlosELarissa\Stockmaster\model\Categoria;
use CarlosELarissa\Stockmaster\model\Fornecedor;
use CarlosELarissa\Stockmaster\model\Produto;
use PHPUnit\Framework\TestCase;

class ProdutoDAOTest extends TestCase
{

    public function testInserir()
    {
        $categoria = new Categoria();
        $categoria->setNome("Informática");
        $categoria->setDescricao("Produtos de informática");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT002");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Tech");
        $fornecedor->setCnpj("98765432000188");
        $fornecedor->setEmail("tech@email.com");
        $fornecedor->setTelefone("46988888888");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $produto = new Produto();
        $produto->setSku("SKU001");
        $produto->setNome("Notebook");
        $produto->setDescricao("Notebook para testes");
        $produto->setPreco(3500.00);
        $produto->setMarca("Dell");
        $produto->setQuantidadeEstoque(10);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new DateTime("2025-03-31 10:00:00"));
        $produto->setCategorias($categoria);
        $produto->setFornecedores($fornecedor);

        $produtoInserido = ProdutoDAO::salvar($produto);

        $this->assertNotNull($produtoInserido->getId());
    }

    public function testListar()
    {
        $produtos = ProdutoDAO::listar();

        foreach ($produtos as $produto){
            echo $produto->getNome() . "\n";
        }

        $this->assertNotNull($produtos);
    }

    public function testBuscarPorId()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Busca ID");
        $categoria->setDescricao("Categoria para produto");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT060");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Busca ID");
        $fornecedor->setCnpj("77766655500011");
        $fornecedor->setEmail("fornecedorid@email.com");
        $fornecedor->setTelefone("46966666661");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $produto = new Produto();
        $produto->setSku("SKU010");
        $produto->setNome("Produto ID");
        $produto->setDescricao("Produto teste id");
        $produto->setPreco(100.00);
        $produto->setMarca("MarcaID");
        $produto->setQuantidadeEstoque(5);
        $produto->setQuantidadeMinima(1);
        $produto->setDataCadastro(new DateTime("2025-03-31 14:00:00"));
        $produto->setCategorias($categoria);
        $produto->setFornecedores($fornecedor);

        $produto = ProdutoDAO::salvar($produto);

        $resultado = ProdutoDAO::buscarPorId($produto->getId());

        $this->assertNotNull($resultado);
        $this->assertEquals("Produto ID", $resultado->getNome());
    }

    public function testAtualizar()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Atualizar");
        $categoria->setDescricao("Categoria atualizar");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT061");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Atualizar");
        $fornecedor->setCnpj("77766655500012");
        $fornecedor->setEmail("fornecedoratualizar@email.com");
        $fornecedor->setTelefone("46966666662");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $produto = new Produto();
        $produto->setSku("SKU011");
        $produto->setNome("Produto Antigo");
        $produto->setDescricao("Descricao antiga");
        $produto->setPreco(200.00);
        $produto->setMarca("MarcaAntiga");
        $produto->setQuantidadeEstoque(8);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new DateTime("2025-03-31 15:00:00"));
        $produto->setCategorias($categoria);
        $produto->setFornecedores($fornecedor);

        $produto = ProdutoDAO::salvar($produto);

        $produto->setNome("Produto Atualizado");
        $produto->setPreco(250.00);

        $produtoAtualizado = ProdutoDAO::atualizar($produto);

        $this->assertEquals("Produto Atualizado", $produtoAtualizado->getNome());
        $this->assertEquals(250.00, $produtoAtualizado->getPreco());
    }

    public function testDeletar()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Excluir");
        $categoria->setDescricao("Categoria excluir");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT062");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Excluir Produto");
        $fornecedor->setCnpj("77766655500013");
        $fornecedor->setEmail("fornecedorexcluir@email.com");
        $fornecedor->setTelefone("46966666663");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $produto = new Produto();
        $produto->setSku("SKU012");
        $produto->setNome("Produto Excluir");
        $produto->setDescricao("Produto excluir");
        $produto->setPreco(300.00);
        $produto->setMarca("MarcaExcluir");
        $produto->setQuantidadeEstoque(10);
        $produto->setQuantidadeMinima(3);
        $produto->setDataCadastro(new DateTime("2025-03-31 16:00:00"));
        $produto->setCategorias($categoria);
        $produto->setFornecedores($fornecedor);

        $produto = ProdutoDAO::salvar($produto);

        $resultado = ProdutoDAO::deletar($produto->getId());

        $this->assertTrue($resultado);
    }

    public function testBuscarPortNome()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Busca Nome");
        $categoria->setDescricao("Categoria nome");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT063");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Busca Nome");
        $fornecedor->setCnpj("77766655500014");
        $fornecedor->setEmail("fornecedornome@email.com");
        $fornecedor->setTelefone("46966666664");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $produto = new Produto();
        $produto->setSku("SKU013");
        $produto->setNome("Notebook Busca");
        $produto->setDescricao("Busca por nome");
        $produto->setPreco(400.00);
        $produto->setMarca("MarcaBusca");
        $produto->setQuantidadeEstoque(12);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new DateTime("2025-03-31 17:00:00"));
        $produto->setCategorias($categoria);
        $produto->setFornecedores($fornecedor);

        ProdutoDAO::salvar($produto);

        $resultado = ProdutoDAO::buscarPortNome("Notebook Busca");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorSku()
    {
        $resultado = ProdutoDAO::buscarPorSku("SKU001");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorMarca()
    {
        $resultado = ProdutoDAO::buscarPorMarca("Dell");

        $this->assertNotNull($resultado);
    }

    public function testListarEstoqueBaixo()
    {
        $resultado = ProdutoDAO::listarEstoqueBaixo();

        $this->assertNotNull($resultado);
    }
}