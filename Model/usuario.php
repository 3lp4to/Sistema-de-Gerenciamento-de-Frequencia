<?php
class Usuario {
    private $id;
    private $nome;
    private $email;
    private $setor;
    private $login;
    private $senha;
    private $idsupervisor;
    private $tipo; // Tipo de usuário: 'admin', 'supervisor', 'bolsista'

    // Coloque $tipo antes de $idsupervisor
  public function __construct($nome, $email, $setor, $login, $senha, $tipo = 'bolsista', $idsupervisor = null, $id = null) {
    $this->id = $id;
    $this->nome = $nome;
    $this->email = $email;
    $this->setor = $setor;
    $this->login = $login;
    $this->senha = $senha; // texto puro, sem hash
    $this->tipo = $tipo;
    $this->idsupervisor = $idsupervisor;
}



    // ======= Getters e Setters =======
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('Email inválido');
        }
    }

    public function getSetor() { return $this->setor; }
    public function setSetor($setor) { $this->setor = $setor; }

    public function getLogin() { return $this->login; }
    public function setLogin($login) {
        if (strlen($login) >= 1) {
            $this->login = $login;
        } else {
            throw new Exception('Login inválido');
        }
    }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function getIdSupervisor() { return $this->idsupervisor; }
    public function setIdSupervisor($idsupervisor) { $this->idsupervisor = $idsupervisor; }

    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }

    public function verificarSenha($senha) { return $this->senha === $senha; }
}
?>
