<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $setor;
    private $login;
    private $senha;
    private $idsupervisor;

    public function __construct($nome, $email, $setor, $login, $senha, $idsupervisor = null, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->setor = $setor;
        $this->login = $login;
        $this->senha = $senha;
        $this->idsupervisor = $idsupervisor;
    }

    // ======= Getters e Setters =======
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSetor() {
        return $this->setor;
    }
    public function setSetor($setor) {
        $this->setor = $setor;
    }

    public function getLogin() {
        return $this->login;
    }
    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getIdSupervisor() {
        return $this->idsupervisor;
    }
    public function setIdSupervisor($idsupervisor) {
        $this->idsupervisor = $idsupervisor;
    }
}
?>
