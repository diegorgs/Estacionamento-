-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.24-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para estacionamento
CREATE DATABASE IF NOT EXISTS `estacionamento` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `estacionamento`;

-- Copiando estrutura para tabela estacionamento.convenio
CREATE TABLE IF NOT EXISTS `convenio` (
  `convenioID` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`convenioID`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela estacionamento.convenio: ~2 rows (aproximadamente)
INSERT INTO `convenio` (`convenioID`, `nome`, `ativo`) VALUES
	(1, 'Banco Santander', 1),
	(2, 'Banco Bradesco', 1);

-- Copiando estrutura para tabela estacionamento.cor
CREATE TABLE IF NOT EXISTS `cor` (
  `corID` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) NOT NULL DEFAULT '',
  `corHexadecimal` varchar(7) NOT NULL,
  PRIMARY KEY (`corID`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `corHexadecimal` (`corHexadecimal`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela estacionamento.cor: ~0 rows (aproximadamente)
INSERT INTO `cor` (`corID`, `nome`, `corHexadecimal`) VALUES
	(1, 'PRETO', '#00000'),
	(2, 'BRANCO', '#FFFFFF'),
	(3, 'CINZA', '#808080'),
	(4, 'VERMELHO', '#FF0000'),
	(5, 'AZUL', '#0000FF'),
	(6, 'OUTRO', '#??????');

-- Copiando estrutura para tabela estacionamento.estacionamento
CREATE TABLE IF NOT EXISTS `estacionamento` (
  `estacionamentoID` int(11) NOT NULL AUTO_INCREMENT,
  `dataEntrada` datetime NOT NULL,
  `dataSaida` datetime DEFAULT NULL,
  `placa` varchar(7) NOT NULL,
  `tipoID` int(11) NOT NULL,
  `chave` tinyint(4) NOT NULL DEFAULT 0,
  `valorTotal` decimal(8,2) DEFAULT NULL,
  `convenioID` int(11) DEFAULT NULL,
  `mensalista` tinyint(4) DEFAULT NULL,
  `corID` int(11) NOT NULL,
  `observacao` text DEFAULT NULL,
  PRIMARY KEY (`estacionamentoID`),
  KEY `placa` (`placa`),
  KEY `tipoID` (`tipoID`),
  KEY `FK_estacionamento_convenio` (`convenioID`),
  KEY `FK_estacionamento_cor` (`corID`),
  CONSTRAINT `FK_estacionamento_convenio` FOREIGN KEY (`convenioID`) REFERENCES `convenio` (`convenioID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_estacionamento_cor` FOREIGN KEY (`corID`) REFERENCES `cor` (`corID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_estacionamento_tipo` FOREIGN KEY (`tipoID`) REFERENCES `tipo` (`tipoID`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela estacionamento.estacionamento: ~166 rows (aproximadamente)

-- Copiando estrutura para view estacionamento.estacionamentodiario
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `estacionamentodiario` (
	`estacionamentoID` INT(11) NOT NULL,
	`dEntrada` VARCHAR(21) NULL COLLATE 'utf8mb4_general_ci',
	`dSaida` VARCHAR(21) NULL COLLATE 'utf8mb4_general_ci',
	`placa` VARCHAR(7) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nome` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_general_ci',
	`valortotal` DECIMAL(8,2) NULL,
	`STATUS` VARCHAR(9) NOT NULL COLLATE 'utf8mb4_general_ci',
	`Entrada` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci'
) ENGINE=MyISAM;

-- Copiando estrutura para procedure estacionamento.estacionamentoSaida
DELIMITER //
CREATE PROCEDURE `estacionamentoSaida`(IN `id` INT)
BEGIN
	UPDATE estacionamento AS e
	INNER JOIN valortotal AS v ON (e.estacionamentoID = v.estacionamentoID)
	SET e.dataSaida = v.DataHoraAtual,
		e.valorTotal= v.ValorTotal
	WHERE e.estacionamentoID = id;
	
	SELECT estacionamentoID, dataSaida, valorTotal
	FROM estacionamento
	WHERE estacionamentoID = id;
END//
DELIMITER ;

-- Copiando estrutura para view estacionamento.tempoestacionado
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `tempoestacionado` (
	`estacionamentoID` INT(11) NOT NULL,
	`tipoID` INT(11) NOT NULL,
	`DataHoraAtual` DATETIME NOT NULL,
	`dataEntrada` DATETIME NOT NULL,
	`TempoPermanecia` TIME NULL,
	`TempoMinuto` DOUBLE(17,0) NULL
) ENGINE=MyISAM;

-- Copiando estrutura para tabela estacionamento.tipo
CREATE TABLE IF NOT EXISTS `tipo` (
  `tipoID` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) NOT NULL,
  PRIMARY KEY (`tipoID`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela estacionamento.tipo: ~3 rows (aproximadamente)
INSERT INTO `tipo` (`tipoID`, `nome`) VALUES
	(1, 'Carro'),
	(2, 'Moto'),
	(3, 'Van / Caminhonete');

-- Copiando estrutura para tabela estacionamento.valor
CREATE TABLE IF NOT EXISTS `valor` (
  `valorID` int(11) NOT NULL AUTO_INCREMENT,
  `tipoID` int(11) NOT NULL,
  `valorMeiaHora` decimal(12,2) NOT NULL,
  `valorHorasAdicionais` decimal(12,2) NOT NULL,
  `valorDia` decimal(12,2) NOT NULL,
  PRIMARY KEY (`valorID`),
  UNIQUE KEY `tipoID` (`tipoID`),
  CONSTRAINT `FK_valor_tipo` FOREIGN KEY (`tipoID`) REFERENCES `tipo` (`tipoID`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela estacionamento.valor: ~3 rows (aproximadamente)
INSERT INTO `valor` (`valorID`, `tipoID`, `valorMeiaHora`, `valorHorasAdicionais`, `valorDia`) VALUES
	(1, 1, 10.00, 15.00, 80.00),
	(2, 2, 8.00, 12.00, 50.00),
	(3, 3, 20.00, 30.00, 200.00);

-- Copiando estrutura para view estacionamento.valortotal
-- Criando tabela temporária para evitar erros de dependência de VIEW
CREATE TABLE `valortotal` (
	`estacionamentoID` INT(11) NOT NULL,
	`DataHoraAtual` DATETIME NOT NULL,
	`dataEntrada` DATETIME NOT NULL,
	`TempoPermanecia` TIME NULL,
	`Name_exp_5` DECIMAL(12,2) NOT NULL
) ENGINE=MyISAM;

-- Copiando estrutura para view estacionamento.estacionamentodiario
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `estacionamentodiario`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `estacionamentodiario` AS SELECT `e`.`estacionamentoID` AS `estacionamentoID`, date_format(`e`.`dataEntrada`,'%d/%m/%Y %H:%i') AS `dEntrada`, date_format(`e`.`dataSaida`,'%d/%m/%Y %H:%i') AS `dSaida`, `e`.`placa` AS `placa`, `t`.`nome` AS `nome`, `e`.`valorTotal` AS `valortotal`, CASE WHEN `e`.`valorTotal` is null THEN 'Em Aberto' ELSE 'Pago' END AS `STATUS`, date_format(`e`.`dataEntrada`,'%Y-%m-%d') AS `Entrada` FROM (`estacionamento` `e` join `tipo` `t` on(`e`.`tipoID` = `t`.`tipoID`)) ORDER BY `e`.`dataEntrada` ASC ;

-- Copiando estrutura para view estacionamento.tempoestacionado
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `tempoestacionado`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `tempoestacionado` AS SELECT `estacionamentoID` AS `estacionamentoID`, `tipoID` AS `tipoID`, current_timestamp() AS `DataHoraAtual`, `dataEntrada` AS `dataEntrada`, timediff(current_timestamp(),`dataEntrada`) AS `TempoPermanecia`, date_format(timediff(current_timestamp(),`dataEntrada`),'%H') * 60 + date_format(timediff(current_timestamp(),`dataEntrada`),'%i') AS `TempoMinuto` FROM `estacionamento` WHERE `dataSaida` is null ;

-- Copiando estrutura para view estacionamento.valortotal
-- Removendo tabela temporária e criando a estrutura VIEW final
DROP TABLE IF EXISTS `valortotal`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `valortotal` AS SELECT `t`.`estacionamentoID` AS `estacionamentoID`, `t`.`DataHoraAtual` AS `DataHoraAtual`, `t`.`dataEntrada` AS `dataEntrada`, `t`.`TempoPermanecia` AS `TempoPermanecia`, CASE WHEN `v`.`valorMeiaHora` <= 5 THEN 0 ELSE `v`.`valorMeiaHora` END FROM (`tempoestacionado` `t` join `valor` `v` on(`t`.`tipoID` = `v`.`tipoID`)) ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
