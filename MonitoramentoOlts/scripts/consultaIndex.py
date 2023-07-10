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
        community = olts[5]
        fabricante = olts[3]
        protocolo = olts[4]

        if fabricante == 'FIBERHOME':
            #######################################
            ############### P O N s ###############
            #######################################
            mibOltPonName = "1.3.6.1.4.1.5875.800.3.9.3.4.1.2"

                        # Consulta o OID de índice PON
            pon_index = get_oid_index_teste(ip, community, mibOltPonName)
            if not pon_index:
                print('Não foi possível acessar a: ', olts[1], '-->', olts[2])
                continue
            else:
                for chavePON, valorPON in pon_index:
                  
                    # Extrair a sequência de números de trás para frente até o ponto (INDEX)
                    indexPON = chavePON.rsplit(".", 1)[-1]
                  
                    #Verifica se existe essa PON no banco de dados para a OLT atual
                    bd.execute(f"SELECT * FROM pon WHERE pon_index = {indexPON} AND olt_id = {olts[0]}")

                    #Salva o resultado da consulta em um array
                    consultaIndex = bd.fetchall()

                    if len(consultaIndex) == 0:
                        print(f"INSERT INTO pon (olt_id, pon_index, slot_porta) VALUES ({olts[0]}, '{indexPON}', '{valorPON}')")
                        bd.execute(f"INSERT INTO pon (olt_id, pon_index, slot_porta) VALUES ({olts[0]}, '{indexPON}', '{valorPON}')")
                        
            # # Salva as alterações no banco de dados
            # conn.commit()

            #######################################
            ############### O N U s ###############
            #######################################
           
            mibOnuPonName = "1.3.6.1.4.1.5875.800.3.9.3.3.1.2"

            onu_index = get_oid_index_teste(ip, community, mibOnuPonName)

            if not onu_index:
                print('Não foi possível acessar as ONUs da: ', olts[1], '-->', olts[2])
                continue
            else:
                for chaveONU, valorONU in onu_index:
                    # Extrair a sequência de números de trás para frente até o ponto (INDEX)
                    indexONU = chaveONU.rsplit(".", 1)[-1]

                    #Extrair a pon da posição da ONU
                    ponSplit = valorONU.split('/')
                    pon = ponSplit[0] + '/' + ponSplit[1]

                    #Busca o id da da pon
                    bd.execute(f"SELECT id FROM pon WHERE slot_porta = '{pon}' AND olt_id = {olts[0]}")
                    #Salva o resultado da consulta em um array
                    pon_id = bd.fetchone()

                    bd.execute(f"SELECT * FROM onu WHERE onu_index = '{indexONU}' AND pon_id = '{pon_id[0]}'")
                    #Salva o resultado da consulta em um array
                    consultaIndex = bd.fetchall()

                    if len(consultaIndex) == 0:
                        print(f"INSERT INTO onu (pon_id, onu_index, posicao) VALUES ({pon_id[0]}, '{indexONU}', '{valorONU}')")
                        bd.execute(f"INSERT INTO onu (pon_id, onu_index, posicao) VALUES ({pon_id[0]}, '{indexONU}', '{valorONU}')")
                      
            # Salva as alterações no banco de dados
            conn.commit()
           

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