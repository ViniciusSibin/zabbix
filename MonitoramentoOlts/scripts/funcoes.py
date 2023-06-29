from pysnmp.hlapi import *
from conexao import *

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

def get_oid_index_teste(ip, community, oid):
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
                chave = varBind[0].prettyPrint()  # Obtém a chave
                valor = varBind[1].prettyPrint()  # Obtém o valor
                result.append((chave, valor))  # Adiciona a chave e o valor como uma tupla à lista
    return result


def consult_single_oid(community, ip_address, oid):
    errorIndication, errorStatus, errorIndex, varBinds = next(
        getCmd(SnmpEngine(),
                CommunityData(community),
                UdpTransportTarget((ip_address, 161)),
                ContextData(),
                ObjectType(ObjectIdentity(oid)),
                lexicographicMode=False)
    )
    if errorIndication:
        print(errorIndication)
        return False
    elif errorStatus:
        print('%s at %s' % (errorStatus.prettyPrint(),
                            errorIndex and varBinds[int(errorIndex) - 1][0] or '?'))
        return False
    else:
        value = varBinds[0][1]
        if isinstance(value, OctetString):
            return value.prettyPrint().strip().strip('"').encode('utf-8').decode('ascii')
        else:
            return str(value)
        

def autorizados(pon_id):
    consultaPON = conn.cursor()
    consultaPON.execute(f"SELECT status FROM onu WHERE pon_id = {pon_id}")

    ponReturn = consultaPON.fetchall()
    soma = 0
    
    for autorizados in ponReturn:
        soma =+ soma + 1
    
    return soma