"""from pysnmp.hlapi import *

result = []
oid = '1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.1.{SNMP.INDEX}'

for (errorIndication, errorStatus, errorIndex, varBinds) in nextCmd(SnmpEngine(),
                              CommunityData('mgp'),
                              UdpTransportTarget(('10.254.1.169', 161)),
                              ContextData(),
                              ObjectType(ObjectIdentity(oid)),
                              lexicographicMode=False):
    if errorIndication:
        print(errorIndication)
        break
    elif errorStatus:
        print('%s at %s' % (errorStatus.prettyPrint(), errorIndex and varBinds[int(errorIndex) - 1][0] or '?'))
        break
    else:
        for varBind in varBinds:
            result.append(varBind[1].prettyPrint())

print(result)"""
from pysnmp.hlapi import *

def get_oid_index(ip, community, oid):
    result = []
    for (errorIndication,
         errorStatus,
         errorIndex,
         varBinds) in nextCmd(SnmpEngine(),
                              CommunityData(community),
                              UdpTransportTarget((ip, 161)),
                              ContextData(),
                              ObjectType(ObjectIdentity(oid)),
                              lexicographicMode=False):
        if errorIndication:
            print(errorIndication)
            break
        elif errorStatus:
            print('%s at %s' % (errorStatus.prettyPrint(),
                                errorIndex and varBinds[int(errorIndex) - 1][0] or '?'))
            break
        else:
            for varBind in varBinds:
                result.append((varBind[0].prettyPrint().split(".")[-1], varBind[1].prettyPrint()))

    return result

oids = ['1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.1']

ip = '10.254.1.169'
community = 'mgp'

for oid in oids:
    result = get_oid_index(ip, community, oid)
    print(result)