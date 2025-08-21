<?php
class Registro {
    private $id;
    private $idUsuario;
    private $horaChegada;
    private $horaSaida;
    private $dataRegistro;
    
    public function __construct($idUsuario, $horaChegada = null, $horaSaida = null, $dataRegistro = null, $id = null) {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->horaChegada = $horaChegada;
        $this->horaSaida = $horaSaida;
        $this->dataRegistro = $dataRegistro;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getHoraChegada() {
        return $this->horaChegada;
    }
    public function setHoraChegada($horaChegada) {
        $this->horaChegada = $horaChegada;
    }

    public function getHoraSaida() {
        return $this->horaSaida;
    }
    public function setHoraSaida($horaSaida) {
        $this->horaSaida = $horaSaida;
    }

    public function getDataRegistro() {
        return $this->dataRegistro;
    }
    public function setDataRegistro($dataRegistro) {
        $this->dataRegistro = $dataRegistro;
    }
}
?>
