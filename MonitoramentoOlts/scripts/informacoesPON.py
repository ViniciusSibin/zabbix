from conexao import conn
from funcoes import *
from pysnmp.hlapi import *
import re
from datetime import datetime

def informacoesPON():
    inicio = datetime.now().strftime('%H:%M:%S')
    #Cria um cursor do banco de dados
    bd = conn.cursor()

    #Trás todas as OLTs do banco de dados
    bd.execute("SELECT olt.id AS id_olt, olt.ip, olt.comunidade, olt.fabricante, olt.protocolo, olt.nome AS olt_nome, p.id AS id_pon, p.pon_index, p.slot_porta FROM pon p INNER JOIN olt ON olt.id = p.olt_id")

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
        pon_id = onu[6]
        pon_index = onu[7]
        pon_slot_porta = onu[8]

        if olt_fabricante == 'FIBERHOME':
            #######################################
            ############### P O N s ###############
            #######################################
            #Inseindo os index nos OIDs
            pon_descricao_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.3" + "." + str(pon_index)
            pon_autorizados_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.12" + "." + str(pon_index)
            pon_corrente_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.10" + "." + str(pon_index)
            pon_tensao_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.9" + "." + str(pon_index)
            pon_tx_power_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.8" + "." + str(pon_index)
            pon_status_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.5" + "." + str(pon_index)
            pon_temperatura_oid = "1.3.6.1.4.1.5875.800.3.9.3.4.1.11" + "." + str(pon_index)
            
            #Consultando valores
            pon_descricao_val = consult_single_oid(olt_community, olt_ip, pon_descricao_oid)
            pon_autorizados_val = consult_single_oid(olt_community, olt_ip, pon_autorizados_oid)
            pon_corrente_val = consult_single_oid(olt_community, olt_ip, pon_corrente_oid)
            pon_tensao_val = consult_single_oid(olt_community, olt_ip, pon_tensao_oid)
            pon_tx_power_val = consult_single_oid(olt_community, olt_ip, pon_tx_power_oid)
            pon_status_val = consult_single_oid(olt_community, olt_ip, pon_status_oid)
            pon_temperatura_val =consult_single_oid(olt_community, olt_ip, pon_temperatura_oid)

            #Corrigindo valores de retorno
            pon_corrente_val = float(re.sub('[^0-9\.-]', '', pon_corrente_val)) * 0.01
            pon_tensao_val = float(re.sub('[^0-9\.-]', '', pon_tensao_val)) * 0.01
            pon_tx_power_val = float(re.sub('[^0-9\.-]', '', pon_tx_power_val)) * 0.01
            pon_temperatura_val = float(re.sub('[^0-9\.-]', '', pon_temperatura_val)) * 0.01

            
            print(f"\nUPDATE pon SET descricao='{pon_descricao_val}', autorizados='{pon_autorizados_val}', corrente='{pon_corrente_val:.2f}', tensao='{pon_tensao_val:.2f}', tx_power='{pon_tx_power_val:.2f}',status='{pon_status_val}',temperatura='{pon_temperatura_val:.2f}',ult_atualizacao=NOW() WHERE id='{pon_id}'")

            #Atualizando o banco com as novas informações
            bd.execute(f"UPDATE pon SET descricao='{pon_descricao_val}', autorizados='{pon_autorizados_val}', corrente='{pon_corrente_val:.2f}', tensao='{pon_tensao_val:.2f}', tx_power='{pon_tx_power_val:.2f}',status='{pon_status_val}',temperatura='{pon_temperatura_val:.2f}',ult_atualizacao=NOW() WHERE id='{pon_id}'")
           
            # Salva as alterações no banco de dados
            conn.commit()           

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


informacoesPON()