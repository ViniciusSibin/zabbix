from conexao import conn
from funcoes import *
from pysnmp.hlapi import *
import re
from datetime import datetime

def informacoesonu():
    inicio = datetime.now().strftime('%H:%M:%S')
    #Cria um cursor do banco de dados
    bd = conn.cursor()

    #Trás todas as OLTs do banco de dados
    bd.execute("SELECT olt.id AS id_olt, olt.ip, olt.comunidade, olt.fabricante, olt.protocolo, olt.nome AS olt_nome, o.id AS onu_id, o.onu_index  FROM pon p INNER JOIN olt ON olt.id = p.olt_id INNER JOIN onu o ON o.pon_id = p.id")

    #Salva o resultado da consulta em um array
    consultaOnus = bd.fetchall()

    #Ação para cada OLT encontrada
    for onu in consultaOnus:
        olt_id = onu[0]
        olt_ip = onu[1]
        olt_community = onu[2]
        olt_fabricante = onu[3]
        olt_protocolo = onu[4]
        olt_nome = onu[5]
        onu_id = onu[6]
        onu_index = onu[7]

        if olt_fabricante == 'FIBERHOME':
            #######################################
            ############### P O N s ###############
            #######################################
            #Inseindo os index nos OIDs
            onu_status_oid = "" + "." + str(onu_index)
            onu_numeroSerial_oid = "" + "." + str(onu_index)
            onu_temperatura_oid = "" + "." + str(onu_index)
            onu_tensao_oid = "" + "." + str(onu_index)
            onu_corrente_oid = "" + "." + str(onu_index)
            onu_rx_power_oid = "" + "." + str(onu_index)
            onu_tx_power_oid = "" + "." + str(onu_index)
           
            
            
            #Consultando valores
            onu_status_val = consult_single_oid(olt_community, olt_ip, onu_status_oid)
            onu_numeroSerial_val = consult_single_oid(olt_community, olt_ip, onu_numeroSerial_oid)
            onu_temperatura_val = consult_single_oid(olt_community, olt_ip, onu_temperatura_oid)
            onu_tensao_val = consult_single_oid(olt_community, olt_ip, onu_tensao_oid)
            onu_corrente_val = consult_single_oid(olt_community, olt_ip, onu_corrente_oid)
            onu_rx_power_val = consult_single_oid(olt_community, olt_ip, onu_rx_power_oid)
            onu_tx_power_val = consult_single_oid(olt_community, olt_ip, onu_tx_power_oid)


            #Corrigindo valores de retorno
            onu_corrente_val = float(re.sub('[^0-9\.-]', '', onu_corrente_val)) * 0.01
            onu_tensao_val = float(re.sub('[^0-9\.-]', '', onu_tensao_val)) * 0.01
            onu_rx_power_val = float(re.sub('[^0-9\.-]', '', onu_rx_power_val)) * 0.01
            onu_tx_power_val = float(re.sub('[^0-9\.-]', '', onu_tx_power_val)) * 0.01
            onu_temperatura_val = float(re.sub('[^0-9\.-]', '', onu_temperatura_val)) * 0.01

            
            print(f"\nUPDATE onu SET status='{onu_status_val}', sn='{onu_numeroSerial_val}', temperatura='{onu_temperatura_val:.2f}', tensao='{onu_tensao_val:.2f}', corrente='{onu_corrente_val:.2f}',  rx_power='{onu_rx_power_val:.2f}', tx_power='{onu_tx_power_val:.2f}',ult_atualizacao=NOW() WHERE id='{onu_id}'")

            #Atualizando o banco com as novas informações
            #bd.execute(f"UPDATE onu SET descricao='{onu_descricao_val}', autorizados='{onu_autorizados_val}', corrente='{onu_corrente_val:.2f}', tensao='{onu_tensao_val:.2f}', tx_power='{onu_tx_power_val:.2f}',status='{onu_status_val}',temperatura='{onu_temperatura_val:.2f}',ult_atualizacao=NOW() WHERE id='{onu_id}'")
           
            # Salva as alterações no banco de dados
            #conn.commit()           

        elif olt_fabricante == 'VSOLUTION':
            ...
        elif olt_fabricante == 'DATACOM':
            ...
        elif olt_fabricante == 'HUAWEI':
            ...
        elif olt_fabricante == 'INTELBRAS':
            ...
        elif olt_fabricante == 'PARKS':
            ...    
        elif olt_fabricante == 'UBIQUITI':
            ...    
        else:
            print(f"OLT Não identificada")

    fim = datetime.now().strftime('%H:%M:%S')

    inicio_dt = datetime.strptime(inicio, '%H:%M:%S')
    fim_dt = datetime.strptime(fim, '%H:%M:%S')
    tempo_decorrido = fim_dt - inicio_dt

    print(tempo_decorrido)


informacoesonu()