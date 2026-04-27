<?php

use dao\CategoriaDAO;
use dao\FornecedorDAO;
use dao\MovimentacaoEstoqueDAO;
use dao\ProdutoDAO;
use dao\UsuarioDAO;
use model\Categoria;
use model\Fornecedor;
use model\MovimentacoesEstoque;
use model\Produto;
use model\Usuario;
use PHPUnit\Framework\TestCase;

class MovimentacaoDAOTest extends TestCase
{
    public function testInserir()
    {
        $categoria = new Categoria();
        $categoria->setNome("Acessórios");
        $categoria->setDescricao("Categoria de acessórios");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT004");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Teste");
        $fornecedor->setCnpj("55566677000188");
        $fornecedor->setEmail("teste@email.com");
        $fornecedor->setTelefone("46966666666");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $usuario = new Usuario();
        $usuario->setNome("Administrador");
        $usuario->setEmail("admin@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("ADMIN");
        $usuario->setMatricula("2025002");
        $usuario->setAtivo(true);
        $usuario = UsuarioDAO::salvar($usuario);

        $produto = new Produto();
        $produto->setSku("SKU003");
        $produto->setNome("Mouse Gamer");
        $produto->setDescricao("Mouse para testes");
        $produto->setPreco(150.00);
        $produto->setMarca("Logitech");
        $produto->setQuantidadeEstoque(20);
        $produto->setQuantidadeMinima(5);
        $produto->setDataCadastro(new DateTime("2025-03-31 12:00:00"));
        $produto->setCategoria($categoria);
        $produto->setFornecedor($fornecedor);
        $produto = ProdutoDAO::salvar($produto);

        $movimentacao = new MovimentacoesEstoque();
        $movimentacao->setTipo("ENTRADA");
        $movimentacao->setQuantidade(5);
        $movimentacao->setObservacao("Entrada de teste");
        $movimentacao->setDataMovimentacao(new DateTime("2025-03-31 13:00:00"));
        $movimentacao->setSaldoAnterior(20);
        $movimentacao->setSaldoAtual(25);
        $movimentacao->setProduto($produto);
        $movimentacao->setUsuario($usuario);

        $movimentacaoInserida = MovimentacaoEstoqueDAO::salvar($movimentacao);

        $this->assertNotNull($movimentacaoInserida->getId());
    }

    public function testListar()
    {
        $movimentacoes = MovimentacaoEstoqueDAO::listar();

        foreach ($movimentacoes as $movimentacao){
            echo $movimentacao->getTipo() . "\n";
        }

        $this->assertNotNull($movimentacoes);
    }

