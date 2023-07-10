#
#
# Requisitos:
#
#
#
# uso: ./pon_conectados.sh
#
# Versão 1.0: Realiza descoberta de portas PON e faz a contagem do total de clientes online por ponta PON
#
# Agosto 2021, Evandro José Zipf
# www.nototi.com.br

#-----------------------------------------[ Inicia as chaves desativadas ]-----------------------------------------

online=0
offline=0
total=0
discovery=0
snmp=0

SNMP_ARQUIVO="/tmp/$4"
SNMP_TOTAL="/tmp/$4.total"

#-----------------------------------------[ Informações Zabbix ]-----------------------------------------

	MENSAGEM_USO="
		Uso: $(basename "$0")[-snmp|-discovery|-online|-offline|-V|-h]
		
		-snmp gera os arquivos para leitura e contagem das portas PON e clientes online
		-discovery faz a descoberta das portas PON em formato JSON
		-online contagem de ONU online
		-total total de clientes conectados na OLT
		-V mostra versão do script
		-h mostra a ajuda
		
		Ex: ./pon_conectados.sh -online slot pon arquivotemp
		Ex: ./pon_conectados.sh -discovery comunidade_snmp IPdaOLT arquivotemp	
		"
		
		#Tem que passar 3 parâmetros
		[ $# -ne 4] && {
			echo "$MENSAGEM_USO"
			exit 0;
		}
		
		case "$1" in
			#Opções de liga e desligam chaves
			
			-online) online=1 ;;
			
			-snmp) snmp=1 ;;
			
			-total) total=1 ;;
			
			-discovery) discovery=1 ;;			
	
			-h|--help)
				echo "$MENSAGEM_USO"
				exit 0
			;;
			
			-V|--version)
				echo -n $(basename "$0")
				#Extrai a versão diretamente dos cabeçalhos do programa
				grep '^# Versão' "$0"| tail -1| cut -d: -f1 |tr -d \#
				exit 0
			;;
			
			*) # opção inválida
				if test -n "$1" then
					echo Opção invalida: $1
					exit 1
				fi
			;;
		esac
		

#-----------------------------------------[ Discovery OLT Fiberhome Zabbix ]-----------------------------------------
	if [ "$snmp" -eq 1 ]; then 
		
		# CONFIGURAÇÃO SNMP 
		COMUNIDADE=$2
		IP=$3
		OID=".1.3.6.1.4.1.5875.800.3.9.3.3.1.2"
		
		SNMP_TEMP=$(snmpwalk -v 2c -On -c "$COMUNIDADE" "$IP" "$OID")
		
		#Verifica se tem erro no SNMP
		if [ $? -eq 0 ]; then
			
			#SNMP OK
			echo "$SNMP_TEMP" > "$SNMP_TOTAL";
			# TRATAMENTO
			echo "$SNMP_TEMP"|cut -d\. -f16|tr \= \" | tr -d " "|cut -d\" -f1,3\
			|sed 's/PON//g;s/[0-9/:/5'|cut -d\: -f1,3,4 > "$SNMP_ARQUIVO"
			echo "1"
			exit 0;
		else
			#ERRO snmp
			echo "0"
			exit 0;
		fi
	fi
	
	if [ "$discovery" -eq 1 ]; then
		
		[ -e "$SNMP_ARQUIVO" ] || {
		
			echo "Arquivo não encontrado $SNMP_ARQUIVO"
			exit 0;
		}
		
		#Trata primeiro elemento do JSON
		PRIMEIRO_ELEMENTO=1
		
		# Criar o cabeçalho padrão do JSON
		printf "{";
		printf "\"data\":[";
		
		IFS=':'
		while read -r listaidpon listaslot listanomepon;
		do
			# Verifica o primeiro elemento
			if [ $PRIMEIRO_ELEMENTO -ne 1 ]; then
				printf ","
			fi

			# Não coloca "," caso seja o ultimo dado no JSON
			PRIMEIRO_ELEMENTO=0

			# Cria o JSON
			printf "{"
			printf "\"{#IDPON}\":\"$listaidpon\"",
			printf "\"{#SLOT}\":\"$listaslot\"",
			printf "\"{#PORTAPON}\":\"$listanomepon\"",
			printf "}"

		done < "$SNMP_ARQUIVO"

		# Finaliza o Formato JSON
		printf "]";
		printf "}";

		# Encerra
		exit 0;
	fi

	# Se ativado chave online lista total online
	test "$online" = 1 && cut "$SNMP_TOTAL" -d\: -f2,3|tr -d " "|grep "PON$2/$3"|wc -l

	# Se ativado chave total lista total de clientes na OLT
	test "$total" = 1 && wc -l < "$SNMP_TOTAL"
