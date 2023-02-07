import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
from funcoes import *

def vsolution():
    # Criando um cursor
    bd = conn.cursor()

    # Executando uma consulta SELECT
    bd.execute("SELECT * FROM olt")

    # Obtendo os resultados da consulta
    resultados = bd.fetchall()

    # Exibindo os resultados
    for linha in resultados:
        print(linha)

    # Fechando a conexão
    conn.close()

    #oids = ['1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.1']
    """oids = ['1.3.6.1.2.1.1.5']

    ip = '192.168.21.1'
    community = 'mgp'

    for oid in oids:
        result = get_oid_index(ip, community, oid)
        print(result)"""


vsolution()