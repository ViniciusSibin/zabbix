#!/usr/bin/python3
from pyzabbix import ZabbixAPI
#from zabbix_api import ZabbixAPI
import math
import sys

#############################################
################ O N L I N E ################
#############################################
zabbix_server = "http://localhost/zabbix"
zabbix_username = "Admin"
zabbix_password = "zabbix"

zapi = ZabbixAPI(server=zabbix_server)
zapi.login(zabbix_username, zabbix_password)

array = sys.argv
hostName = array[1]
ponName = array[2]
ponName = ponName + '/'

items = zapi.item.get(host=hostName)
dados0 = []

for i in items:
	if i.get('description') == "ONUSTATUS":
		if i.get('lastvalue') != '1':
			nomeOnu = i.get('name')
			if nomeOnu[:len(ponName)] == ponName:
				dados = (i.get('lastvalue'))
				dados0.append(dados)
print (len(dados0))