    public function testBuscarPorId()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Mov ID");
        $categoria->setDescricao("Categoria para mov");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT070");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Mov ID");
        $fornecedor->setCnpj("88877766600011");
        $fornecedor->setEmail("fornecedormovid@email.com");
        $fornecedor->setTelefone("46977777770");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $usuario = new Usuario();
        $usuario->setNome("Usuario Mov ID");
        $usuario->setEmail("usuariomovid@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025020");
        $usuario->setAtivo(true);
        $usuario = UsuarioDAO::salvar($usuario);

        $produto = new Produto();
        $produto->setSku("SKU020");
        $produto->setNome("Produto Mov ID");
        $produto->setDescricao("Produto mov id");
        $produto->setPreco(120.00);
        $produto->setMarca("MarcaMov");
        $produto->setQuantidadeEstoque(9);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new DateTime("2025-03-31 18:00:00"));
        $produto->setCategoria($categoria);
        $produto->setFornecedor($fornecedor);
        $produto = ProdutoDAO::salvar($produto);

        $movimentacao = new MovimentacoesEstoque();
        $movimentacao->setTipo("SAIDA");
        $movimentacao->setQuantidade(2);
        $movimentacao->setObservacao("Teste buscar por id");
        $movimentacao->setDataMovimentacao(new DateTime("2025-03-31 19:00:00"));
        $movimentacao->setSaldoAnterior(9);
        $movimentacao->setSaldoAtual(7);
        $movimentacao->setProduto($produto);
        $movimentacao->setUsuario($usuario);
        $movimentacao = MovimentacaoEstoqueDAO::salvar($movimentacao);

        $resultado = MovimentacaoEstoqueDAO::buscarPorId($movimentacao->getId());

        $this->assertNotNull($resultado);
        $this->assertEquals("SAIDA", $resultado->getTipo());
    }

    public function testAtualizar()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Mov Atualizar");
        $categoria->setDescricao("Categoria atualizar mov");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT071");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Mov Atualizar");
        $fornecedor->setCnpj("88877766600012");
        $fornecedor->setEmail("fornecedormovatualizar@email.com");
        $fornecedor->setTelefone("46977777771");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $usuario = new Usuario();
        $usuario->setNome("Usuario Mov Atualizar");
        $usuario->setEmail("usuariomovatualizar@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025021");
        $usuario->setAtivo(true);
        $usuario = UsuarioDAO::salvar($usuario);

        $produto = new Produto();
        $produto->setSku("SKU021");
        $produto->setNome("Produto Mov Atualizar");
        $produto->setDescricao("Produto mov atualizar");
        $produto->setPreco(130.00);
        $produto->setMarca("MarcaMovA");
        $produto->setQuantidadeEstoque(15);
        $produto->setQuantidadeMinima(3);
        $produto->setDataCadastro(new DateTime("2025-03-31 20:00:00"));
        $produto->setCategoria($categoria);
        $produto->setFornecedor($fornecedor);
        $produto = ProdutoDAO::salvar($produto);

        $movimentacao = new MovimentacoesEstoque();
        $movimentacao->setTipo("ENTRADA");
        $movimentacao->setQuantidade(3);
        $movimentacao->setObservacao("Antes de atualizar");
        $movimentacao->setDataMovimentacao(new DateTime("2025-03-31 21:00:00"));
        $movimentacao->setSaldoAnterior(15);
        $movimentacao->setSaldoAtual(18);
        $movimentacao->setProduto($produto);
        $movimentacao->setUsuario($usuario);
        $movimentacao = MovimentacaoEstoqueDAO::salvar($movimentacao);

        $movimentacao->setQuantidade(4);
        $movimentacao->setObservacao("Depois de atualizar");
        $movimentacao->setSaldoAtual(19);

        $movimentacaoAtualizada = MovimentacaoEstoqueDAO::atualizar($movimentacao);

        $this->assertEquals(4, $movimentacaoAtualizada->getQuantidade());
        $this->assertEquals("Depois de atualizar", $movimentacaoAtualizada->getObservacao());
        $this->assertEquals(19, $movimentacaoAtualizada->getSaldoAtual());
    }

    public function testDeletar()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Mov Excluir");
        $categoria->setDescricao("Categoria excluir mov");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT072");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Mov Excluir");
        $fornecedor->setCnpj("88877766600013");
        $fornecedor->setEmail("fornecedormovexcluir@email.com");
        $fornecedor->setTelefone("46977777772");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $usuario = new Usuario();
        $usuario->setNome("Usuario Mov Excluir");
        $usuario->setEmail("usuariomovexcluir@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025022");
        $usuario->setAtivo(true);
        $usuario = UsuarioDAO::salvar($usuario);

        $produto = new Produto();
        $produto->setSku("SKU022");
        $produto->setNome("Produto Mov Excluir");
        $produto->setDescricao("Produto mov excluir");
        $produto->setPreco(140.00);
        $produto->setMarca("MarcaMovE");
        $produto->setQuantidadeEstoque(11);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new DateTime("2025-03-31 22:00:00"));
        $produto->setCategoria($categoria);
        $produto->setFornecedor($fornecedor);
        $produto = ProdutoDAO::salvar($produto);

        $movimentacao = new MovimentacoesEstoque();
        $movimentacao->setTipo("SAIDA");
        $movimentacao->setQuantidade(1);
        $movimentacao->setObservacao("Excluir movimentacao");
        $movimentacao->setDataMovimentacao(new DateTime("2025-03-31 23:00:00"));
        $movimentacao->setSaldoAnterior(11);
        $movimentacao->setSaldoAtual(10);
        $movimentacao->setProduto($produto);
        $movimentacao->setUsuario($usuario);
        $movimentacao = MovimentacaoEstoqueDAO::salvar($movimentacao);

        $resultado = MovimentacaoEstoqueDAO::deletar($movimentacao->getId());

        $this->assertTrue($resultado);
    }

    public function testBuscarPorTipo()
    {
        $resultado = MovimentacaoEstoqueDAO::buscarPorTipo("ENTRADA");

        $this->assertNotNull($resultado);
    }

    public function testBuscarPorUsuario()
    {
        $categoria = new Categoria();
        $categoria->setNome("Categoria Usuario Busca");
        $categoria->setDescricao("Categoria teste busca usuario");
        $categoria->setSetor("Tecnologia");
        $categoria->setCodigoInterno("CAT080");
        $categoria->setAtivo(true);
        $categoria = CategoriaDAO::salvar($categoria);

        $fornecedor = new Fornecedor();
        $fornecedor->setNome("Fornecedor Usuario Busca");
        $fornecedor->setCnpj("99988877700022");
        $fornecedor->setEmail("fornecedorusuariobusca@email.com");
        $fornecedor->setTelefone("46988888888");
        $fornecedor->setAtivo(true);
        $fornecedor = FornecedorDAO::salvar($fornecedor);

        $usuario = new Usuario();
        $usuario->setNome("Usuário Busca");
        $usuario->setEmail("busca@email.com");
        $usuario->setSenha("123456");
        $usuario->setPerfil("OPERADOR");
        $usuario->setMatricula("2025003");
        $usuario->setAtivo(true);
        $usuario = UsuarioDAO::salvar($usuario);

        $produto = new Produto();
        $produto->setSku("SKU030");
        $produto->setNome("Produto Usuario Busca");
        $produto->setDescricao("Produto teste busca usuario");
        $produto->setPreco(99.90);
        $produto->setMarca("MarcaBusca");
        $produto->setQuantidadeEstoque(10);
        $produto->setQuantidadeMinima(2);
        $produto->setDataCadastro(new \DateTime("2025-03-31 20:00:00"));
        $produto->setCategoria($categoria);
        $produto->setFornecedor($fornecedor);
        $produto = ProdutoDAO::salvar($produto);

        $movimentacao = new MovimentacoesEstoque();
        $movimentacao->setTipo("ENTRADA");
        $movimentacao->setQuantidade(2);
        $movimentacao->setObservacao("Teste buscar por usuário");
        $movimentacao->setDataMovimentacao(new \DateTime("2025-03-31 21:00:00"));
        $movimentacao->setSaldoAnterior(10);
        $movimentacao->setSaldoAtual(12);
        $movimentacao->setProduto($produto);
        $movimentacao->setUsuario($usuario);
        MovimentacaoEstoqueDAO::salvar($movimentacao);

        $resultado = MovimentacaoEstoqueDAO::buscarPorUsuario($usuario);

        $this->assertNotNull($resultado);
    }

    public function testListarComSaldoAtualMenorQue()
    {
        $resultado = MovimentacaoEstoqueDAO::listarComSaldoAtualMenorQue(30);

        $this->assertNotNull($resultado);
    }
}