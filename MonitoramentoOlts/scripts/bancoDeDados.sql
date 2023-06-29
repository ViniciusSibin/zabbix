CREATE DATABASE IF NOT EXISTS olt
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

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
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('VSOL-EPON-VILA', '192.168.14.5', 'VSOLUTION', '2c', 'mgp', 23);
INSERT INTO olt (nome, ip, fabricante, versaoSNMP, comunidade, porta) VALUES('OLT - MGP', '10.254.1.132', 'FIBERHOME', '2c', 'mgp', 23);

COMMIT;

CREATE TABLE IF NOT EXISTS pon(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	olt_id INT NOT NULL,
	pon_index INT,
	slot_porta VARCHAR(9) NOT NULL,
	autorizados INT,
	amperagem FLOAT,
	tx_power FLOAT,
	status INT,
	temperatura FLOAT,
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
	FOREIGN KEY (pon_id) REFERENCES pon(id)
)DEFAULT CHARSET=utf8 ENGINE=InnoDB;