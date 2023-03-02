# Exemplo de uso
import sys
sys.path.append("G:/Programacao/ZABBIX/Scripts/MonitoramentoOlts/lib")
from conexao import conn
from funcoes import *

community = 'mgp'
ip_address = '10.254.1.137'
oid = '1.3.6.1.4.1.37950.1.1.5.10.1.2.1.1.2.2'

valor_oid = consult_single_oid(community, ip_address, oid)
print(f'Valor do OID {oid}: {valor_oid}')