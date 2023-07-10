import re
import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
from funcoes import *
from pysnmp.hlapi import *


def vsolution():
    # Criando um cursor
    bd = conn.cursor()

    #Limpando dados da VSOLUTION
    bd.execute("delete from onu where pon_id in (select id from pon p where p.olt_id in (select id from olt where olt.fabricante = 'VSOLUTION'))")
    bd.execute("delete from pon where olt_id in (select id from olt where olt.fabricante = 'VSOLUTION')")
    conn.commit()

    # Executando uma consulta SELECT
    bd.execute("SELECT * FROM olt WHERE fabricante='VSOLUTION' AND comunidade IS NOT NULL AND versaoSNMP IS NOT NULL AND ip IS NOT NULL AND protocolo = 'EPON'")

    # Obtendo os resultados da consulta
    resultados = bd.fetchall()

    # Exibindo os resultados
    for olts in resultados:
        # Configurações de conexão SNMP
        ip = olts[2]
        community = olts[6]
        # ip = '192.168.21.1'
        # community = 'mgp'

        #######################################
        ############### P O N s ###############
        #######################################

        # OIDs a serem consultados
        pon_index_oid = ["1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.1"]
        pon_nome_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2"
        pon_corrente_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.4"
        pon_tx_power_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5"
        pon_status_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.6"
        pon_tensao_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3"
        pon_temperatura_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2"
        pon_descricao_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.14"

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
                    pon_descricao = pon_descricao_oid + "." + str(i)
                    
                    #Consultando os valores dos OIDs
                    pon_nome_val = consult_single_oid(community, ip, pon_nome)
                    if not pon_nome_val or '0x504f' in pon_nome_val or pon_nome_val == '':
                        # tratamento do valor encontrado
                        pon_nome_val = f'PON0/{i}'
                    pon_corrente_val = consult_single_oid(community, ip, pon_corrente)
                    pon_tx_power_val = consult_single_oid(community, ip, pon_tx_power)
                    pon_status_val = consult_single_oid(community, ip, pon_status)
                    pon_tensao_val = consult_single_oid(community, ip, pon_tensao)
                    pon_temperatura_val = consult_single_oid(community, ip, pon_temperatura)
                    pon_descricao_val = consult_single_oid(community, ip, pon_descricao)

                    pon_corrente_val = re.sub('[^0-9\.-]', '', str(pon_corrente_val))
                    pon_tx_power_val = re.sub('[^0-9\.-]', '', str(pon_tx_power_val))
                    pon_tensao_val = re.sub('[^0-9\.-]', '', str(pon_tensao_val))
                    pon_temperatura_val = re.sub('[^0-9\.-]', '', str(pon_temperatura_val))

                    if pon_tx_power_val:
                        pon_tx_power_val = float(pon_tx_power_val)

                        if pon_tx_power_val != 0:
                            pon_status_val = 1

                    # Cria a string de comando SQL para inserir no banco de dados
                    queryPON = f"INSERT INTO pon (olt_id, pon_index, slot_porta, bairro, amperagem, tensao, tx_power, status, temperatura) VALUES ({olts[0]}, {i}, '{pon_nome_val}', '{pon_descricao_val}', '{pon_corrente_val}', '{pon_tensao_val}', '{pon_tx_power_val}', '{pon_status_val}', '{pon_temperatura_val}')"
                    print(queryPON)
                    # Executa a query SQL
                    bd.execute(queryPON)

                    # Salva as alterações no banco de dados
                    conn.commit()

                    #######################################
                    ############### O N U s ###############
                    #######################################
                    #Verificando se é GPON
                    if olts[4] == 'GPON':
                        # OIDs a serem consultados
                        
                        onu_index_oid = ["1.3.6.1.4.1.37950.1.1.6.1.1.2.1.2" + "." + str(i)]
                        onu_serial_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.2.1.5" + "." + str(i)
                        onu_corrente_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.5" + "." + str(i)
                        onu_tx_power_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.6" + "." + str(i)
                        onu_rx_power_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.7" + "." + str(i)
                        onu_status_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4" + "." + str(i)
                        onu_tensao_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.4" + "." + str(i)
                        onu_temperatura_oid = "1.3.6.1.4.1.37950.1.1.6.1.1.3.1.3" + "." + str(i)


                        # Consulta o OID de índice PON
                        for oidONU in onu_index_oid:
                            onu_index = get_oid_index(ip, community, oidONU)
                            if not onu_index:
                                print('ONU Não encontrada')
                                continue
                            else:
                                for onuIndex in onu_index:
                                    onu_serial = onu_serial_oid + "." + str(onuIndex)
                                    onu_corrente = onu_corrente_oid + "." + str(onuIndex)
                                    onu_tx_power = onu_tx_power_oid + "." + str(onuIndex)
                                    onu_rx_power = onu_rx_power_oid + "." + str(onuIndex)
                                    onu_status = onu_status_oid + "." + str(onuIndex)
                                    onu_tensao = onu_tensao_oid + "." + str(onuIndex)
                                    onu_temperatura = onu_temperatura_oid + "." + str(onuIndex)
                                    
                                    #Consultando os valores dos OIDs
                                    onu_serial_val = consult_single_oid(community, ip, onu_serial)
                                    if not onu_serial_val or '0x504f' in onu_serial_val or onu_serial_val == '':
                                        # tratamento do valor encontrado
                                        onu_serial_val = f'PON0/{i}'
                                    onu_corrente_val = consult_single_oid(community, ip, onu_corrente)
                                    onu_tx_power_val = consult_single_oid(community, ip, onu_tx_power)
                                    onu_rx_power_val = consult_single_oid(community, ip, onu_rx_power)
                                    onu_status_val = consult_single_oid(community, ip, onu_status)
                                    onu_tensao_val = consult_single_oid(community, ip, onu_tensao)
                                    onu_temperatura_val = consult_single_oid(community, ip, onu_temperatura)

                                    #deixando somente valores númericos
                                    onu_corrente_val = re.sub('[^0-9\.-]', '', str(onu_corrente_val))
                                    onu_tx_power_val = re.sub('[^0-9\.-]', '', str(onu_tx_power_val))
                                    onu_rx_power_val = re.sub('[^0-9\.-]', '', str(onu_rx_power_val))
                                    onu_tensao_val = re.sub('[^0-9\.-]', '', str(onu_tensao_val))
                                    onu_temperatura_val = re.sub('[^0-9\.-]', '', str(onu_temperatura_val))

                                    #coletando pon_id do banco de dados
                                    ponQuery = f"SELECT id, slot_porta FROM pon where olt_id='{olts[0]}' AND slot_porta='{pon_nome_val}'"
                                    bd.execute(ponQuery)

                                    # Obtendo os resultados da consulta
                                    pon_resultado = bd.fetchall()
                                    if len(pon_resultado) > 0:
                                        ponID = pon_resultado[0][0]
                                        
                                        #Criando a posicao
                                        posicao = str(pon_resultado[0][1]) + "/" + str(onuIndex)
                                    else:
                                        print("Nenhum resultado encontrado")
                                    
                                    # Cria a string de comando SQL para inserir no banco de dados
                                    
                                    queryONU = f"INSERT INTO onu (pon_id, onu_index, posicao, status, sn, temperatura, voltagem, amperagem, rx_power, tx_power) VALUES ('{ponID}', '{onuIndex}', '{posicao}', '{onu_status_val}', '{onu_serial_val}', '{onu_temperatura_val}', '{onu_tensao_val}', '{onu_corrente_val}', '{onu_rx_power_val}', '{onu_tx_power_val}')"
                                    print(queryONU)
                                    # Executa a query SQL
                                    bd.execute(queryONU)

                                    # Salva as alterações no banco de dados
                                    conn.commit()
                                    
                        autorizadas = autorizados(ponID)
                        queryAutorizados = f"UPDATE pon SET autorizados='{autorizadas}' WHERE id='{ponID}';"
                        # Executa a query SQL
                        bd.execute(queryAutorizados)

                        # Salva as alterações no banco de dados
                        conn.commit()
                    #Verificando se é EPON                
                    elif olts[4] == 'EPON':
                            # OIDs a serem consultados
                        
                        onu_index_oid = ["1.3.6.1.4.1.37950.1.1.5.12.1.9.1.1"]
                        onu_mac_oid = "1.3.6.1.4.1.37950.1.1.5.12.1.9.1.5"
                        onu_desc_oid = "1.3.6.1.4.1.37950.1.1.5.12.1.12.1.10"
                        onu_corrente_oid = "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.5" + "." + str(i)
                        onu_tx_power_oid = "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.6" + "." + str(i)
                        onu_rx_power_oid = "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.7" + "." + str(i)
                        onu_status_oid = "1.3.6.1.4.1.37950.1.1.5.12.1.9.1.4"
                        onu_tensao_oid = "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.4" + "." + str(i)
                        onu_temperatura_oid = "1.3.6.1.4.1.37950.1.1.5.12.2.1.8.1.3" + "." + str(i)


                        # Consulta o OID de índice PON
                        for oidONU in onu_index_oid:
                            onu_index = get_oid_index(ip, community, oidONU)
                            if not onu_index:
                                print('ONU Não encontrada')
                                continue
                            else:
                                for onuIndex in onu_index:
                                    onu_mac = onu_mac_oid + "." + str(onuIndex)
                                    onu_desc = onu_desc_oid + "." + str(onuIndex)
                                    onu_corrente = onu_corrente_oid + "." + str(onuIndex)
                                    onu_tx_power = onu_tx_power_oid + "." + str(onuIndex)
                                    onu_rx_power = onu_rx_power_oid + "." + str(onuIndex)
                                    onu_status = onu_status_oid + "." + str(onuIndex)
                                    onu_tensao = onu_tensao_oid + "." + str(onuIndex)
                                    onu_temperatura = onu_temperatura_oid + "." + str(onuIndex)
                                    
                                    #Consultando os valores dos OIDs
                                    onu_mac_val = consult_single_oid(community, ip, onu_mac)
                                    if not onu_mac_val or '0x504f' in onu_mac_val or onu_mac_val == '':
                                        # tratamento do valor encontrado
                                        onu_mac_val = f'PON0/{i}'
                                    onu_desc_val = consult_single_oid(community, ip, onu_desc)
                                    onu_corrente_val = consult_single_oid(community, ip, onu_corrente)
                                    onu_tx_power_val = consult_single_oid(community, ip, onu_tx_power)
                                    onu_rx_power_val = consult_single_oid(community, ip, onu_rx_power)
                                    onu_tensao_val = consult_single_oid(community, ip, onu_tensao)
                                    onu_temperatura_val = consult_single_oid(community, ip, onu_temperatura)

                                    #deixando somente valores númericos
                                    onu_corrente_val = re.sub('[^0-9\.-]', '', str(onu_corrente_val))
                                    onu_tx_power_val_filtro = re.search(r'\((-?\d+\.\d+)\s+dBm\)', onu_tx_power_val)
                                    onu_rx_power_val_filtro = re.search(r'\((-?\d+\.\d+)\s+dBm\)', onu_rx_power_val)
                                    onu_tensao_val = re.sub('[^0-9\.-]', '', str(onu_tensao_val))
                                    onu_temperatura_val = re.sub('[^0-9\.-]', '', str(onu_temperatura_val))

                                    try:
                                        onu_tx_power_val = float(onu_tx_power_val_filtro.group(1))
                                        onu_rx_power_val = float(onu_rx_power_val_filtro.group(1))

                                        if onu_rx_power_val != 0:
                                            onu_status_val = 1
                                        
                                            
                                    except:
                                        onu_status_val = 0

                                    #coletando pon_id do banco de dados
                                    ponQuery = f"SELECT id, slot_porta FROM pon where olt_id='{olts[0]}' AND slot_porta='{pon_nome_val}'"
                                    bd.execute(ponQuery)

                                    # Obtendo os resultados da consulta
                                    pon_resultado = bd.fetchall()
                                    if len(pon_resultado) > 0:
                                        ponID = pon_resultado[0][0]
                                        
                                        #Criando a posicao
                                        posicao = str(pon_resultado[0][1]) + "/" + str(onuIndex)
                                    else:
                                        print("Nenhum resultado encontrado")
                                    
                                    # Cria a string de comando SQL para inserir no banco de dados
                                    
                                    queryONU = f"INSERT INTO onu (pon_id, onu_index, posicao, status, sn, descricao, temperatura, voltagem, amperagem, rx_power, tx_power) VALUES ('{ponID}', '{onuIndex}', '{posicao}', '{onu_status_val}', '{onu_mac_val}', '{onu_desc_val}', '{onu_temperatura_val}', '{onu_tensao_val}', '{onu_corrente_val}', '{onu_rx_power_val}', '{onu_tx_power_val}')"
                                    print(queryONU)
                                    # Executa a query SQL
                                    bd.execute(queryONU)

                                    # Salva as alterações no banco de dados
                                    conn.commit()
                                    
                        autorizadas = autorizados(ponID)
                        queryAutorizados = f"UPDATE pon SET autorizados='{autorizadas}' WHERE id='{ponID}';"
                        # Executa a query SQL
                        bd.execute(queryAutorizados)

                        # Salva as alterações no banco de dados
                        conn.commit()
                    else:
                        print('OLT SEM PROTOCOLO')
    # Fecha a conexão com o banco de dados
    conn.close()


vsolution()