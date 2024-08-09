CREATE USER 'monitoramento'@'%' IDENTIFIED BY 'Av!n!0306b';
GRANT ALL PRIVILEGES ON * . * TO 'monitoramento'@'%';
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS monitoramento_OLTs
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE monitoramento_OLTs;

CREATE TABLE `olt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `fabricante` varchar(40) DEFAULT NULL,
  `protocolo` varchar(20) DEFAULT NULL,
  `versaoSNMP` enum('1','2c','3') DEFAULT '2c',
  `comunidade` varchar(30) DEFAULT NULL,
  `porta` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-IGUA-C650', '10.254.1.190', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-PAICANDU-C600', '10.254.1.208', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-PAULISTA-ZTE-C620', '10.254.1.204', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-IGUATEMI', '10.254.1.197', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-MANDAGUACU', '10.254.1.199', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-NOVO-SDI-C620', '10.254.1.194', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-VALE-AZUL-C620', '10.254.1.195', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-DR-CAMARGO-C620', '10.254.1.207', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-ANGULO-C620', '10.254.1.193', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-V200-C620', '10.254.1.191', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-FLORIANO-C620', '10.254.1.210', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-OURO-COLA-C620', '10.254.1.206', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-MANDACARU-C620', '10.254.1.203', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-IVATUBA-ZTE-C620', '10.254.1.205', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-ITAIPU-C620', '10.254.1.202', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-EVEREST-ZTE-C620', '10.254.1.201', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-CAMPO-BELO-C600', '10.254.1.209', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-AURORA-C620', '10.254.1.196', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-VILA-RURAL-C620', '10.254.1.192', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-MGP', '10.254.1.132', 'FIBERHOME', 'GPON', '2c', 'mgp', '23');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-ZONA07-C620', '10.254.1.198', 'ZTE', 'XGSPON', '2c', 'mgp', '22');
INSERT INTO olt (nome, ip, fabricante, protocolo, versaoSNMP, comunidade, porta) VALUES('OLT-SARANDI-C600', '10.254.1.200', 'ZTE', 'XGSPON', '2c', 'mgp', '22');

COMMIT;

CREATE TABLE `cidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO cidades (nome, estado, pais, status) VALUES('Agua Boa', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Angulo', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Astorga', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Doutor Camargo', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Iguaracu', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Iguatemi', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Ivatuba', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Maringa', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Munhoz de Melo', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Paicandu', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Sarandi', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Tupinamba', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Mandaguacu', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Dist. Floriano', 'Paraná', 'Brasil', 1);
INSERT INTO cidades (nome, estado, pais, status) VALUES('Dist. São Domingos', 'Paraná', 'Brasil', 1);

COMMIT;

CREATE TABLE `olt_cidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_olt` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_olt` (`id_olt`),
  KEY `id_cidade` (`id_cidade`),
  CONSTRAINT `olt_cidade_ibfk_1` FOREIGN KEY (`id_olt`) REFERENCES `olt` (`id`),
  CONSTRAINT `olt_cidade_ibfk_3` FOREIGN KEY (`id_cidade`) REFERENCES `cidades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 9);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 3);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 5);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(4, 10);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(4, 15);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(6, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(7, 6);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(7, 15);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(8, 13);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(9, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(10, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(11, 4);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(13, 2);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(14, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(16, 14);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(18, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(20, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(21, 7);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(22, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(26, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(28, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(29, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(11, 1);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(32, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(32, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(31, 5);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(34, 11);


CREATE TABLE `pon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `olt_id` int(11) NOT NULL,
  `pon_index` varchar(15) NOT NULL,
  `slot_porta` varchar(20) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `autorizados` int(11) DEFAULT NULL,
  `corrente` float DEFAULT NULL,
  `tensao` float DEFAULT NULL,
  `tx_power` float DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `temperatura` float DEFAULT NULL,
  `ult_atualizacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `olt_id` (`olt_id`),
  CONSTRAINT `pon_ibfk_1` FOREIGN KEY (`olt_id`) REFERENCES `olt` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `onu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pon_id` int(11) NOT NULL,
  `onu_index` int(11) DEFAULT NULL,
  `posicao` varchar(20) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `sn` varchar(30) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `temperatura` float DEFAULT NULL,
  `tensao` float DEFAULT NULL,
  `corrente` float DEFAULT NULL,
  `rx_power` float DEFAULT NULL,
  `tx_power` float DEFAULT NULL,
  `ult_atualizacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pon_id` (`pon_id`),
  CONSTRAINT `onu_ibfk_1` FOREIGN KEY (`pon_id`) REFERENCES `pon` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


COMMIT;

SET @tempo_historico = 1;
SET @sinal = -25;


CREATE TABLE historico_clientes_sinal_ruim (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_hora DATETIME,
    qtd_clientes INT
);


-- Habilitar o Event Scheduler, se ainda não estiver ativado
SET GLOBAL event_scheduler = ON;

-- Criar o evento para atualizar o histórico a cada minuto
CREATE EVENT atualizar_historico
ON SCHEDULE EVERY @tempo_historico MINUTE
DO
BEGIN
    DECLARE client_count INT;

    -- Calcula o count dos clientes com sinal ruim
    SELECT COUNT(*) INTO client_count
    FROM onu o
    INNER JOIN pon p ON p.id = o.pon_id
    INNER JOIN olt ON olt.id = p.olt_id 
    WHERE o.rx_power BETWEEN -39.00 AND @sinal;

    -- Insere o resultado na tabela de histórico
    INSERT INTO historico_clientes_sinal_ruim (data_hora, qtd_clientes)
    VALUES (NOW(), client_count);
END;



CREATE EVENT limpar_base
ON SCHEDULE EVERY 10 MINUTE
DO 
BEGIN
	DELETE FROM onu WHERE TIMESTAMPDIFF(HOUR, ult_atualizacao, NOW()) >= 5;
	DELETE FROM pon WHERE TIMESTAMPDIFF(HOUR, ult_atualizacao, NOW()) >= 5;
	DELETE FROM historico_clientes_sinal_ruim  WHERE TIMESTAMPDIFF(DAY, data_hora, NOW()) >= 31;
END


COMMIT;