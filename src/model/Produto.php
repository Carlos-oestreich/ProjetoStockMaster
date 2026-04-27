<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_produto")]

class Produto extends GenericModel {

    #[ORM\Column(name: "sku_produto", type: "string")]
    private $sku;

    #[ORM\Column(name: "nome_produto", type: "string")]
    private $nome;

    #[ORM\Column(name: "descricao_produto", type: "string")]
    private $descricao;

    #[ORM\Column(name: "preco_produto", type: "decimal", precision: 10, scale: 2)]
    private $preco;

    #[ORM\Column(name: "marca_produto", type: "string")]
    private $marca;

    #[ORM\Column(name: "quantidade_estoque_produto", type: "integer")]
    private $quantidadeEstoque;

    #[ORM\Column(name: "quantidade_minima_produto", type: "integer")]
    private $quantidadeMinima;

    #[ORM\Column(name: "data_cadastro_produto", type: "datetime")]
    private $dataCadastro;

    #[ORM\ManyToOne(targetEntity: Categoria::class)]
    #[ORM\JoinColumn(name: "categoria_produto")]
    private $categoria;

    #[ORM\ManyToOne(targetEntity: Fornecedor::class, inversedBy: "produtos")]
    #[ORM\JoinColumn(name: "fornecedor_produto")]
    private $fornecedore;

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getPreco()
    {
        return $this->preco;
    }

    /**
     * @param mixed $preco
     */
    public function setPreco($preco): void
    {
        $this->preco = $preco;
    }

    /**
     * @return mixed
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * @param mixed $marca
     */
    public function setMarca($marca): void
    {
        $this->marca = $marca;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeEstoque()
    {
        return $this->quantidadeEstoque;
    }

    /**
     * @param mixed $quantidadeEstoque
     */
    public function setQuantidadeEstoque($quantidadeEstoque): void
    {
        $this->quantidadeEstoque = $quantidadeEstoque;
    }

    /**
     * @return mixed
     */
    public function getQuantidadeMinima()
    {
        return $this->quantidadeMinima;
    }

    /**
     * @param mixed $quantidadeMinima
     */
    public function setQuantidadeMinima($quantidadeMinima): void
    {
        $this->quantidadeMinima = $quantidadeMinima;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    /**
     * @param mixed $dataCadastro
     */
    public function setDataCadastro($dataCadastro): void
    {
        $this->dataCadastro = $dataCadastro;
    }

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return mixed
     */
    public function getFornecedor()
    {
        return $this->fornecedor;
    }

    /**
     * @param mixed $fornecedor
     */
    public function setFornecedor($fornecedor): void
    {
        $this->fornecedor = $fornecedor;
    }


}