-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS pontoeletronico;
USE pontoeletronico;

-- Criação da tabela 'justificativas'
CREATE TABLE `justificativas` (
  `idjust` INT(11) NOT NULL AUTO_INCREMENT,
  `idusuario` INT(11) DEFAULT NULL,
  `texto` VARCHAR(150) DEFAULT NULL,
  `data_envio` DATE DEFAULT NULL,
  PRIMARY KEY (`idjust`),
  KEY `idusuario` (`idusuario`),
  CONSTRAINT `justificativas_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Inserção de dados na tabela 'justificativas'
INSERT INTO `justificativas` (`idjust`, `idusuario`, `texto`, `data_envio`) VALUES
(1, 4, 'doente', '2025-11-13');

-- Criação da tabela 'registros'
CREATE TABLE `registros` (
  `idregistro` INT(11) NOT NULL AUTO_INCREMENT,
  `idusuario` INT(11) NOT NULL,
  `horaChegada` TIME DEFAULT NULL,
  `dataRegistro` DATE DEFAULT NULL,
  `horaSaida` TIME DEFAULT NULL,
  `horas_trabalhadas` TIME DEFAULT NULL,
  PRIMARY KEY (`idregistro`),
  KEY `idusuario` (`idusuario`),
  CONSTRAINT `registros_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Inserção de dados na tabela 'registros'
INSERT INTO `registros` (`idregistro`, `idusuario`, `horaChegada`, `dataRegistro`, `horaSaida`, `horas_trabalhadas`) VALUES
(1, 4, '14:51:28', '2025-11-13', '14:51:39', '00:00:11'),
(2, 4, '15:37:51', '2025-11-13', '15:37:56', '00:00:05'),
(3, 4, '16:06:26', '2025-11-13', '16:06:31', '00:00:05');

-- Criação da tabela 'usuario'
CREATE TABLE `usuario` (
  `idusuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `setor` VARCHAR(50) NOT NULL,
  `login` VARCHAR(50) NOT NULL,
  `senha` VARCHAR(60) DEFAULT NULL,
  `idsupervisor` INT(11) DEFAULT NULL,
  `tipo` ENUM('admin', 'supervisor', 'bolsista') NOT NULL DEFAULT 'bolsista',
  PRIMARY KEY (`idusuario`),
  KEY `idsupervisor` (`idsupervisor`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idsupervisor`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Inserção de dados na tabela 'usuario'
INSERT INTO `usuario` (`idusuario`, `nome`, `email`, `setor`, `login`, `senha`, `idsupervisor`, `tipo`) VALUES
(4, 'joao', 'teste@teste.com', 'CIET', '123', '123', NULL, 'bolsista');
