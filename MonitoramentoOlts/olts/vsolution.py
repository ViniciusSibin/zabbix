import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
import easysnmp

# Criando um cursor
bd = conn.cursor()

# Executando uma consulta SELECT
bd.execute("SELECT * FROM olt WHERE fabricante='VSOLUTION' AND comunidade IS NOT NULL AND versaoSNMP IS NOT NULL AND ip IS NOT NULL")

# Obtendo os resultados da consulta
resultados = bd.fetchall()

# Exibindo os resultados
for olts in resultados:
    # Configurações de conexão SNMP
    ip = olts[2]
    community = olts[5]

    # OIDs a serem consultados
    pon_index_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.1"
    pon_nome_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2"
    pon_corrente_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.4"
    pon_tx_power_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.5"
    pon_status_oid = "1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.6"
    pon_tensao_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.3"
    pon_temperatura_oid = "1.3.6.1.4.1.37950.1.1.5.10.13.1.1.2"

    # Consulta o OID de índice PON
    session = easysnmp.Session(hostname=ip, community=community, version=2)
    pon_index = session.get(pon_index_oid).value

    # Loop para consultar os valores dos OIDs para cada índice PON
    for i in range(int(pon_index)):
        pon_nome = pon_nome_oid + "." + str(i+1)
        pon_corrente = pon_corrente_oid + "." + str(i+1)
        pon_tx_power = pon_tx_power_oid + "." + str(i+1)
        pon_status = pon_status_oid + "." + str(i+1)
        pon_tensao = pon_tensao_oid + "." + str(i+1)
        pon_temperatura = pon_temperatura_oid + "." + str(i+1)

        # Consulta os valores dos OIDs
        pon_nome_val = session.get(pon_nome).value
        pon_corrente_val = session.get(pon_corrente).value
        pon_tx_power_val = session.get(pon_tx_power).value
        pon_status_val = session.get(pon_status).value
        pon_tensao_val = session.get(pon_tensao).value
        pon_temperatura_val = session.get(pon_temperatura).value

        # Cria a string de comando SQL para inserir no banco de dados
        query = f"INSERT INTO pon (olt_id, pon_index, slot_porta, autorizados, amperagem, tensao, tx_power, status, temperatura) VALUES ({i}, {pon_index}, '{pon_nome_val}', {pon_corrente_val}, {pon_tensao_val}, {pon_tx_power_val}, {pon_status_val}, {pon_temperatura_val})"
        print(query)
        # Executa a query SQL
        #bd.execute(query)

        # Salva as alterações no banco de dados
        #conn.commit()

        # Fecha a conexão com o banco de dados
        #conn.close()