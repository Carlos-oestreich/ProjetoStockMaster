<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: "tb_fornecedor")]
class Fornecedor extends GenericModel {

    #[ORM\Column(name: "nome_fornecedor", type: "string")]
    private $nome;

    #[ORM\Column(name: "cnpj_fornecedor", type: "string")]
    private $cnpj;

    #[ORM\Column(name: "email_fornecedor", type: "string")]
    private $email;

    #[ORM\Column(name: "telefone_fornecedor", type: "string")]
    private $telefone;

    #[ORM\Column(name: "ativo_fornecedor", type: "boolean")]
    private $ativo;

    #[ORM\OneToMany(mappedBy: "fornecedor", targetEntity: Produto::class)]
    private $produtos;

    #[ORM\ManyToOne(targetEntity: Empresa::class)]
    #[ORM\JoinColumn(name: "empresa_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $empresa;

    public function __construct() {
        $this->produtos = new ArrayCollection();
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
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param mixed $cnpj
     */
    public function setCnpj($cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone): void
    {
        $this->telefone = $telefone;
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

    public function getProdutos(): ArrayCollection
    {
        return $this->produtos;
    }

    public function setProdutos(ArrayCollection $produtos): void
    {
        $this->produtos = $produtos;
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