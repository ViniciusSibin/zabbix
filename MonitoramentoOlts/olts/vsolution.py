import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
from funcoes import *

def vsolution():
    # Criando um cursor
    bd = conn.cursor()

    # Executando uma consulta SELECT
    bd.execute("SELECT * FROM olt WHERE fabricante='VSOLUTION' AND comunidade IS NOT NULL AND versaoSNMP IS NOT NULL AND ip IS NOT NULL")

    # Obtendo os resultados da consulta
    resultados = bd.fetchall()
    
    
    # Exibindo os resultados
    for olts in resultados:
        ip = olts[2]
        community = olts[5]
        """
        #Atualiza o nome das OLTs no banco de dados
        sysName = ['1.3.6.1.2.1.1.5']
        
        for oid in sysName:
            result = get_oid_index(ip, community, oid)
            if result:
                if result[0][1] != olts[1] and result[0][1] != '':
                    updateNome = "UPDATE olt SET nome='" + result[0][1] + "' WHERE ip='" + olts[2] + "'"
                    bd.execute(updateNome)
                    conn.commit()
                    print(olts[0], '-->', olts[1], '-->', olts[2] , '-->', result)
                # elif result[0][1] == '':
                #     print("OLTs Sem nome")
                #     print(olts[0], '-->', olts[1], '-->', olts[2] , '-->', result)
        """

        #Coleta as informações das PONs
        oid_pon_index = ['1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.1']
        
        for oid in oid_pon_index:
            result = get_oid_index(ip, community, oid)
            if not result:
                print('Não foi possível acessar a: ', olts[1], '-->', olts[2])
            else:
                for pon_index in result:
                    oid_pon_nome = '1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2.' + str(pon_index)
                    print(oid_pon_nome)
                    oid_pon_corrente = '1.3.6.1.4.1.37950.1.1.5.10.13.1.1.4.' + str(pon_index)
                    oid_pon_tx_Power = '1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5.' + str(pon_index)
                    oid_pon_status = '1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.6.' + str(pon_index)
                    oid_pon_tensao = '1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3.' + str(pon_index)
                    oid_pon_temperatura = '1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2.' + str(pon_index)

                    pon_nome = get_oid_index(ip, community, oid_pon_nome)
                    print(pon_nome)
                    pon_corrente = get_oid_index(ip, community, oid_pon_corrente)
                    pon_tx_Power = get_oid_index(ip, community, oid_pon_tx_Power)
                    pon_status = get_oid_index(ip, community, oid_pon_status)
                    pon_tensao = get_oid_index(ip, community, oid_pon_tensao)
                    pon_temperatura = get_oid_index(ip, community, oid_pon_temperatura)
                    
                    print('INDEX:', pon_index, 'NOME: ', pon_nome, 'CORRENTE: ', pon_corrente, 'TX POWER: ', pon_tx_Power, 'STATUS: ', pon_status, 'TENSÃO: ', pon_tensao, 'TEMPERATURA: ', pon_temperatura)

        #Coleta as informações das ONUs
        """oid_onu_onu_index = ['1.3.6.1.4.1.37950.1.1.6.1.1.2.1.2']
        oid_onu_pon_Index = ['1.3.6.1.4.1.37950.1.1.6.1.1.2.1.1']
        oid_onu_posição = ['']
        oid_onu_numero_serial = ['1.3.6.1.4.1.37950.1.1.6.1.1.2.1.5.{PONINDEX}']
        oid_onu_temperatura = ['1.3.6.1.4.1.37950.1.1.6.1.1.3.1.3.{PONINDEX}']
        oid_onu_tensao = ['1.3.6.1.4.1.37950.1.1.6.1.1.3.1.4.{PONINDEX}']
        oid_onu_corrente = ['1.3.6.1.4.1.37950.1.1.6.1.1.3.1.5.{PONINDEX}']
        oid_onu_rx_Power = ['1.3.6.1.4.1.37950.1.1.6.1.1.3.1.7.{PONINDEX}']
        oid_onu_tx_Power = ['1.3.6.1.4.1.37950.1.1.6.1.1.3.1.6.{PONINDEX}']
        oid_onu_status = ['1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.{PONINDEX}']"""


    # Fechando a conexão
    conn.close()

vsolution()