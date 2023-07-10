#!/usr/bin/python3
from pyzabbix import ZabbixAPI
import math
zabbix_server = 'http://172.31.255.51/zabbix/'
username = 'Admin'
password = 'zabbix'

zapi = ZabbixAPI(server=zabbix_server)
zapi.login(username, password)

#items = zapi.item.get(hostid=10508, output=['application', 'name', 'lastvalue'],search='PON')
#items = zapi.item.get ({"output": "extend", "filter"{'host':'OLT FIBERHOME 29 - SDI'},"search":{'name':'Status'}})
items = zapi.item.get(output='name', filter={'host':hostName, 'state':1}, search:{'name':"Status"})



print("Buscou o Host")

for item in items:
	if item['lastvalue'] == '1':	
		print item

#    for item in items:
#        print "ItemID: {} - Item: {} - Key: {}".format(item['itemid'], item['name'], item['key_'])
#
#        values = zapi.history.get(itemids=item['itemid'], time_from=fromTimestamp, time_till=tillTimestamp, history=item['value_type'])



#if (i = items.(aplication)) == 'status':
#	if (item.get('name'))== status:
#        	if (item.get('lastvalue')) == '1':
#            		dados = (item.get('lastvalue'))
#            		dados0.append(dados)
   
#print (len(dados0))
