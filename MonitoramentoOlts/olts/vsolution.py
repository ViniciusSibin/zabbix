import re
import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
from funcoes import *
from pysnmp.hlapi import *

# Criando um cursor
bd = conn.cursor()

# Executando uma consulta SELECT
bd.execute("SELECT * FROM olt WHERE fabricante='VSOLUTION' AND comunidade IS NOT NULL AND versaoSNMP IS NOT NULL AND ip IS NOT NULL")

# Obtendo os resultados da consulta
resultados = bd.fetchall()

# Exibindo os resultados
for olts in resultados:
    # Configurações de conexão SNMP
    # ip = olts[2]
    # community = olts[5]
    ip = '192.168.21.1'
    community = 'mgp'

    # OIDs a serem consultados
    pon_index_oid = ["1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.1"]
    pon_nome_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2"
    pon_corrente_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.4"
    pon_tx_power_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5"
    pon_status_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.6"
    pon_tensao_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3"
    pon_temperatura_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2"

    # Consulta o OID de índice PON
    for oid in pon_index_oid:
        pon_index = get_oid_index(ip, community, oid)
        if not pon_index:
            print('Não foi possível acessar a: ', olts[1], '-->', olts[2])
            continue
        else:
            for i in pon_index:
                #Salvando o index nos OIDs
                pon_nome = pon_nome_oid + "." + str(i)
                pon_corrente = pon_corrente_oid + "." + str(i)
                pon_tx_power = pon_tx_power_oid + "." + str(i)
                pon_status = pon_status_oid + "." + str(i)
                pon_tensao = pon_tensao_oid + "." + str(i)
                pon_temperatura = pon_temperatura_oid + "." + str(i)
                
                #Consultando os valores dos OIDs
                pon_nome_val = consult_single_oid(community, ip, pon_nome)
                if '0x504f' in pon_nome_val or not pon_nome_val:
                    # tratamento do valor encontrado
                    pon_nome_val = f'PON0/{i}'
                pon_corrente_val = consult_single_oid(community, ip, pon_corrente)
                print(pon_corrente_val)
                pon_tx_power_val = consult_single_oid(community, ip, pon_tx_power)
                pon_status_val = consult_single_oid(community, ip, pon_status)
                pon_tensao_val = consult_single_oid(community, ip, pon_tensao)
                pon_temperatura_val = consult_single_oid(community, ip, pon_temperatura)

                pon_corrente_val = re.sub('[^0-9\.]', '', pon_corrente_val)
                pon_tx_power_val = re.sub('[^0-9\.]', '', pon_tx_power_val)
                pon_tensao_val = re.sub('[^0-9\.]', '', pon_tensao_val)
                pon_temperatura_val = re.sub('[^0-9\.]', '', pon_temperatura_val)

                # Cria a string de comando SQL para inserir no banco de dados
                query = f"INSERT INTO pon (olt_id, pon_index, slot_porta, autorizados, amperagem, tensao, tx_power, status, temperatura) VALUES ({olts[0]}, {i}, '{pon_nome_val}', {pon_corrente_val}, {pon_tensao_val}, {pon_tx_power_val}, {pon_status_val}, {pon_temperatura_val})"
                print(query)
                # Executa a query SQL
                #bd.execute(query)

                # Salva as alterações no banco de dados
                #conn.commit()

                # Fecha a conexão com o banco de dados
                #conn.close()