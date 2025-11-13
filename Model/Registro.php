<?php
class Registro {
    private $id;
    private $idusuario;
    private $horaChegada;
    private $horaSaida;
    private $dataRegistro;
    private $horas_trabalhadas;

    public function __construct($idusuario, $horaChegada, $horaSaida, $dataRegistro, $horas_trabalhadas = null, $id = null) {
        $this->id = $id;
        $this->idusuario = $idusuario;
        $this->horaChegada = $horaChegada;
        $this->horaSaida = $horaSaida;
        $this->dataRegistro = $dataRegistro;
        $this->horas_trabalhadas = $horas_trabalhadas;
    }

    // ======= Getters e Setters =======
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getIdUsuario() {
        return $this->idusuario;
    }
    public function setIdUsuario($idusuario) {
        $this->idusuario = $idusuario;
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

    public function getHorasTrabalhadas() {
        return $this->horas_trabalhadas;
    }
    public function setHorasTrabalhadas($horas_trabalhadas) {
        $this->horas_trabalhadas = $horas_trabalhadas;
    }
}
?>
