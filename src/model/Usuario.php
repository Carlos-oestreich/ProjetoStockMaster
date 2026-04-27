<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "tb_usuario")]
class Usuario extends GenericModel {

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
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha): void
    {
        $this->senha = $senha;
    }

    /**
     * @return mixed
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * @param mixed $perfil
     */
    public function setPerfil($perfil): void
    {
        $this->perfil = $perfil;
    }

    /**
     * @return mixed
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @param mixed $matricula
     */
    public function setMatricula($matricula): void
    {
        $this->matricula = $matricula;
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





}