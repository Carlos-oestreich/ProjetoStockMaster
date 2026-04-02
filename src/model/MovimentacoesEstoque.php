<?php

namespace CarlosELarissa\Stockmaster\model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_movimentacao_estoque")]
class MovimentacoesEstoque extends GenericModel {

    #[ORM\Column(name: "tipo_movimentacao", type: "string")]
    private $tipo;

    #[ORM\Column(name: "quantidade_movimentacao", type: "integer")]
    private $quantidade;

    #[ORM\Column(name: "observacao_movimentacao", type: "string")]
    private $observacao;

    #[ORM\Column(name: "data_movimentacao", type: "datetime")]
    private $dataMovimentacao;

    #[ORM\Column(name: "saldo_anterior_movimentacao", type: "integer")]
    private $saldoAnterior;

    #[ORM\Column(name: "saldo_atual_movimentacao", type: "integer")]
    private $saldoAtual;

    #[ORM\ManyToOne(targetEntity: Produto::class)]
    #[ORM\JoinColumn(name: "produto_movimentacao")]
    private $produto;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: "usuario_movimentacao")]
    private $usuario;

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo): void
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param mixed $quantidade
     */
    public function setQuantidade($quantidade): void
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return mixed
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param mixed $observacao
     */
    public function setObservacao($observacao): void
    {
        $this->observacao = $observacao;
    }

    /**
     * @return mixed
     */
    public function getDataMovimentacao()
    {
        return $this->dataMovimentacao;
    }

    /**
     * @param mixed $dataMovimentacao
     */
    public function setDataMovimentacao($dataMovimentacao): void
    {
        $this->dataMovimentacao = $dataMovimentacao;
    }

    /**
     * @return mixed
     */
    public function getSaldoAnterior()
    {
        return $this->saldoAnterior;
    }

    /**
     * @param mixed $saldoAnterior
     */
    public function setSaldoAnterior($saldoAnterior): void
    {
        $this->saldoAnterior = $saldoAnterior;
    }

    /**
     * @return mixed
     */
    public function getSaldoAtual()
    {
        return $this->saldoAtual;
    }

    /**
     * @param mixed $saldoAtual
     */
    public function setSaldoAtual($saldoAtual): void
    {
        $this->saldoAtual = $saldoAtual;
    }

    /**
     * @return mixed
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param mixed $produto
     */
    public function setProduto($produto): void
    {
        $this->produto = $produto;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario): void
    {
        $this->usuario = $usuario;
    }





}