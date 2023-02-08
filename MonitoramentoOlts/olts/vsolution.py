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

        #Atualiza o nome da OLT no banco
        sysName = ['1.3.6.1.2.1.1.5']
        for oid in sysName:
            result = get_oid_index(ip, community, oid)
            if result:
                if result[0][1] != olts[1] and result[0][1] != '':
                    updateNome = "UPDATE olt SET nome='" + result[0][1] + "' WHERE ip='" + olts[2] + "'"
                    bd.execute(updateNome)
                    conn.commit()
                    print(olts[0], '-->', olts[1], '-->', olts[2] , '-->', result)

        #Atualiza as PONs
        ponPortDownSpeed = ['1.3.6.1.4.1.37950.1.1.5.10.1.2.2.1.24']

    # Fechando a conexão
    conn.close()

vsolution()