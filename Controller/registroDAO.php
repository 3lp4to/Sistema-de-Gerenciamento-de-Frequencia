<?php

include_once "../model/Registro.php";

class RegistroDAO
{
    private $conexao;

    public function __construct()
    {
        include_once '../conexao/conexao.php';
        $this->conexao = Conexao::getConexao();
    }

    /**
     * Cadastra o registro de entrada do usuário (hora de chegada)
     */
    public function cadastrarRegistro(Registro $registro)
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO registros (idusuario, horaChegada, dataRegistro)
                VALUES (:idusuario, :horaChegada, :dataRegistro)
            ");

            $stmt->bindValue(':idusuario', $registro->getIdUsuario(), PDO::PARAM_INT);
            $stmt->bindValue(':horaChegada', $registro->getHoraChegada());
            $stmt->bindValue(':dataRegistro', $registro->getDataRegistro());

            return $stmt->execute();
        } catch (PDOException $e) {
            // Aqui você pode tratar a exceção de forma adequada
            echo "Erro ao cadastrar o registro de entrada: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Registra a saída do usuário e calcula as horas trabalhadas
     */
    public function registrarSaida(Registro $registro)
    {
        try {
            $ultimoRegistro = $this->buscarUltimoRegistro($registro->getIdUsuario());

            if (!$ultimoRegistro || $ultimoRegistro['horaSaida'] !== null) {
                return false; 
            }

            
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
            $stmt->bindValue(':idusuario', $registro->getIdUsuario(), PDO::PARAM_INT);
            $stmt->bindValue(':dataRegistro', $registro->getDataRegistro());

            return $stmt->execute();
        } catch (PDOException $e) {
            // Tratamento de erro
            echo "Erro ao registrar saída: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca o último registro do usuário (para verificar se ele já bateu o ponto)
     */
    public function buscarUltimoRegistro($idUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT * FROM registros
                WHERE idusuario = :idusuario
                ORDER BY idregistro DESC
                LIMIT 1
            ");
            $stmt->bindValue(':idusuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Tratar erro de consulta
            echo "Erro ao buscar o último registro: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Retorna todos os registros de um mês específico de um usuário
     */
    public function buscarRegistrosPorMes($idUsuario, $mes, $ano)
    {
        try {
            $sql = "
                SELECT horaChegada, horaSaida, horas_trabalhadas, dataRegistro
                FROM registros
                WHERE idusuario = :idusuario
                  AND MONTH(dataRegistro) = :mes
                  AND YEAR(dataRegistro) = :ano
                ORDER BY dataRegistro ASC
            ";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':idusuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
            $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Tratar erro de consulta
            echo "Erro ao buscar registros por mês: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Calcula o total de horas trabalhadas no mês
     */
    public function calcularTotalHorasMes($idUsuario, $mes, $ano)
    {
        try {
            $sql = "
                SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas_trabalhadas))) AS total
                FROM registros
                WHERE idusuario = :idusuario
                  AND MONTH(dataRegistro) = :mes
                  AND YEAR(dataRegistro) = :ano
            ";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':idusuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
            $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? '00:00:00';
        } catch (PDOException $e) {
            // Tratar erro de cálculo de total de horas
            echo "Erro ao calcular total de horas: " . $e->getMessage();
            return '00:00:00';
        }
    }
}
