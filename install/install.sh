#!/bin/bash
# Script permettant d'initialiser un environnement de développement pour le S.I. de l'AEDI
# Ce script se connecte à la base MySQL locale, créer une nouvelle base et injecte un jeu de test.
# @author Sébastien Mériot (sebastien.meriot [at] gmail [dt] com)

EXPECTED_ARGS=3
MYSQL=`which mysql`
TEST_SET='test_set.sql'

E_PARAM_EXPECTED=1
E_CONNECTION_FAILED=2
E_DATABASE_EXISTS=3
E_CREATE_FAILED=4
E_IMPORT_FAILED=5

# Affichage de l'usage
function usage
{
	echo -e "$0 <mysql_user> <mysql_passwd> <db_name>"
	echo -e "\tmysql_user: MySQL username"
	echo -e "\tmysql_passwd: MySQL password"
	echo -e "\tdb_name: Base de données à monter"
	echo
}

# Test du nombre de param
function test_param
{
	if [ ${#} -ne ${EXPECTED_ARGS} ]
	then
		usage
		exit ${E_PARAM_EXPECTED}
	fi
}

function test_connect
{
	echo -en "\t[1] Tentative de connexion "
	echo "quit" | ${MYSQL} -u $1 --password=$2 > /dev/null
	if [ $? -eq 0 ]
	then
		echo "[OK]"
	else
		echo "[FAIL]"
		exit ${E_CONNECTION_FAILED}
	fi
}

function test_db
{
	echo -en "\t[2] Vérification que la BD n'existe pas "
	echo "quit" | ${MYSQL} -u $1 --password=$2 $3 2> /dev/null
	if [ $? -eq 1 ]
	then
		echo "[OK]"
	else
		echo "[FAIL]"
		exit ${E_DATABASE_EXISTS}
	fi
}

function create_db
{
	echo -en "\t[3] Création de la base de données $3 "
	echo "quit" | echo "CREATE DATABASE $3;" | ${MYSQL} -u $1 --password=$2
	if [ $? -eq 0 ]
	then
		echo "[OK]"
	else
		echo "[FAIL]"
		exit ${E_CREATE_FAILED}
	fi
}

function import_data
{
	echo -en "\t[4] Importation des données "
	${MYSQL} -u $1 --password=$2 $3 < ${TEST_SET}
	if [ $? -eq 0 ]
	then
		echo "[OK]"
	else
		echo "[FAIL]"
		exit ${E_IMPORT_FAILED}
	fi
}

function main
{
	test_param $@

	echo "AEDI - installation d'un environnement de développement..."
	test_connect $@
	test_db $@

	create_db $@
	import_data $@

	echo "Terminé."
	echo

	exit 0
}



main $@
