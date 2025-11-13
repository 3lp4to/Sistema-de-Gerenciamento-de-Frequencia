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

    public function __construct($nome, $email, $setor, $login, $senha, $tipo = 'bolsista', $idsupervisor = null, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->setor = $setor;
        $this->login = $login;
        $this->senha = $this->setSenha($senha); // Criptografa a senha
        $this->tipo = $tipo;
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
        // Validação do formato de email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new Exception('Email inválido');
        }
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
        // Validação do formato do login (opcional)
        if (strlen($login) >= 5) {
            $this->login = $login;
        } else {
            throw new Exception('Login deve ter no mínimo 5 caracteres');
        }
    }

    public function getSenha() {
        return $this->senha;
    }

    // Senha criptografada ao ser setada
    public function setSenha($senha) {
        if (strlen($senha) >= 6) {
            return password_hash($senha, PASSWORD_DEFAULT);
        } else {
            throw new Exception('A senha deve ter no mínimo 6 caracteres');
        }
    }

    public function getIdSupervisor() {
        return $this->idsupervisor;
    }
    public function setIdSupervisor($idsupervisor) {
        $this->idsupervisor = $idsupervisor;
    }

    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    // Método para verificar se a senha fornecida é válida
    public function verificarSenha($senha) {
        return password_verify($senha, $this->senha);
    }
}
?>
