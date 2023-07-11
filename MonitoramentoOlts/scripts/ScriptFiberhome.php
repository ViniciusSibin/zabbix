<?php
function fiberhome(){
	include_once("conexao.php");
	$execQuery = $mysqli->query("SELECT * FROM olt WHERE fabricante = 'FIBERHOME'") or die($mysqli->error);
	$inicio = date('H:i:s');
	echo "Iniciado às $inicio\n";

	while($queryConsultada = $execQuery->fetch_assoc()){
		if($queryConsultada['fabricante'] = 'FIBERHOME'){
			$olt_id = $queryConsultada['id'];
			$host = $queryConsultada['ip'];
			$community = $queryConsultada['comunidade'];
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
				$indexPon = substr($chavePon, 45);

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
				$corrente = (substr(snmp2_get($host,$community,$MIBoltPonOpticalCurrentIndex), 9)) * 0.01;
				$tensao = (substr(snmp2_get($host,$community,$MIBoltPonOpticalVltageIndex), 9)) * 0.01;
				$tx_power = (substr(snmp2_get($host,$community,$MIBoltPonTxOpticalPowerIndex), 9)) * 0.01;
				$status = substr(snmp2_get($host,$community,$MIBoltPonOnlineStatusIndex), 9);
				$temperatura = (substr(snmp2_get($host,$community,$MIBoltPonOpticalTemperatureIndex), 9)) * 0.01;

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
				$indexOnu = substr($chaveOnu, 45);

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
		} elseif($queryConsultada['fabricante'] = 'VSOLUTION'){
			echo "Começa agora";
		}
	}
	$fim = date('H:i:s');
	echo "\nFinalizado às $fim\n";
	$TempoDecorrito = (strtotime($fim))-strtotime($inicio);
	echo "\n" . date('H:i:s',$TempoDecorrito) . "\n\n";
}

fiberhome();
?>
