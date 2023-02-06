<?php
function Fiberhome(){
        require_once("conexao.php");
        $execQuery = $mysqli->query("SELECT * FROM olt WHERE fabricante = 'FIBERHOME'") or die($mysqli->error);
        $inicio = date('H:i:s');

        while($queryConsultada = $execQuery->fetch_assoc()){
                $host = $queryConsultada['ip'];
                $community = $queryConsultada['comunidade'];
                // ################# Atualizar o nome da OLT ##########################
                $OLTName = str_replace('"', '', substr(snmp2_get($host,$community,"sysName.0"), 8));
                $DBNameQuery = $mysqli->query("SELECT nome FROM olt WHERE ip = '$host'");
                $DBNameFetch = $DBNameQuery->fetch_assoc();
                $DBName = $DBNameFetch['nome'];
                if ($OLTName != $DBName){
                        $mysqli->query("UPDATE olt SET nome = '$OLTName' WHERE ip = '$host'") or die($mysqli->error);
                }
                //################# PREENCHENDO A TABELA PON ################
                $MIBoltPonNameIndex = 'oltPonName'; //'oltPonName'
                $MIBoltPonName = 'oltPonName.';
                $MIBoltPonAuthOnuNum = 'oltPonAuthOnuNum.';
                $MIBoltPonOpticalCurrent = 'oltPonOpticalCurrent.';
                $MIBoltPonOpticalVltage = 'oltPonOpticalVltage.';
                $MIBoltPonTxOpticalPower = 'oltPonTxOpticalPower.';
                $MIBoltPonOnlineStatus = 'oltPonOnlineStatus.';
                $MIBoltPonOpticalTemperature = 'oltPonOpticalTemperature.';

                //############# INDEX PON #############";
                $OIDindexPon = snmp2_real_walk($host,$community,$MIBoltPonNameIndex);

                foreach($OIDindexPon as $chavePon => $valorPon){
                        //CRIANDO UM INDEX
                        $indexPon = substr($chavePon, 38);

                        $MIBoltPonNameIndex = ($MIBoltPonName . $indexPon);
                        $MIBoltPonAuthOnuNumIndex = ($MIBoltPonAuthOnuNum . $indexPon);
                        $MIBoltPonOpticalCurrentIndex = ($MIBoltPonOpticalCurrent . $indexPon);
                        $MIBoltPonOpticalVltageIndex = ($MIBoltPonOpticalVltage . $indexPon);
                        $MIBoltPonTxOpticalPowerIndex = ($MIBoltPonTxOpticalPower . $indexPon);
                        $MIBoltPonOnlineStatusIndex = ($MIBoltPonOnlineStatus . $indexPon);
                        $MIBoltPonOpticalTemperatureIndex = ($MIBoltPonOpticalTemperature . $indexPon);

                        $olt_id = $queryConsultada['id'];
                        $slot_porta = str_replace('"', '', substr(snmp2_get($host,$community,$MIBoltPonNameIndex), 9));
                        $autorizados = substr(snmp2_get($host,$community,$MIBoltPonAuthOnuNumIndex), 9);
                        $amperagem = (substr(snmp2_get($host,$community,$MIBoltPonOpticalCurrentIndex), 9)) * 0.01;
                        $voltagem = (substr(snmp2_get($host,$community,$MIBoltPonOpticalVltageIndex), 9)) * 0.01;
                        $tx_power = (substr(snmp2_get($host,$community,$MIBoltPonTxOpticalPowerIndex), 9)) * 0.01;
                        $status = substr(snmp2_get($host,$community,$MIBoltPonOnlineStatusIndex), 9);
                        $temperatura = (substr(snmp2_get($host,$community,$MIBoltPonOpticalTemperatureIndex), 9)) * 0.01;

                        $DBPonQuery = $mysqli->query("SELECT slot_porta FROM pon WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'");
                        $DBPonFetch = $DBPonQuery->fetch_assoc();


                        if(is_null($DBPonFetch)) {
                                $inserirPON = "INSERT INTO pon (olt_id,slot_porta,autorizados,amperagem,voltagem,tx_power,status,temperatura) VALUES ('$olt_id','$slot_porta','$autorizados','$amperagem','$voltagem','$tx_power','$status','$temperatura')";

                                $mysqli->query($inserirPON) or die($mysqli->error);
                        } else {
                                $atualizarPon = "UPDATE pon SET autorizados='$autorizados', amperagem='$amperagem', voltagem='$voltagem', tx_power='$tx_power', status='$status', temperatura='$temperatura' WHERE olt_id = '$olt_id' AND slot_porta = '$slot_porta'";

                                $mysqli->query($atualizarPon) or die($mysqli->error);
                        }
                }
                // ################# FIM ################

                // ################# PREENCHENDO A TABELA ONU ################
                // ############# INDEX ONU #############
                $MibOnu = "onuPonName";
                $OIDindexOnu = snmp2_real_walk($host,$community,$MibOnu);

                $MIBifName = 'onuPonName.';
                $MIBonuStatus = 'onuStatus.';
                $MIBauthOnuListMac = 'authOnuListMac.';
                $MIBonuPonOpticalTemperature = 'onuPonOpticalTemperature.';
                $MIBonuPonOpticalVltage = 'onuPonOpticalVltage.';
                $MIBonuPonOpticalCurrent = 'onuPonOpticalCurrent.';
                $MIBonuPonRxOpticalPower = 'onuPonRxOpticalPower.';
                $MIBonuPonTxOpticalPower = 'onuPonTxOpticalPower.';


                foreach($OIDindexOnu as $chaveOnu => $valorOnu){
                        //inserindo index nas MIBs para consulta SNMP
                        $indexOnu = substr($chaveOnu, 38);
                        $MIBifNameIndex = ($MIBifName . $indexOnu);
                        $MIBonuStatusIndex = ($MIBonuStatus . $indexOnu);
                        $MIBauthOnuListMacIndex = ($MIBauthOnuListMac . $indexOnu);
                        $MIBonuPonOpticalTemperatureIndex =($MIBonuPonOpticalTemperature . $indexOnu);
                        $MIBonuPonOpticalVltageIndex = ($MIBonuPonOpticalVltage . $indexOnu);
                        $MIBonuPonOpticalCurrentIndex = ($MIBonuPonOpticalCurrent . $indexOnu);
                        $MIBonuPonRxOpticalPowerIndex = ($MIBonuPonRxOpticalPower . $indexOnu);
                        $MIBonuPonTxOpticalPowerIndex = ($MIBonuPonTxOpticalPower . $indexOnu);

                        $pon_id_Index = str_replace('"', '', substr(substr(snmp2_get($host,$community,$MIBifNameIndex), 9),0,9));
                        if($pon_id_Index[strlen($pon_id_Index)-1] == "/"){
                                $pon_id_Index = substr($pon_id_Index,0,8);
                        } else if ($pon_id_Index[strlen($pon_id_Index)-2] == "/" ){
                                $pon_id_Index = substr($pon_id_Index,0,7);
                        }
                        $pon_id_query = $mysqli->query("SELECT pon.id FROM pon INNER JOIN olt ON olt.id = pon.olt_id WHERE olt.ip = '$host' AND  slot_porta = '$pon_id_Index'");
                        $ponConsultada = $pon_id_query->fetch_assoc();
                        $pon_id = $ponConsultada['id'];

                        $posicao = str_replace('"', '', substr(snmp2_get($host,$community,$MIBifNameIndex), 9));
                        $status = substr(snmp2_get($host,$community,$MIBonuStatusIndex), 9);
                        $sn = str_replace('"', '', substr(snmp2_get($host,$community,$MIBauthOnuListMacIndex), 9));
                        $temperatura = floatval((substr(snmp2_get($host,$community,$MIBonuPonOpticalTemperatureIndex), 9)))*0.01;
                        $voltagem = floatval((substr(snmp2_get($host,$community,$MIBonuPonOpticalVltageIndex), 9))) * 0.01;
                        $amperagem = floatval(substr(snmp2_get($host,$community,$MIBonuPonOpticalCurrentIndex), 9)) * 0.01;
                        $rx_power = floatval(substr(snmp2_get($host,$community,$MIBonuPonRxOpticalPowerIndex), 9)) * 0.01;
                        $tx_power = floatval(substr(snmp2_get($host,$community,$MIBonuPonTxOpticalPowerIndex), 9)) * 0.01;

                        $DBOnuQuery = $mysqli->query("SELECT sn FROM onu WHERE sn='$sn'");
                        $DBOnuFetch = $DBOnuQuery->fetch_assoc();
                        $testeNovo = 0;
                        $testeAtualizado = 0;

                        if(is_null($DBOnuFetch)) {
                                $inserirONU = "INSERT INTO onu (pon_id,posicao,status,sn,temperatura,voltagem,amperagem,rx_power,tx_power) VALUES ('$pon_id','$posicao','$status','$sn','$temperatura','$voltagem','$amperagem','$rx_power','$tx_power')";

                                $mysqli->query($inserirONU) or die($mysqli->error);
                        } else {
                                $atualizarPon = "UPDATE onu SET status='$status', temperatura='$temperatura', voltagem='$voltagem', amperagem='$amperagem', rx_power='$rx_power', tx_power='$tx_power' WHERE pon_id='$pon_id' AND posicao='$posicao'";

                                $mysqli->query($atualizarPon) or die($mysqli->error);
                        }
                }
        }
        $fim = date('H:i:s');
        $TempoDecorrito = (strtotime($fim))-strtotime($inicio);
        return date('H:i:s',$TempoDecorrito);
}
?>
