CREATE DATABASE IF NOT EXISTS monitoramento_OLTs
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE monitoramento_OLTs;

CREATE TABLE IF NOT EXISTS olt(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(40) NOT NULL,
	ip VARCHAR(15) NOT NULL,
	fabricante VARCHAR(40),
	versaoSNMP enum('1', '2c', '3') DEFAULT '2c',
	comunidade VARCHAR(30),
	porta VARCHAR(5)
)DEFAULT CHARSET=utf8 ENGINE=InnoDB;

INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT DATACOM IGUA', '192.168.14.4', 'DATACOM', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-SARANDI', '10.254.1.160', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT2-29-SDI', '10.254.1.163', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-PAICANDU', '10.254.1.130', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT2-PAICANDU', '10.254.1.183', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-PAULISTA', '10.100.255.2', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-IGUATEMI', '10.254.1.6', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-MANDAGUACU', '10.254.1.161', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT INTEL NOVO SARANDI', '10.254.1.141', 'INTELBRAS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT INTEL VALE AZUL', '10.254.1.142', 'INTELBRAS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT DR CAMARGO', '10.254.1.165', 'INTELBRAS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT PARKS CAMP BELO', '10.10.0.1', 'PARKS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT PARKS ANGULO', '10.254.1.174', 'PARKS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT PARKS V200', '10.254.1.177', 'PARKS', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT MGP CND HAVANA', '10.254.1.181', 'UBIQUITI', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT FLORIANO', '10.254.1.137', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT SEMINARIO', '10.254.1.138', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT GPON OURO COLA', '10.254.1.147', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT ED CANAÃ', '10.254.1.151', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT MANDACARU', '10.254.1.153', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT IVATUBA', '10.254.1.168', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT ITAIPU', '10.254.1.179', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('VSOL-EPON-IGUA01', '192.168.14.2', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('VSOL-IGUA-GPON-VILA', '192.168.14.6', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT ORIENTAL', '192.168.21.1', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('VSOL-GPON-OXI', '10.100.3.10', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('EVEREST EPON 1', '10.100.3.2', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-THAIS', '10.254.1.136', 'FIBERHOME', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT-MGP-AURORA2', '10.254.2.2', 'HUAWEI', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT AGUA BOA', '10.254.1.169', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT_EPON-2_IGUA', '192.168.14.3', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT - MGP', '10.254.1.132', 'FIBERHOME', '2c', 'mgp', 23);

COMMIT;

CREATE TABLE IF NOT EXISTS cidades(
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(50) NOT NULL,
  estado varchar(50) NOT NULL,
  pais varchar(50) NOT NULL,
  status int(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

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

CREATE TABLE olt_cidade (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_olt int(11) NOT NULL,
  id_cidade int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY id_olt (id_olt),
  KEY id_cidade (id_cidade),
  CONSTRAINT olt_cidade_ibfk_1 FOREIGN KEY (id_olt) REFERENCES olt (id),
  CONSTRAINT olt_cidade_ibfk_3 FOREIGN KEY (id_cidade) REFERENCES cidades (id)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 9);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 3);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(1, 5);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(2, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(3, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(4, 10);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(5, 10);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(5, 15);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(6, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(7, 6);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(7, 15);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(8, 13);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(9, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(10, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(11, 4);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(12, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(13, 2);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(14, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(15, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(16, 14);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(17, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(18, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(19, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(20, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(21, 7);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(22, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(23, 5);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(24, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(25, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(26, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(27, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(28, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(29, 11);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(30, 1);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(31, 5);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(32, 8);
INSERT INTO olt_cidade (id_olt, id_cidade) VALUES(32, 11);



CREATE TABLE IF NOT EXISTS pon(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	olt_id INT NOT NULL,
	pon_index INT NOT NULL,
	slot_porta VARCHAR(9) NOT NULL,
	descricao VARCHAR(30),
	autorizados INT,
	corrente FLOAT,
	tensao FLOAT,
	tx_power FLOAT,
	status INT,
	temperatura FLOAT,
	ult_atualizacao TIME,
	FOREIGN KEY (olt_id) REFERENCES olt(id)
)DEFAULT CHARSET=utf8 ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS onu(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	pon_id INT NOT NULL,
	onu_index INT,
	posicao VARCHAR(12) NOT NULL,
	status INT,
	sn VARCHAR(30),
	temperatura FLOAT,
	voltagem FLOAT,
	amperagem FLOAT,
	rx_power FLOAT,
	tx_power FLOAT,
	ult_atualizacao TIME,
	FOREIGN KEY (pon_id) REFERENCES pon(id)
)DEFAULT CHARSET=utf8 ENGINE=InnoDB;