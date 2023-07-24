<?php
function fiberhome(){
	require_once("conexao.php");
	$execQuery = $mysqli->query("SELECT * FROM olt") or die($mysqli->error);
	$inicio = date('H:i:s');
	echo "\nIniciado às $inicio\n";

	while($queryConsultada = $execQuery->fetch_assoc()){
		$olt_id = $queryConsultada['id'];
		$host = $queryConsultada['ip'];
		$community = $queryConsultada['comunidade'];

		if($queryConsultada['fabricante'] == 'FIBERHOME'){
			// ################# Atualizar o nome da OLT ##########################
			$OLTName = str_replace('"', '', substr(snmp2_get($host,$community,"sysName.0"), 8));
			$DBNameQuery = $mysqli->query("SELECT nome FROM olt WHERE ip = '$host'");
			$DBNameFetch = $DBNameQuery->fetch_assoc();
			if (!empty($OLTName) && $OLTName != $DBNameFetch['nome']){
					$mysqli->query("UPDATE olt SET nome = '$OLTName' WHERE ip = '$host'") or die($mysqli->error);
			}

			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			$OIDindexPon = snmp2_real_walk($host,$community,'1.3.6.1.4.1.5875.800.3.9.3.4.1.2');

			foreach($OIDindexPon as $chavePon => $valorPon){
				//CRIANDO UM INDEX
				$indexPon = substr($chavePon, 38);

				//Concatenando o OID com o index da PON
				$MIBoltPonAuthOnuNumIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.12.$indexPon");
				$MIBoltPonOpticalCurrentIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.10.$indexPon");
				$MIBoltPonOpticalVltageIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.9.$indexPon");
				$MIBoltPonTxOpticalPowerIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.8.$indexPon");
				$MIBoltPonOnlineStatusIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.5.$indexPon");
				$MIBoltPonOpticalTemperatureIndex = ("1.3.6.1.4.1.5875.800.3.9.3.4.1.11.$indexPon");

				//Consultando o OID e já formantando o valor
				$slot_porta = str_replace('"', '', substr($valorPon, 9));

				$autorizados = substr(snmp2_get($host,$community,$MIBoltPonAuthOnuNumIndex), 9);
				$corrente = floatval(substr(snmp2_get($host,$community,$MIBoltPonOpticalCurrentIndex), 9)) * 0.01;
				$tensao = floatval(substr(snmp2_get($host,$community,$MIBoltPonOpticalVltageIndex), 9)) * 0.01;
				$tx_power = floatval(substr(snmp2_get($host,$community,$MIBoltPonTxOpticalPowerIndex), 9)) * 0.01;
				$status = substr(snmp2_get($host,$community,$MIBoltPonOnlineStatusIndex), 9);
				$temperatura = floatval(substr(snmp2_get($host,$community,$MIBoltPonOpticalTemperatureIndex), 9)) * 0.01;

				$DBPonQuery = $mysqli->query("SELECT slot_porta FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
				$DBPonFetch = $DBPonQuery->fetch_assoc();


				if(is_null($DBPonFetch)) {
						$inserirPON = "INSERT INTO pon (olt_id,pon_index,slot_porta,autorizados,corrente,tensao,tx_power,status,temperatura,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$autorizados','$corrente','$tensao','$tx_power','$status','$temperatura', NOW())";

						$mysqli->query($inserirPON) or die($mysqli->error);
				} else {
						$atualizarPon = "UPDATE pon SET autorizados='$autorizados', corrente='$corrente', tensao='$tensao', tx_power='$tx_power', status='$status', temperatura='$temperatura', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'";

						$mysqli->query($atualizarPon) or die($mysqli->error);
				}
			}
			// ################# FIM ################

			// ################# PREENCHENDO A TABELA ONU ################
			// ############# INDEX ONU #############
			$OIDindexOnu = snmp2_real_walk($host,$community,"1.3.6.1.4.1.5875.800.3.9.3.3.1.2");

			foreach($OIDindexOnu as $chaveOnu => $valorOnu){
				//inserindo index nas MIBs para consulta SNMP
				$indexOnu = substr($chaveOnu, 38);

				//Concatenando o OID com o index da PON
				$MIBonuStatusIndex = ("1.3.6.1.4.1.5875.800.3.10.1.1.11.$indexOnu");
				$MIBauthOnuListMacIndex = ("1.3.6.1.4.1.5875.800.3.10.1.1.10.$indexOnu");
				$MIBonuPonOpticalTemperatureIndex =("1.3.6.1.4.1.5875.800.3.9.3.3.1.10.$indexOnu");
				$MIBonuPonOpticalVltageIndex = ("1.3.6.1.4.1.5875.800.3.9.3.3.1.8.$indexOnu");
				$MIBonuPonOpticalCurrentIndex = ("1.3.6.1.4.1.5875.800.3.9.3.3.1.9.$indexOnu");
				$MIBonuPonRxOpticalPowerIndex = ("1.3.6.1.4.1.5875.800.3.9.3.3.1.6.$indexOnu");
				$MIBonuPonTxOpticalPowerIndex = ("1.3.6.1.4.1.5875.800.3.9.3.3.1.7.$indexOnu");

				//Coletando a posição da ONU
				$pon_id_Index = str_replace('"', '', substr(substr($valorOnu, 9),0,9));
				if($pon_id_Index[strlen($pon_id_Index)-1] == "/"){
						$pon_id_Index = substr($pon_id_Index,0,8);
				} else if ($pon_id_Index[strlen($pon_id_Index)-2] == "/" ){
						$pon_id_Index = substr($pon_id_Index,0,7);
				}

				try{
					$posicao = str_replace('"', '', substr($valorOnu, 9));
					$status = substr(snmp2_get($host,$community,$MIBonuStatusIndex), 9);
					$sn = str_replace('"', '', substr(snmp2_get($host,$community,$MIBauthOnuListMacIndex), 9));
					$temperatura = floatval((substr(snmp2_get($host,$community,$MIBonuPonOpticalTemperatureIndex), 9)))*0.01;
					$voltagem = floatval((substr(snmp2_get($host,$community,$MIBonuPonOpticalVltageIndex), 9))) * 0.01;
					$amperagem = floatval(substr(snmp2_get($host,$community,$MIBonuPonOpticalCurrentIndex), 9)) * 0.01;
					$rx_power = floatval(substr(snmp2_get($host,$community,$MIBonuPonRxOpticalPowerIndex), 9)) * 0.01;
					$tx_power = floatval(substr(snmp2_get($host,$community,$MIBonuPonTxOpticalPowerIndex), 9)) * 0.01;

					$pon_id_query = $mysqli->query("SELECT pon.id FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt.ip = '$host' AND  slot_porta = '$pon_id_Index'");
					$ponConsultada = $pon_id_query->fetch_assoc();
					$pon_id = $ponConsultada['id'];

					$DBOnuQuery = $mysqli->query("SELECT sn FROM onu WHERE sn='$sn'");
					$DBOnuFetch = $DBOnuQuery->fetch_assoc();

					if(is_null($DBOnuFetch)) {
							$inserirONU = "INSERT INTO onu (pon_id, onu_index, posicao,status,sn,temperatura,tensao,corrente,rx_power,tx_power,ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$sn','$temperatura','$tensao','$corrente','$rx_power','$tx_power', NOW())";

							$mysqli->query($inserirONU) or die($mysqli->error);
					} else {
							$atualizarPon = "UPDATE onu SET status='$status', temperatura='$temperatura', tensao='$tensao', corrente='$corrente', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND posicao='$posicao'";

							$mysqli->query($atualizarPon) or die($mysqli->error);
					}
				} catch (Exception $e) {
					// Capturar a exceção e tratar o erro
					echo "Ocorreu um erro na comunicação SNMP: " . $e->getMessage();
				}
			}
		} elseif($queryConsultada['fabricante'] == 'VSOLUTION'){
			//################# PREENCHENDO A TABELA PON ################
			//############# INDEX PON #############"
			try {
				$pon_nome_oid = snmp2_real_walk($host,$community,'1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2');

				if ($pon_nome_oid === false) {
					// Erro de resposta SNMP
					echo "Erro ao obter dados SNMP do dispositivo.";
				} else {
					// Processar os dados SNMP retornados
					foreach($pon_nome_oid as $chavePon => $valorPon){
						//CRIANDO UM INDEX
						$indexPon = substr($chavePon, 49);
						
						//Concatenando o OID com o index da PON
						$pon_corrente_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.4.$indexPon";
						$pon_tx_power_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5.$indexPon";
						$pon_tensao_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3.$indexPon";
						$pon_temperatura_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2.$indexPon";
						$pon_descricao_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.14.$indexPon";

						//Consultando o OID e já formantando o valor
						$slot_porta = str_replace('/[^a-zA-z0-9\.-/]/', '', str_replace('"', '', substr($valorPon, 9)));
						$descricao = str_replace('"', '', substr(snmp2_get($host,$community,$pon_descricao_oid), 9));
						$corrente = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$pon_corrente_oid));
						$tensao = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$pon_tensao_oid));
						$tx_power = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$pon_tx_power_oid));
						$temperatura = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$pon_temperatura_oid));

						if($slot_porta == 'no Descr'){
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
							$inserirPON = "INSERT INTO pon (olt_id,pon_index,slot_porta, descricao, corrente,tensao,tx_power,status,temperatura,ult_atualizacao) VALUES ('$olt_id','$indexPon','$slot_porta','$descricao','$corrente','$tensao','$tx_power','$status','$temperatura', NOW())";

							$mysqli->query($inserirPON) or die($mysqli->error);
						} else {
							$atualizarPon = "UPDATE pon SET descricao='$descricao', corrente='$corrente', tensao='$tensao', tx_power='$tx_power', status='$status', temperatura='$temperatura', ult_atualizacao=NOW() WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'";
							
							$mysqli->query($atualizarPon) or die($mysqli->error);
						}
					}
				}
			} catch (Exception $e) {
				// Tratar a exceção do SNMP
				echo "Erro durante a comunicação SNMP: " . $e->getMessage();
			}

			//################# PREENCHENDO A TABELA ONU ################
			//############# INDEX ONU #############"
			if($queryConsultada['protocolo'] == 'GPON'){
				try {
					$onu_serialNumber_oid = snmp2_real_walk($host,$community,'1.3.6.1.4.1.37950.1.1.6.1.1.2.1.5');

					if ($onu_serialNumber_oid === false) {
						// Erro de resposta SNMP
						echo "Erro ao obter dados SNMP do dispositivo.";
					} else {
						// Processar os dados SNMP retornados
						foreach($onu_serialNumber_oid as $chaveOnu => $valorOnu){
							//CRIANDO UM INDEX
							$oid_indexOnu = substr($chaveOnu, 46);
							$oid_indexOnu_array = explode('.',$oid_indexOnu);
							$pon_index = $oid_indexOnu_array[0];
							$indexOnu = $oid_indexOnu_array[1];

							//Concatenando o OID com o index da PON
							$onu_corrente_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.5.$oid_indexOnu";
							$onu_tx_power_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.6.$oid_indexOnu";
							$onu_rx_power_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.7.$oid_indexOnu";
							$onu_status_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.$oid_indexOnu";
							$onu_tensao_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.4.$oid_indexOnu";
							$onu_temperatura_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.3.$oid_indexOnu";

							//Consultando o OID e já formantando o valor
							$serialNumber = str_replace('"', '', substr($valorOnu, 9));
							$status = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_status_oid));
							$temperatura = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_temperatura_oid));
							$tensao = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_tensao_oid));
							$corrente = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_corrente_oid));
							$rx_power = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_rx_power_oid));
							$tx_power = preg_replace('/[^0-9\.-]/', '', snmp2_get($host,$community,$onu_tx_power_oid));

							if($status == '2') {
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
								$inserirONU = "INSERT INTO onu (pon_id, onu_index, posicao,status,sn,temperatura,tensao,corrente,rx_power,tx_power,ult_atualizacao) VALUES ('$pon_id','$indexOnu','$posicao','$status','$serialNumber','$temperatura','$tensao','$corrente','$rx_power','$tx_power', NOW())";

								$mysqli->query($inserirONU) or die($mysqli->error);
							} else {
								$atualizarOnu = "UPDATE onu SET status='$status', temperatura='$temperatura', tensao='$tensao', corrente='$corrente', rx_power='$rx_power', tx_power='$tx_power', ult_atualizacao=NOW() WHERE pon_id='$pon_id' AND sn='$serialNumber'";

								$mysqli->query($atualizarPon) or die($mysqli->error);
							}
						}
					}
				} catch (Exception $e) {
					// Tratar a exceção do SNMP
					echo "Erro durante a comunicação SNMP: " . $e->getMessage();
				}
			} elseif($queryConsultada['protocolo'] == 'EPON'){
				try {

				} catch (Exception $e) {
					// Tratar a exceção do SNMP
					echo "Erro durante a comunicação SNMP: " . $e->getMessage();
				}
			}

			//Consulta a quantidade de clientes autorizados por pon
			$autorizadosPon = $mysqli->query("select id from  pon where olt_id = $olt_id");
			
			//Percorrendo as PONs encontradas
			foreach($autorizadosPon as $pon_id){
				//Consultando todos as ONUs da PON atual
				$onuAutorizadas = $mysqli->query("SELECT status FROM onu WHERE pon_id = {$pon_id['id']}");
				$autorizados = $onuAutorizadas->num_rows;

				$atualizaPON = $mysqli->query("UPDATE pon SET autorizados='$autorizados' WHERE id='{$pon_id['id']}'");
			}
		}
	}
	$fim = date('H:i:s');
	echo "\nFinalizado às $fim\n";
	$TempoDecorrito = (strtotime($fim))-strtotime($inicio);
	echo "\n" . date('H:i:s',$TempoDecorrito) . "\n\n";
}

fiberhome();
?>