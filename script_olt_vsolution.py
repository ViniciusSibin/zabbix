from pysnmp.hlapi import *

oid = "1.3.6.1.4.1.37950.1.1.6.1.1.1.1.4.1"  # OID que deseja consultar
result = []  # Array para armazenar o resultado

# Realiza a consulta SNMP GET
errorIndication, errorStatus, errorIndex, varBinds = nextCmd(SnmpEngine(),
                              CommunityData('mgp'),
                              UdpTransportTarget(('10.254.1.169', 161)),
                              ContextData(),
                              ObjectType(ObjectIdentity(oid)))

# Verifica se a consulta foi realizada com sucesso
if errorIndication:
    print(errorIndication)
else:
    if errorStatus:
        print('%s at %s' % (errorStatus.prettyPrint(),
                            errorIndex and varBinds[int(errorIndex) - 1][0] or '?'))
    else:
        for varBind in varBinds:
            # Armazena o resultado na array
            result.append(varBind[1].prettyPrint())

print(result)