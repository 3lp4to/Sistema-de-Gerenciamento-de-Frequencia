<?php

include_once "../Model/Registro.php";

class RegistroDAO
{
    private $conexao;

    public function __construct()
    {
        include_once '../Conexao/Conexao.php';
        $this->conexao = Conexao::getConexao();
    }

    public function cadastrarRegistro(Registro $registro)
    {

        $stmt = $this->conexao->prepare("
        INSERT INTO registros (idusuario, horaChegada, dataRegistro)
        VALUES (:idusuario, :horaChegada, :dataRegistro)");

        $stmt->bindValue(':idusuario', $registro->getIdUsuario());
        $stmt->bindValue(':horaChegada', $registro->getHoraChegada());
        $stmt->bindValue(':dataRegistro', $registro->getDataRegistro());

        return $stmt->execute();
    }
public function registrarSaida(Registro $registro)
{
    $horaChegada = new DateTime($registro->getHoraChegada());
    $horaSaida = new DateTime($registro->getHoraSaida());
    $intervalo = $horaChegada->diff($horaSaida);
    $horasTrabalhadas = $intervalo->format('%H:%I:%S');

    $stmt = $this->conexao->prepare("
        UPDATE registros 
        SET horaSaida = :horaSaida,
            horas_trabalhadas = :horas_trabalhadas
        WHERE idusuario = :idusuario 
          AND dataRegistro = :dataRegistro
          AND horaSaida IS NULL
    ");

    $stmt->bindValue(':horaSaida', $registro->getHoraSaida());
    $stmt->bindValue(':horas_trabalhadas', $horasTrabalhadas);
    $stmt->bindValue(':idusuario', $registro->getIdUsuario());
    $stmt->bindValue(':dataRegistro', $registro->getDataRegistro());

    return $stmt->execute();
}
public function buscarUltimoRegistro($idUsuario)
{
    $stmt = $this->conexao->prepare("
        SELECT * FROM registros
        WHERE idusuario = :idusuario
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->bindValue(':idusuario', $idUsuario);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function buscarRegistrosPorMes($idUsuario, $mes, $ano)
{
    $sql = "SELECT horaChegada, horaSaida, horas_trabalhadas, dataRegistro
            FROM registros
            WHERE idusuario = :idusuario
              AND MONTH(dataRegistro) = :mes
              AND YEAR(dataRegistro) = :ano
            ORDER BY dataRegistro ASC";

    $stmt = $this->conexao->prepare($sql);
    $stmt->bindValue(':idusuario', $idUsuario);
    $stmt->bindValue(':mes', $mes);
    $stmt->bindValue(':ano', $ano);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
