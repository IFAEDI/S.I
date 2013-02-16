#!/bin/bash
# Script permettant de configurer automatiquement les droits sur les répertoires, la config log4php ainsi que accès à la BDD
# TODO : Remplacer ce script par un script Phing, voir un simple Ant, et pourquoi pas un pom Maven2-3 pour intégration dans une PIC type Hudson/Jenkins
# TODO : Si mise en place de Composer par la suite, Phing peut être la solution la plus intéressante...
# @author: Sébastien Mériot (sebastien.meriot [at] gmail [dt] com)

EXPECTED_ARGS=4
LOG_PATH="logs/"
LOG_FILE="ifaedi.log"
CONF_PATH="config/"
CONF_LOG="log4php.xml"
CONF_DB="config.inc.php"

E_PARAM_EXPECTED=1
E_CANNOT_MKDIR_LOG=2
E_CANNOT_CHMOD_LOG=3
E_CANNOT_CHOWN_LOG=4
E_CANNOT_MKDIR_CONF=5
E_CANNOT_CHMOD_CONF=6
E_CANNOT_CHOWN_CONF=7
E_CANNOT_INIT_LOGS=8
E_CANNOT_SETUP_LOG4PHP=9

script_path=
ifaedi_path=

function usage
{
	echo "Usage:"
	echo "$0 <apache_user> <db_user> <db_pwd> <db_name>"
	echo -e "\tapache_user:	Utilisateur Apache"
	echo -e "\tdb_user:	Utilisateur MySQL"
	echo -e "\tdb_pwd:	Mot de passe associé à l'utilisateur MySQL"
	echo -e "\tdb_name:	Base de données à utiliser"
	echo
	echo "La commande doit être lancée en root!"
	echo
}

function check_dir_exists_or_create
{
        if [ -d ${1} ]
        then
                echo "[OK]"
        else
                echo -n "[CREATION..."
                mkdir ${1}
                if [ $? -ne 0 ]
                then
                        echo " FAIL!]"
                        return 1
                fi

                echo " OK]"
        fi

	return 0
}

function check_dirs
{
	cd $script_path

	ifaedi_path="`dirname $PWD`/"

	echo -en "\t[1] Vérification que le dossier de log existe "

	check_dir_exists_or_create "$ifaedi_path${LOG_PATH}"
	if [ $? -ne 0 ]
	then
		exit ${E_CANNOT_MKDIR_LOG}
	fi

	echo -en "\t[2] Vérification que le dossier de config existe "

	check_dir_exists_or_create "$ifaedi_path${CONF_PATH}"
	if [ $? -ne 0 ]
	then
		exit ${E_CANNOT_MKDIR_CONF}
	fi
}

function init_logs
{
	echo -en "\t[3] Initialisation des logs "

	#rm -f "$ifaedi_path${LOG_PATH}/*"
	touch "$ifaedi_path${LOG_PATH}${LOG_FILE}"
	if [ $? -ne 0 ]
	then
		echo "[FAIL]"
		exit ${E_CANNOT_INIT_LOGS}
	fi

	echo "[OK]"
}

function set_rights_logs
{
	echo -en "\t[4] Changement des droits sur le dossier de log "
	
	chmod -R 700 "$ifaedi_path${LOG_PATH}"
	if [ $? -ne 0 ]
	then
		echo "[FAIL]"
		exit ${E_CANNOT_CHMOD_LOG}
	fi

	echo "[OK]"

	echo -en "\t[5] Changement du propriétaire sur le dossier de log "

	chown -R $1:$1 "$ifaedi_path${LOG_PATH}"
	if [ $? -ne 0 ]
	then
		echo "[FAIL]"
		exit ${E_CANNOT_CHOWN_LOG}
	fi

	echo "[OK]"
}

function config_log4php
{
	echo -en "\t[6] Configuration de log4php "
	
	da_path=`echo "$ifaedi_path${LOG_PATH}${LOG_FILE}" | sed 's/\//\\\\\//g'`
	conf_log_path="$ifaedi_path${CONF_PATH}${CONF_LOG}"

	if [ -f $conf_log_path ]
	then
		sed -i "s/name=\"file\".*\"/name=\"file\" value=\"$da_path\"/g" $conf_log_path

		if [ $? -ne 0 ]
		then
			echo "[FAIL]"
			exit ${E_CANNOT_SETUP_LOG4PHP}
		fi

		echo "[OK]"
	else
		# TODO : wget?

		echo "[LOG4PHP.XML introuvable]"
		exit ${E_CANNOT_SETUP_LOG4PHP}
	fi
}

function config_db
{
	echo -en "\t[7] Configuration des accès BDD "

	
	echo "[NON IMPLEMENTE - ATTENTE MERGE PR]"
}

function main
{
	echo
	if [ $# -ne ${EXPECTED_ARGS} ]
	then
		usage
		exit ${E_PARAM_EXPECTED}
	fi

	script_path=`dirname $0`
	#dirname $0 | cd
	check_dirs $@
	init_logs
	set_rights_logs $@
	config_log4php $@
	config_db $@

	echo
	echo "Done."
}


main $@
