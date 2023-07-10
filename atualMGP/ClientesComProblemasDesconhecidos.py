#!/usr/bin/python3
from pyzabbix import ZabbixAPI
import math
import sys

###########################################
######## D E S C O N H E C I D O S ########
###########################################

zabbix_server = "http://localhost/zabbix"
username = "Admin"
password = "zabbix"

zapi = ZabbixAPI(server=zabbix_server)
zapi.login(username, password)

array = sys.argv
hostName = array[1]

items = zapi.item.get(host=hostName)
dados0 = []

for i in items :
   if  i.get('description') == "ONUSTATUS":
      if  (i.get('lastvalue')) == '3':
        dados = (i.get('lastvalue'))
        dados0.append(dados)

print (len(dados0))
