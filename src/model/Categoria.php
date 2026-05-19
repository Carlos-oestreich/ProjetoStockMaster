<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_categoria")]
class Categoria extends GenericModel {

    #[ORM\Column(name: "nome_categoria", type: "string")]
    private $nome;

    #[ORM\Column(name: "descricao_categoria", type: "string")]
    private $descricao;

    #[ORM\Column(name: "setor_categoria", type: "string")]
    private $setor;

    #[ORM\Column(name: "codigo_interno_categoria", type: "string")]
    private $codigoInterno;

    #[ORM\Column(name: "ativo_categoria", type: "boolean")]
    private $ativo;

    #[ORM\ManyToOne(targetEntity: Empresa::class)]
    #[ORM\JoinColumn(name: "empresa_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $empresa;

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
    public function getSetor()
    {
        return $this->setor;
    }

    /**
     * @param mixed $setor
     */
    public function setSetor($setor): void
    {
        $this->setor = $setor;
    }

    /**
     * @return mixed
     */
    public function getCodigoInterno()
    {
        return $this->codigoInterno;
    }

    /**
     * @param mixed $codigoInterno
     */
    public function setCodigoInterno($codigoInterno): void
    {
        $this->codigoInterno = $codigoInterno;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa($empresa): void
    {
        $this->empresa = $empresa;
    }


}