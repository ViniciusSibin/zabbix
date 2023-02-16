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
            result = False
            break
        elif errorStatus:
            result = False
            break
        else:
            for varBind in varBinds:
                result.append((varBind[1].prettyPrint()))

    return result