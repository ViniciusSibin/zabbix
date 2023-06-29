from conexao import conn
from funcoes import *
from pysnmp.hlapi import *

def consultaIndex():
    #Cria um cursor do banco de dados
    bd = conn.cursor()

    #Trás todas as OLTs do banco de dados
    bd.execute("SELECT * FROM olt")

    #Salva o resultado da consulta em um array
    consultaOlts = bd.fetchall()

    #Ação para cada OLT encontrada
    for olts in consultaOlts:
        ip = olts[2]
        community = olts[6]
        fabricante = olts[3]
        protocolo = olts[4]
        versaoSNMP = olts[5]

        if fabricante == 'FIBERHOME':
            #######################################
            ############### P O N s ###############
            #######################################
            mibOltPonName = ["1.3.6.1.4.1.5875.800.3.9.3.4.1.2"]

            # Consulta o OID de índice PON
            for oidPON in mibOltPonName:
                pon_index = get_oid_index_teste(ip, community, oidPON)
                if not pon_index:
                    print('Não foi possível acessar a: ', olts[1], '-->', olts[2])
                    continue
                else:
                    for chavePON, valorPON in pon_index:
                        
                        # Extrair a sequência de números de trás para frente até o ponto (INDEX)
                        indexPON = chavePON.rsplit(".", 1)[-1]
                        
                        #Trás todas as OLTs do banco de dados
                        bd.execute(f"SELECT * FROM pon WHERE pon_index = {indexPON} AND olt_id = {olts[0]}")

                        #Salva o resultado da consulta em um array
                        consultaIndex = bd.fetchall()

                        if len(consultaIndex) == 0:
                            bd.execute(f"INSERT INTO pon (olt_id, pon_index, slot_porta) VALUES ({olts[0]}, {indexPON}, {valorPON})")

            #######################################
            ############### O N U s ###############
            #######################################

            mibOnuPonName = ["1.3.6.1.4.1.5875.800.3.9.3.3.1.2"]

            for oidONU in mibOnuPonName:
                onu_index = get_oid_index_teste(ip, community, oidONU)

                if not onu_index:
                    print('Não foi possível acessar a: ', olts[1], '-->', olts[2])
                    continue
                else:
                    for chaveONU, valorONU in onu_index:
                        
                        # Extrair a sequência de números de trás para frente até o ponto (INDEX)
                        indexONU = chavePON.rsplit(".", 1)[-1]
                        
                        #Trás todas as OLTs do banco de dados
                        bd.execute(f"SELECT * FROM onu WHERE pon_index = {indexONU} AND olt_id = {olts[0]}")

        elif fabricante == 'VSOLUTION':
            ...
        elif fabricante == 'DATACOM':
            ...
        elif fabricante == 'HUAWEI':
            ...
        elif fabricante == 'INTELBRAS':
            ...
        elif fabricante == 'PARKS':
            ...    
        elif fabricante == 'UBIQUITI':
            ...    
        else:
            print(f"OLT Não identificada")


consultaIndex()