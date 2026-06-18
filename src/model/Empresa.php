<?php

namespace model;

use dao\GenericDAO;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_empresa")]

class Empresa extends GenericModel
{



    #[ORM\Column(name: "nome_empresa", type: "string")]
    private $nome;

    #[ORM\Column(name: "cnpj_empresa", type: "string", nullable: true)]
    private $cnpj;

    #[ORM\Column(name: "email_empresa", type: "string", nullable: true)]
    private $email;

    #[ORM\Column(name: "telefone_empresa", type: "string", nullable: true)]
    private $telefone;

    #[ORM\Column(name: "endereco_empresa", type: "string", nullable: true)]
    private $endereco;

    #[ORM\Column(name: "logo_empresa", type: "string", nullable: true)]
    private $logo;

    public function getNome(): ?string
    {
        return $this->nome;
    }
    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }
    public function setCnpj($cnpj): void
    {
        $this->cnpj = $cnpj ?: null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail($email): void
    {
        $this->email = $email ?: null;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }
    public function setTelefone($telefone): void
    {
        $this->telefone = $telefone ?: null;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }
    public function setEndereco($endereco): void
    {
        $this->endereco = $endereco ?: null;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }
    public function setLogo($logo): void
    {
        $this->logo = $logo ?: null;
    }

}