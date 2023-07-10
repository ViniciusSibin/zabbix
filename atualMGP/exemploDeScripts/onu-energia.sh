#!/bin/sh

snmpwalk -v 2c -c mgp 10.254.1.163 1.3.6.1.4.1.5875.800.3.10.1.1.11 | grep -c "INTEGER: 2"
