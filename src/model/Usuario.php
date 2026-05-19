<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_usuario")]
class Usuario extends GenericModel
{
    #[ORM\Column(name: "nome_usuario", type: "string")]
    private $nome;

    #[ORM\Column(name: "email_usuario", type: "string")]
    private $email;

    #[ORM\Column(name: "senha_usuario", type: "string")]
    private $senha;

    #[ORM\Column(name: "perfil_usuario", type: "string")]
    private $perfil;

    #[ORM\Column(name: "matricula_usuario", type: "string")]
    private $matricula;

    #[ORM\Column(name: "ativo_usuario", type: "boolean")]
    private $ativo;

    // TRUE = senha criada pelo adm/dono, usuario deve trocar no primeiro acesso
    #[ORM\Column(name: "senha_temporaria_usuario", type: "boolean", options: ["default" => true])]
    private $senhaTemporaria = true;

    // CPF e documento pessoal do usuario (diferente de CNPJ que e da empresa)
    #[ORM\Column(name: "cpf_usuario", type: "string", nullable: true)]
    private $cpf;

    // Relacionamento: cada usuario pertence a uma empresa
    #[ORM\ManyToOne(targetEntity: Empresa::class)]
    #[ORM\JoinColumn(name: "empresa_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private $empresa;

    public function getNome(): ?string { return $this->nome; }
    public function setNome($nome): void { $this->nome = $nome; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail($email): void { $this->email = $email; }

    public function getSenha(): ?string { return $this->senha; }
    public function setSenha($senha): void { $this->senha = $senha; }

    public function getPerfil(): ?string { return $this->perfil; }
    public function setPerfil($perfil): void { $this->perfil = $perfil; }

    public function getMatricula(): ?string { return $this->matricula; }
    public function setMatricula($matricula): void { $this->matricula = $matricula; }

    public function getAtivo(): ?bool { return $this->ativo; }
    public function setAtivo($ativo): void { $this->ativo = $ativo; }

    public function getSenhaTemporaria(): ?bool { return $this->senhaTemporaria; }
    public function setSenhaTemporaria($senhaTemporaria): void { $this->senhaTemporaria = $senhaTemporaria; }

    public function getCpf(): ?string { return $this->cpf; }
    public function setCpf($cpf): void { $this->cpf = $cpf ?: null; }

    public function getEmpresa(): ?Empresa { return $this->empresa; }
    public function setEmpresa($empresa): void { $this->empresa = $empresa; }
}