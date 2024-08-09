<?php

function consultaSinalOlts(){
	echo "Iniciando consulta";

	$mysqli = new mysqli('127.0.0.1', 'monitoramento', 'Av!n!0306b', 'monitoramento_OLTs');

	if($mysqli->connect_errno){
		die("Erro de conexão no banco de dados:" . $mysqli->connect_errno);
	}

	$execQuery = $mysqli->query("SELECT * FROM olt") or die($mysqli->error);

	while($queryConsultada = $execQuery->fetch_assoc()){
		$olt_id = $queryConsultada['id'];
		$host = $queryConsultada['ip'];
		$community = $queryConsultada['comunidade'];

		if($queryConsultada['fabricante'] == 'FIBERHOME'){
			echo "\n\nIniciando a coleta da OLT $queryConsultada[fabricante] - $queryConsultada[nome]";

			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			$olt_pon_name_index = snmp2_real_walk($host,$community,'1.3.6.1.4.1.5875.800.3.9.3.4.1.2');
			
			foreach($olt_pon_name_index as $chavePon => $nome_pon){
				//CRIANDO UM INDEX
				$indexPon = substr($chavePon, 38);
				//$indexPon = substr($chavePon, 45);
				
				//Consultando o OID e já formantando o valor
				$slot_porta = str_replace('"', '', substr($nome_pon, 9));
				$descricao = str_replace('"', '', substr(snmp2_get($host, $community, "1.3.6.1.4.1.5875.800.3.9.3.4.1.3.$indexPon"),8));
				$autorizados = substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.4.1.12.$indexPon"), 9);
				$tensao = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.4.1.9.$indexPon"), 9)) * 0.01;
				$tx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.4.1.8.$indexPon"), 9)) * 0.01;
				$status = substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.4.1.5.$indexPon"), 9);
				$temperatura = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.4.1.11.$indexPon"), 9)) * 0.01;

				$DBPonQuery = $mysqli->query("SELECT slot_porta FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
				$DBPonNumRow = $DBPonQuery->num_rows;

				if($DBPonNumRow === 0) {
					$mysqli->query("INSERT INTO pon (olt_id,pon_index,slot_porta,descricao,autorizados,tensao,tx_power,status,temperatura,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$descricao','$autorizados','$tensao','$tx_power','$status','$temperatura', NOW())");
				} else {
					$mysqli->query("UPDATE pon SET descricao='$descricao', autorizados='$autorizados', tensao='$tensao', tx_power='$tx_power', status='$status', temperatura='$temperatura', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
				}
			}
			// ################# FIM ################

			// ################# PREENCHENDO A TABELA ONU ################
			// ############# INDEX ONU #############
			$OIDindexOnu = snmp2_real_walk($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.2");

			foreach($OIDindexOnu as $chaveOnu => $valorOnu){
				//inserindo index nas MIBs para consulta SNMP
				$indexOnu = substr($chaveOnu, 38);
				//$indexOnu = substr($chaveOnu, 45);

				//Coletando a posição da ONU
				$pon_id_Index = str_replace('"', '', substr(substr($valorOnu, 9),0,9));
				if($pon_id_Index[strlen($pon_id_Index)-1] == "/"){
						$pon_id_Index = substr($pon_id_Index,0,8);
				} else if ($pon_id_Index[strlen($pon_id_Index)-2] == "/" ){
						$pon_id_Index = substr($pon_id_Index,0,7);
				}

				$posicao = str_replace('"', '', substr($valorOnu, 9));
				$status = intval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.10.1.1.11.$indexOnu"), 9));
				$sn = str_replace('"', '', substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.10.1.1.10.$indexOnu"), 9));
				$usuario = str_replace('"', '', substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.10.1.1.7.$indexOnu"),8));
				$temperatura = floatval((substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.10.$indexOnu"), 9)))*0.01;
				$tensao = floatval((substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.8.$indexOnu"), 9))) * 0.01;
				$rx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.6.$indexOnu"), 9)) * 0.01;
				$tx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.7.$indexOnu"), 9)) * 0.01;

				if($status !== 1){
					$status = 0;
				}

				$pon_id_query = $mysqli->query("SELECT pon.id FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt.ip = '$host' AND  slot_porta = '$pon_id_Index'");
				$ponConsultada = $pon_id_query->fetch_assoc();
				$pon_id = $ponConsultada['id'];

				$DBOnuQuery = $mysqli->query("SELECT sn FROM onu WHERE sn='$sn'");
				$DBOnuNuRow = $DBOnuQuery->num_rows;

				if($DBOnuNuRow === 0) {
						$mysqli->query("INSERT INTO onu (pon_id, onu_index, posicao,status,sn,usuario,temperatura,tensao,rx_power,tx_power,ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$sn','$usuario','$temperatura','$tensao','$rx_power','$tx_power', NOW())");
				} else {
					$mysqli->query("UPDATE onu SET status='$status',usuario='$usuario', temperatura='$temperatura', tensao='$tensao', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND posicao='$posicao'");
				}
			}
		} elseif($queryConsultada['fabricante'] == 'VSOLUTION'){
			echo "\n\nIniciando a coleta da OLT $queryConsultada[fabricante] - $queryConsultada[nome]";
			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			$pon_nome_oid = snmp2_real_walk($host,$community,'1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2');

			if ($pon_nome_oid === false) {
				// Erro de resposta SNMP
				echo "Erro ao obter dados SNMP do dispositivo.";
			} else {
				// Processar os dados SNMP retornados
				foreach($pon_nome_oid as $chavePon => $valorPon){
					//CRIANDO UM INDEX
					$indexPon = substr($chavePon, 49);

					//Consultando o OID e já formantando o valor
					$slot_porta = trim(str_replace('"', '', substr($valorPon, 9)));
					$descricao = str_replace('"', '', substr(snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.14.$indexPon"), 9));
					$tensao = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3.$indexPon"));
					$tx_power = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5.$indexPon"));
					$temperatura = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2.$indexPon"));

					if(empty($slot_porta) || $slot_porta == 'no Descr'){
						$slot_porta = "PON$indexPon";
					}

					//Arrumando o valor do status
					if(empty($tx_power) || $tx_power == '0'){
						$status = 0;
					} else {
						$status = 1;
					}

					$DBPonQuery = $mysqli->query("SELECT slot_porta FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					$DBPonFetch = $DBPonQuery->fetch_assoc();
					if(is_null($DBPonFetch)) {
						$mysqli->query("INSERT INTO pon (olt_id,pon_index,slot_porta,descricao,tensao,tx_power,status,temperatura,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$descricao','$tensao','$tx_power','$status','$temperatura', NOW())");
					} else {
						$mysqli->query("UPDATE pon SET slot_porta='$slot_porta', descricao='$descricao', tensao='$tensao', tx_power='$tx_power', status='$status', temperatura='$temperatura', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					}
				}
			}

			//################# PREENCHENDO A TABELA ONU ################
			//############# INDEX ONU #############"
			if($queryConsultada['protocolo'] == 'GPON'){
				$onu_serialNumber_oid = snmp2_real_walk($host,$community,'1.3.6.1.4.1.37950.1.1.6.1.1.2.1.5');

				if ($onu_serialNumber_oid === false) {
					// Erro de resposta SNMP
					echo "Erro ao obter dados SNMP do dispositivo.";
				} else {
					// Processar os dados SNMP retornados
					foreach($onu_serialNumber_oid as $chaveOnu => $valorOnu){
						//CRIANDO UM INDEX
						$oid_indexOnu_array = explode('.',substr($chaveOnu, 46));
						$pon_index = $oid_indexOnu_array[0];
						$indexOnu = $oid_indexOnu_array[1];
						
						//Consultando o OID e já formantando o valor
						$serialNumber = str_replace('"', '', substr($valorOnu, 9));
						$status = intval(preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.$pon_index.$indexOnu")));
						$temperatura = floatval(preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.6.1.1.3.1.3.$pon_index.$indexOnu")));
						$tensao = floatval(preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.6.1.1.3.1.4.$pon_index.$indexOnu")));
						$rx_power = floatval(preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.6.1.1.3.1.7.$pon_index.$indexOnu")));
						$tx_power = floatval(preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,"1.3.6.1.4.1.37950.1.1.6.1.1.3.1.6.$pon_index.$indexOnu")));

						if($status !== 1) {
							$status = 0;
						}

						$pon_id_query = $mysqli->query("SELECT pon.id, pon.slot_porta FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt.id = '$olt_id' AND  pon.pon_index = '$pon_index'");
						$ponConsultada = $pon_id_query->fetch_assoc();
						$pon_id = $ponConsultada['id'];

						//Montando a posição da ONU
						$posicao = $ponConsultada['slot_porta'] . "/$indexOnu";

						$DBOnuQuery = $mysqli->query("SELECT sn FROM onu WHERE sn='$serialNumber' AND pon_id='$pon_id'");
						$DBOnuFetch = $DBOnuQuery->fetch_assoc();

						if(is_null($DBOnuFetch)) {
							$mysqli->query("INSERT INTO onu (pon_id, onu_index, posicao,status,sn,temperatura,tensao,rx_power,tx_power,ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$serialNumber','$temperatura','$tensao','$rx_power','$tx_power', NOW())");
						} else {
							$mysqli->query("UPDATE onu SET status='$status', temperatura='$temperatura', tensao='$tensao', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND sn='$serialNumber'");
						}
					}
				}
			} elseif($queryConsultada['protocolo'] == 'EPON'){
				echo "Ainda em desenvolvimento";
			}

			//Consulta a quantidade de clientes autorizados por pon
			$autorizadosPon = $mysqli->query("SELECT id FROM  pon WHERE olt_id = $olt_id");
			
			//Percorrendo as PONs encontradas
			foreach($autorizadosPon as $pon_id){
				//Consultando todos as ONUs da PON atual
				$onuAutorizadas = $mysqli->query("SELECT id FROM onu WHERE pon_id = {$pon_id['id']}");
				$autorizados = $onuAutorizadas->num_rows;

				$mysqli->query("UPDATE pon SET autorizados='$autorizados' WHERE id='{$pon_id['id']}'");
			}
		} elseif($queryConsultada['fabricante'] == 'ZTE'){
			echo "\n\nIniciando a coleta da OLT $queryConsultada[fabricante] - $queryConsultada[nome]";
			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			$OIDNomePon = snmp2_real_walk($host,$community,'1.3.6.1.2.1.31.1.1.1.1');

			foreach($OIDNomePon as $chavePon => $nomePon){
				if(strrpos($nomePon, "gpon")){
					//CRIANDO UM INDEX
					$indexPon = substr($chavePon, 15);

					//Consultando o OID e já formantando o valor
					$slot_porta = str_replace('"', '', substr($nomePon, 8));
					$descricao = str_replace('"', '', substr(snmp2_get($host,$community,"1.3.6.1.2.1.2.2.1.2.$indexPon"), 8));
					$corrente = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.30.40.2.4.1.5.$indexPon"), 9)) * 0.001;
					$tensao = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.30.40.2.4.1.6.$indexPon"), 9)) * 0.001;
					$tx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.30.40.2.4.1.3.$indexPon"), 9)) * 0.001;
					$status = substr(snmp2_get($host,$community,"1.3.6.1.2.1.2.2.1.8.$indexPon"), 9);
					$temperatura = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.30.40.2.4.1.8.$indexPon"), 9)) * 0.001;

					if($status == "down(2)"){
						$status = 0;
					} else {
						$status = 1;
					}

					$DBPonQuery = $mysqli->query("SELECT slot_porta FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					$DBPonFetch = $DBPonQuery->fetch_assoc();

					//echo "\n\nSlot_porta: $slot_porta \nCorrente: $corrente \nTensão: $tensao \nTX_Power: $tx_power \nStatus: $status \nTemperatura: $temperatura";
					if(is_null($DBPonFetch)) {
						$mysqli->query("INSERT INTO pon (olt_id,pon_index,slot_porta,descricao,corrente,tensao,tx_power,status,temperatura,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$descricao','$corrente','$tensao','$tx_power','$status','$temperatura', NOW())");
					} else {
						$mysqli->query("UPDATE pon SET descricao='$descricao', corrente='$corrente', tensao='$tensao', tx_power='$tx_power', status='$status', temperatura='$temperatura', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					}
				}
			}
			// ################# FIM ################

			// ################# PREENCHENDO A TABELA ONU ################
			// ############# INDEX ONU #############
			$OIDSerialNumberOnu = snmp2_real_walk($host,$community,"1.3.6.1.4.1.3902.1082.500.10.2.3.3.1.18");

			foreach($OIDSerialNumberOnu as $chaveOnu => $valorOnu){
				//inserindo index nas MIBs para consulta SNMP
				$indexPonOnu = explode(".",substr($chaveOnu, 34));
				$indexPon = $indexPonOnu[0];
				$indexOnu = $indexPonOnu[1];
				
				$sn = str_replace('"', '',substr($valorOnu, 11));
				$usuario = str_replace('"', '', substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.500.10.2.3.3.1.2.$indexPon.$indexOnu"), 8));
				$status = substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.500.10.2.3.8.1.4.$indexPon.$indexOnu"), 9);
				$rx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.500.20.2.2.2.1.10.$indexPon.$indexOnu.1"), 9));
				$tx_power = floatval(substr(snmp2_get($host,$community,"1.3.6.1.4.1.3902.1082.500.20.2.2.2.1.14.$indexPon.$indexOnu.1"), 9));

				if($status == 4){
					$status = 1;
				} else {
					$status = 0;
				}

				if($rx_power >= 0 && $rx_power <= 32767){
					$rx_power = ($rx_power * 0.002) - 30;
				} elseif($rx_power > 32767) {
					$rx_power = 0.0;
				}

				if($tx_power >= 0 && $tx_power <= 32767){
					$tx_power = ($tx_power * 0.002) - 30;
				} elseif($tx_power > 32767) {
					$tx_power = 0.0;
				}

				$pon_id_query = $mysqli->query("SELECT pon.id, pon.slot_porta FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt_id = '$olt_id' AND  pon_index = '$indexPon'");
				$ponConsultada = $pon_id_query->fetch_assoc();
				$pon_id = $ponConsultada['id'];

				$posicao = $ponConsultada['slot_porta'] . ":" . $indexOnu; 

				$DBOnuQuery = $mysqli->query("SELECT sn FROM onu WHERE sn='$sn' AND pon_id = $pon_id");
				$DBOnuNumRows = $DBOnuQuery->num_rows;

				if($DBOnuNumRows === 0) {
					$mysqli->query("INSERT INTO onu (pon_id, onu_index, posicao, status, sn, usuario, rx_power, tx_power, ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$sn', '$usuario', '$rx_power','$tx_power', NOW())");

				} else {
					$mysqli->query("UPDATE onu SET status='$status', usuario='$usuario', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND posicao='$posicao'");
				}
			}

			//Consulta a quantidade de clientes autorizados por pon
			$autorizadosPon = $mysqli->query("SELECT id FROM pon WHERE olt_id = $olt_id");
			
			//Percorrendo as PONs encontradas
			foreach($autorizadosPon as $pon_id){
				//Consultando todos as ONUs da PON atual
				$onuAutorizadas = $mysqli->query("SELECT status FROM onu WHERE pon_id = {$pon_id['id']}");
				$autorizados = $onuAutorizadas->num_rows;

				$mysqli->query("UPDATE pon SET autorizados='$autorizados' WHERE id='{$pon_id['id']}'");
			}
		} elseif($queryConsultada['fabricante'] == 'HUAWEI'){
			echo "\n\nIniciando a coleta da OLT $queryConsultada[fabricante] - $queryConsultada[nome]";
			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			$OIDNomePon = snmp2_real_walk($host,$community,'1.3.6.1.2.1.31.1.1.1.1');

			foreach($OIDNomePon as $chavePon => $nomePon){
				if(strrpos($nomePon, "GPON")){
					//CRIANDO UM INDEX
					$indexPon = substr($chavePon, 15);

					//Concatenando o OID com o index da PON
					$MIBhwGponDeviceOltControlStatusIndex = ("1.3.6.1.4.1.2011.6.128.1.1.2.21.1.10.$indexPon");
					$MIBtransmitPowerIndex = ("1.3.6.1.4.1.2011.6.128.1.1.2.23.1.4.$indexPon");
					$MIBhwGponDeviceOltControlOntNumIndex = ("1.3.6.1.4.1.2011.6.128.1.1.2.21.1.16.$indexPon");

					//$MIBzxAnOpticalSupplyVoltageIndex = ("1.3.6.1.4.1.3902.1082.30.40.2.4.1.6.$indexPon");
					//$MIBzxAnOpticalTemperatureIndex = ("1.3.6.1.4.1.3902.1082.30.40.2.4.1.8.$indexPon");
					//$descricao

					//Consultando o OID e já formantando o valor
					$slot_porta = str_replace('"', '', substr($nomePon, 8));
					$status = intval(trim(substr(snmp2_get($host,$community,$MIBhwGponDeviceOltControlStatusIndex), 9)));
					$tx_power = floatval(substr(snmp2_get($host,$community,$MIBtransmitPowerIndex), 9)) * 0.01;
					$autorizados = intval(trim(substr(snmp2_get($host,$community,$MIBhwGponDeviceOltControlOntNumIndex), 9)));
					
					//$tensao = floatval(substr(snmp2_get($host,$community,$MIBzxAnOpticalSupplyVoltageIndex), 9)) * 0.001;
					//$temperatura = floatval(substr(snmp2_get($host,$community,$MIBzxAnOpticalTemperatureIndex), 9)) * 0.001;
					//$descricao = 

					if($status !== 1){
						$status = 0;
					}

					if($tx_power > 10){
						$tx_power = 0;
					}

					$DBPonQuery = $mysqli->query("SELECT * FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					$DBPonRow = $DBPonQuery->num_rows;

					if($DBPonRow === 0) {
						$mysqli->query("INSERT INTO pon (olt_id,pon_index,slot_porta,autorizados,tx_power,status,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$autorizados','$tx_power','$status', NOW())");
					} else {
						$mysqli->query("UPDATE pon SET autorizados='$autorizados', tx_power='$tx_power', status='$status', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
					}
				}
			}
			// ################# FIM ################

			// ################# PREENCHENDO A TABELA ONU ################
			// ############# INDEX ONU #############
			$OIDUsuarioOnu = snmp2_real_walk($host,$community,"1.3.6.1.4.1.2011.6.128.1.1.2.43.1.9");

			foreach($OIDUsuarioOnu as $chaveOnu => $valorOnu){
				//inserindo index nas MIBs para consulta SNMP
				$indexPonOnu = explode(".",substr($chaveOnu, 48));
				$indexPon = $indexPonOnu[0];
				$indexOnu = $indexPonOnu[1];

				//Concatenando o OID com o index da PON
				$onu_status_oid = ("1.3.6.1.4.1.2011.6.128.1.1.2.62.1.22.$indexPon.$indexOnu.1");
				$onu_rx_power_oid = ("1.3.6.1.4.1.2011.6.128.1.1.2.51.1.4.$indexPon.$indexOnu");
				$onu_tx_power_oid = ("1.3.6.1.4.1.2011.6.128.1.1.2.51.1.3.$indexPon.$indexOnu");
				$onu_tensao_oid = "1.3.6.1.4.1.2011.6.128.1.1.2.51.1.5.$indexPon.$indexOnu";
				$onu_temperatura_oid = "1.3.6.1.4.1.2011.6.128.1.1.2.51.1.1.$indexPon.$indexOnu";
				
				$usuario = str_replace('"', '',substr($valorOnu, 8));
				//$status = intval(substr(snmp2_get($host,$community,$onu_status_oid), 9));
				$rx_power = floatval(substr(snmp2_get($host,$community,$onu_rx_power_oid), 9)) * 0.01;
				$tx_power = floatval(substr(snmp2_get($host,$community,$onu_tx_power_oid), 9)) * 0.01;
				$tensao = floatval(substr(snmp2_get($host,$community,$onu_tensao_oid), 9)) * 0.001;
				$temperatura = floatval(substr(snmp2_get($host,$community,$onu_temperatura_oid), 9));

				if($rx_power > 0){
					$status = 0;
					$rx_power = 0;
					$tx_power = 0;
					$tensao = 0;
					$temperatura = 0;
					$corrente = 0;
				} else {
					$status = 1;
				}

				$sn = "Sem OID";
				
				$pon_id_query = $mysqli->query("SELECT pon.id, pon.slot_porta FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt_id = '$olt_id' AND  pon_index = '$indexPon'");
				$ponConsultada = $pon_id_query->fetch_assoc();
				$pon_id = $ponConsultada['id'];

				$posicao = $ponConsultada['slot_porta'] . ":" . $indexOnu; 

				$DBOnuQuery = $mysqli->query("SELECT * FROM onu WHERE usuario='$usuario' AND pon_id = $pon_id");
				$DBOnuNumRows = $DBOnuQuery->num_rows;

				if($DBOnuNumRows === 0) {
					$mysqli->query("INSERT INTO onu (pon_id, onu_index, posicao, status, sn, usuario, temperatura, tensao, rx_power, tx_power, ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$sn', '$usuario', '$temperatura','$tensao','$rx_power','$tx_power', NOW())");
				} else {
					$mysqli->query("UPDATE onu SET status='$status', usuario='$usuario', temperatura='$temperatura', tensao='$tensao', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND posicao='$posicao'");
				}
			}
		} else {
			echo "OLT ainda não configurada";
		}
	}
}

consultaSinalOlts();
?>