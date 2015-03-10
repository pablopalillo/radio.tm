<?php
define("LM_FRONT_CHOOSE_PACKAGE","<b>Scegli il pacchetto da installare:</b>");
define("LM_FRONT_CHOOSE_PACKAGE_SUB","<small>Si prega di selezionare la versione di Wordpress che si desidera installare</small>");
define("LM_FRONT_TOP","<span colspan='2' class='contentheading'>Installare Wordpress Online</span>");
define("LM_FRONT_TOP_FTP_DETAILS","<h2>Fornisci i tuoi dati ftp qui sotto:</h2>");
define("LM_FRONT_WEBSITE_URL","<b>Url Sito Web:</b>");
define("LM_FRONT_WEBSITE_URL_SUB","<small>Si prega di fornire l'URL del sito dove verr&agrave; installato Wordpress, ad esempio http://www.sitename.com/Wordpress</small>");
define("LM_FRONT_FTP_HOST","<b>Hostname Ftp:</b>");
define("LM_FRONT_FTP_HOST_SUB","<small>ad esempio ftp.sitename.com</small>");
define("LM_FRONT_FTP_USER","<b>Nome Utente Ftp:</b>");
define("LM_FRONT_FTP_USER_SUB","<small>ad esempio john</small>");
define("LM_FRONT_FTP_PASS","<b>Password Ftp:</b>");
define("LM_FRONT_FTP_PASS_SUB","<small>ad esempio johnpass</small>");
define("LM_FRONT_FTP_DIR","<b>Cartella Ftp:</b>");
define("LM_FRONT_FTP_DIR_SUB","<small>Si prega di fornire la cartella ftp di dove si desidera installare Wordpress, ad esempio public_html/Wordpress/ oppure htdocs/Wordpress/ e assicuratevi che ci siano i permessi di scrittura per tutti</small>");
define("LM_TRANSFER_FTP_INCT","Trasferimento Incrementale:");
define("LM_TRANSFER_FTP_INCT_SUB","tenter&agrave; di trasferire i file tramite ftp in modalit&agrave; incrementale al fine di evitare eventuali pagine bianche o di timeout");
define("LM_FRONT_BOTTOM","Xcloner non funziona? Si prega di dirci cosa &egrave; successo alla -> <a href='http://www.xcloner.com/contact/'>Pagina dei Contatti</a><br />
                         Realizzato da <a href='http://www.xcloner.com'>XCloner</a>");
define("LM_FRONT_MSG_OK","Abbiamo caricato l'utilit&agrave; di ripristino dei pacchetti sul tuo sito ftp, per proseguire clicca qui");
define("LM_NOPAKCAGE_ERROR","Non vi &egrave; alcun pacchetto selezionato, interruzione...!");
define("LM_AMAZON_S3","Archiviazione su Amazon S3");
define("LM_AMAZON_S3_ACTIVATE","Attivare");
define("LM_AMAZON_S3_AWSACCESSKEY","AWS Access Key:");
define("LM_AMAZON_S3_AWSSECRETKEY","AWS Secret Key:");
define("LM_AMAZON_S3_BUCKET","Bucket name:");
define("LM_AMAZON_S3_DIRNAME","Cartella di Upload:");
define("LM_AMAZON_S3_SSL","Abilita trasferimento SSL");
define("LM_DROPBOX","DropBox Storage");
define("LM_DROPBOX_ACTIVATE","Activate");
define("LM_DROPBOX_AWSACCESSKEY","Application Key:");
define("LM_DROPBOX_AWSSECRETKEY","Application Secret:");
define("LM_DROPBOX_DIRNAME","Upload Directory:");
define("LM_DATABASE_EXCLUDE_TABLES","Seleziona le tabelle da escludere dal backup:");
define("LM_CONFIG_SYSTEM_FOLDER","Visualizza Cartelle:");
define("LM_CONFIG_SYSTEM_FOLDER_SUB","si prega di selezionare la modalit&agrave; con cui si desidera selezionare le cartelle da escludere nella sezione 'Crea Backup'");
define("LM_CONFIG_SYSTEM_LANG","Lingua di sistema:");
define("LM_CONFIG_SYSTEM_LANG_SUB","configura la lingua di XCloner, se impostato al Valore predefinito del sistema verr&agrave; visualizzata, se disponibile, la lingua predefinita di Wordpress");
define("LM_CONFIG_SYSTEM_LANG_DEFAULT","Valore predefinito del sistema");
define("LM_CONFIG_SYSTEM_DOWNLOAD","Abilita il link diretto di download:");
define("LM_CONFIG_SYSTEM_DOWNLOAD_SUB","se questa opzione &egrave; selezionata, nella schermata 'Amministrazione Backup', il link di download sar&agrave; un collegamento diretto dal server per scaricare il pacchetto, si ricorda che il percorso di backup deve essere all'interno del percorso di root di Wordpress");
define("LM_CONFIG_DISPLAY","Impostazioni di visualizzazione");
define("LM_CONFIG_SYSTEM","Impostazioni di sistema");
define("LM_CONFIG_SYSTEM_FTP","Modalit&agrave; di trasferimento FTP");
define("LM_CONFIG_SYSTEM_FTP_SUB","selezionare come i file saranno trasferiti da server a server quando si utilizza il protocollo ftp");
define("LM_CONFIG_MEM","Backup utilizzando funzioni del server");
define("LM_CONFIG_MEM_SUB","<small>Se impostato su attiva, vi sar&agrave; richiesto di avere sul vostro server il supporto per <b>zip o tar</b> e/o i comandi <b>mysqldump</b> specificando i loro percorsi, e anche <b>exec()</b> di accesso php</small>");
define("LM_CRON_DB_BACKUP","Abilitare il backup del database:");
define("LM_CRON_DB_BACKUP_SUB","<small>Selezionare questa opzione per includere i dati mysql nel backup.</small>");
define("LM_CONFIG_SYSTEM_MBACKUP","Includi le cartelle di backup precedenti:");
define("LM_CONFIG_SYSTEM_MBACKUP_SUB","<small>Se selezionato, ogni backup includer&agrave; anche i file di backup precedenti, aumentando cos&igrave; le sue dimensioni di volta in volta.</small>");
define("LM_TAB_MYSQL","MYSQL");
define("LM_CONFIG_MYSQL","MYSQL Impostazioni di connessione:");
define("LM_CONFIG_MYSQLH","Mysql nome host:");
define("LM_CONFIG_MYSQLU","Mysql nome utente:");
define("LM_CONFIG_MYSQLP","Mysql password:");
define("LM_CONFIG_MYSQLD","Mysql nome database:");
define("LM_TAB_AUTH","Autenticazione");
define("LM_CONFIG_AUTH","Area Autenticazione Utente");
define("LM_CONFIG_AUTH_USER","Login Username:");
define("LM_CONFIG_AUTH_PASS","Login Password:");
define("LM_CONFIG_AUTH_USER_SUB","questo sar&agrave; il vostro nome utente predefinito di accesso a XCloner");
define("LM_CONFIG_AUTH_PASS_SUB","la password predefinita di login, lasciare in bianco se non la si vuole cambiare");
define("LM_YES","Si");
define("LM_NO","No");
define("LM_ACTIVE","attiva");
define("LM_TAR_PATH","Tar percorso o comando:");
define("LM_TAR_PATH_SUB","(richiesto se il tipo di archivio &egrave; Tar e la casella attiva &egrave; selezionata)");
define("LM_ZIP_PATH","Zip percorso o comando:");
define("LM_ZIP_PATH_SUB","(richiesto se il tipo di archivio &egrave; Zip e la casella attiva &egrave; selezionata)");
define("LM_MYSQLDUMP_PATH","Mysqldump percorso o comando: (richiesto se la casella attiva &egrave; selezionata)<br /> Per grandi dump (file di backup) mysql si prega di utilizzare<br /><b><small>mysqldump --quote-names --quick --single-transaction --skip-comments</b></small>");
define("LM_CONFIG_MANUAL","Processo di Backup Manuale");
define("LM_CONFIG_MANUAL_BACKUP","Backup Manuale:");
define("LM_CONFIG_MANUAL_BACKUP_SUB","Selezionare questa opzione se si dispone di tempi limitati di esecuzione del PHP sul server. Ci&ograve; richieder&agrave; javascript attivato nel tuo browser. (<b>nota</b>: parametro non obbligatorio, provare prima a fare il backup senza attivarlo)");
define("LM_CONFIG_MANUAL_FILES","File da elaborare per richiesta:");
define("LM_CONFIG_DB_RECORDS","Record del database per richiesta");
define("LM_CONFIG_MANUAL_REFRESH","Tempo tra le richieste:");
define("LM_CONFIG_SYSTEM_MDATABASES","Backup di pi&ugrave; Database:");
define("LM_CONFIG_SYSTEM_MDATABASES_SUB","Questa opzione controlla se XCloner pu&ograve; eseguire il backup di pi&ugrave; database");
define("LM_CONFIG_EXCLUDE_FILES_SIZE","Escludi i file pi&ugrave; grandi di:");
define("LM_CONFIG_CRON_LOCAL","Server locale*");
define("LM_CONFIG_CRON_REMOTE","Account ftp Remoto");
define("LM_CONFIG_CRON_EMAIL","Email**");
define("LM_CONFIG_CRON_FULL","Tutto (file + database)");
define("LM_CONFIG_CRON_FILES","Solo File");
define("LM_CONFIG_CRON_DATABASE","Solo Database");
define("LM_CONFIG_EDIT","Modifica file di configurazione:");
define("LM_CONFIG_BSETTINGS","Impostazioni percorso di backup");
define("LM_CONFIG_BSETTINGS_OPTIONS","Opzioni generatore di backup");
define("LM_CONFIG_BSETTINGS_SERVER","Usare opzioni del Server");
define("LM_CONFIG_BPATH","Percorso Archivio di backup:");
define("LM_CONFIG_UBPATH","Percorso iniziale di Backup:");
define("LM_CONFIG_BPATH_SUB","<small>questo &egrave; il percorso dove tutti i backup verranno memorizzati</small>");
define("LM_CONFIG_UBPATH_SUB","<small>questo &egrave; il percorso base che XCloner utilizzer&agrave; per selezionare i file e le cartelle di backup</small>");
define("LM_CRON_EXCLUDE","Cartelle escluse");
define("LM_CRON_EXCLUDE_DIR","Lista delle cartelle escluse una per ogni riga:<br>si prega di utilizzare il percorso completo della cartella nel server");
define("LM_CRON_BNAME","Nome Backup:");
define("LM_CRON_BNAME_SUB","<small>Se lasciato vuoto si creer&agrave; un nome di default ogni volta che viene pianificato un nuovo backup</small>");
define("LM_CRON_IP","IP ammessi alla pianificazione:");
define("LM_CRON_IP_SUB","<small>Per impostazione predefinita, solo il server locale avranno accesso al processo di pianificazione, ma si pu&ograve; inserire anche altri indirizzi IP uno per ogni linea</small>");
define("LM_CRON_DELETE_FILES","Eliminare i vecchi backup");
define("LM_CRON_DELETE_FILES_SUB","Elimina i backup pi&ugrave; vecchio di:");
define("LM_CRON_DELETE_FILES_SUB_ACTIVE"," Attivare");
define("LM_CRON_SEMAIL","Email log di di pianificazione:");
define("LM_CRON_SEMAIL_SUB","Se un indirizzo e-mail &egrave; impostato, dopo l'esecuzione di un processo pianificato, il log di pianificazione sar&agrave; inviato a questo indirizzo; pi&ugrave; indirizzi possono essere aggiunte separati da ;");
define("LM_CRON_FROMEMAIL","Email cron log from:");
define("LM_CRON_FROMEMAIL_SUB","If an email address is set, after running a cron job, the cron log will be emailed from this address");
define("LM_CRON_MCRON","Nome Configurazione:");
define("LM_CRON_MCRON_AVAIL","Configurazioni disponibili:");
define("LM_CRON_MCRON_R","si prega di fornire un nome semplice per la configurazione di un nuovo processo di pianificazione");
define("LM_CRON_MCRON_SUB","con questa opzione si sar&agrave; in grado di salvare la configurazione corrente in un file separato e utilizzarlo per l'esecuzione di pi&ugrave; processi di pianificazione (CronJobs)");
define("LM_CRON_SETTINGS_M","Configurazione Multipli processi di pianificazione (CronJobs)");
define("LM_CONFIG_SPLIT_BACKUP_SIZE","Dividi l'archivio di backup se la dimensione &egrave; maggiore di:");
define("LM_MENU_OPEN_ALL","apri tutto");
define("LM_MENU_CLOSE_ALL","chiudi tutto");
define("LM_MENU_ADMINISTRATION","Amministrazione");
define("LM_MENU_CLONER","XCloner");
define("LM_MENU_CONFIGURATION","Configurazione");
define("LM_MENU_CRON","Pianificazione");
define("LM_MENU_LANG","Traduttore");
define("LM_MENU_ACTIONS","Attivit&agrave;");
define("LM_MENU_Generate_backup","Avvio Backup");
define("LM_MENU_Restore_backup","Ripristina Backup");
define("LM_MENU_View_backups","Amministrazione Backup");
define("LM_MENU_Documentation","Aiuto");
define("LM_MENU_ABOUT","Info");
define("LM_DELETE_FILE_FAILED","Eliminazione non riuscita, controllare i permessi sui file");
define("LM_JOOMLAPLUG_CP","XCloner - la soluzione per il backup e ripristino del tuo sito");
define("LM_MENU_FORUM","Forum");
define("LM_MENU_SUPPORT","Supporto");
define("LM_MENU_WEBSITE","Sito Web");
define("LM_MAIN_Settings","Impostazioni");
define("LM_MAIN_View_Backups","Amministrazione Backup");
define("LM_MAIN_Generate_Backup","Avvio Backup");
define("LM_MAIN_Help","Guida");
define("LM_FTP_TRANSFER_MORE","Modalit&agrave; di connessione FTP");
define("LM_REFRESH_MODE","Modalit&agrave; Aggiornamento Backup");
define("LM_DEBUG_MODE","Abilita i log:");
define("LM_REFRESH_ERROR","Si &egrave; verificato un errore durante il recupero dei dati JSON dal server, riprova oppure contatta gli sviluppatori!");
define("LM_LANG_NAME","Nome Lingua");
define("LM_LANG_MSG_DEL","Lingua(e) cancellati con successo!");
define("LM_LANG_NEW","Nuovo Nome Lingua:");
define("LM_LANG_EDIT_FILE","Modifica File:");
define("LM_LANG_EDIT_FILE_SUB","Non dimenticare di salvare la vostra traduzione ogni 5 minuti, basta premere il pulsante Applica per aggiornare");
define("LM_TAB_GENERAL","Generale");
define("LM_TAB_G_STRUCTURE","Struttura");
define("LM_TAB_SYSTEM","Sistema");
define("LM_TAB_CRON","Pianificazione");
define("LM_TAB_INFO","Info");
define("LM_TAB_G_DATABASE","Opzioni Database");
define("LM_TAB_G_FILES","Opzioni File");
define("LM_TAB_G_COMMENTS","Commenti di backup");
define("LM_G_EXCLUDE_COMMENT","<br>Si prega di inserire qui le cartelle escluse, una per linea!
    <br><b>si consiglia di disabilitare la funzionalit&agrave; di cache quando si esegue un backup, o in caso contrario escludi la cartella della cache dal backup</b>");
define("LM_TAB_G_COMMENTS_H2","Inserisci qui sotto eventuali commenti da archiviare:");
define("LM_TAB_G_COMMENTS_NOTE","Si prega di notare, i commenti vengono memorizzati all'interno dell'archivio, file <b>administrator/backups/.comments</b>");
define("LM_MSG_FRONT_1","Nessun pacchetto disponibile");
define("LM_MSG_FRONT_2","Il caricamento (upload) FTP non &egrave; riuscito per la destinazione");
define("LM_MSG_FRONT_3","Caricamento eseguito per");
define("LM_MSG_FRONT_4","La Connessione FTP &egrave; fallita!");
define("LM_MSG_FRONT_5","Tentativo di connessione a");
define("LM_MSG_FRONT_6","per l'utente");
define("LM_MSG_BACK_1","Configurazione aggiornata con successo...");
define("LM_MSG_BACK_2","La Connessione FTP &egrave; fallita!");
define("LM_MSG_BACK_3","Sposta Backup &egrave; FATTO! Il backup selezionato dovrebbe essere disponibile nella posizione prevista!");
define("LM_MSG_BACK_4","Spostamento fatto, avviare il processo di Duplica (Clone) sull'host remoto");
define("LM_MSG_BACK_5","Non pubblicato con successo dal frontend");
define("LM_MSG_BACK_6","Rimosso da pubblicato fallito! Si prega di verificare i percorsi!");
define("LM_MSG_BACK_7","Pubblicato con successo dal frontend");
define("LM_MSG_BACK_8","Pubblicazione fallita! Si prega di verificare i percorsi!");
define("LM_MSG_BACK_9","La copia (clone) rinominato con successo!");
define("LM_MSG_BACK_10","Il percorso di Wordpress non &egrave; nel vostro percorso di backup! Impossibile utilizzare la modalit&agrave; Scaricamento Diretto! Si dovrebbe modificare la  Configurazione-> Scheda Sistema e impostare il 'Link Scaricamento Diretto' a No");
define("LM_MSG_BACK_11","Tutto fatto! Il Processo di backup manuale &egrave; stato completato! <a href='plugins.php?page=xcloner_show&?option=com_cloner&task=view'>Clicca qui per continuare</a>");
define("LM_MSG_BACK_12","<h2>Backup non riuscito! Si prega di verificare di avere il supporto per l'utilit&agrave; Zip sul server (/usr/bin/zip o /usr/local/bin/zip) ed il percorso sia impostato correttamente in Configurazione, oppure scegliere il tipo di archivio Tar!</h2>");
define("LM_MSG_BACK_13","<h2>Backup non riuscito! Si prega di verificare di avere il supporto per l'utilit&agrave; Tar sul server (/usr/bin/tar o /usr/local/bin/tar) ed il percorso sia impostato correttamente in Configurazione,  oppure scegliere il tipo di archivio Zip!</h2>");
define("LM_MSG_BACK_14","<font color='red'>C'&egrave; stato un problema nel generare il backup del database, si prega di controllare il percorso sul server di mysqldump!</font>");
define("LM_CRON_TOP","Installazione comando di Pianificazione (Cron)");
define("LM_CRON_SUB","<b>Utilizzando la funzione di pianificazione (cron) &egrave; possibile impostare in automatico la generazione del backup per il tuo sito!</b>
<br />Per impostarlo, &egrave; necessario aggiungere nel vostro pannello di amministrazione scheda pianificazione uno dei seguenti comandi:");
define("LM_CRON_HELP","Note:
 - Se la posizione di php &egrave; diversa da /usr/bin/php, si prega di utilizzare questa, formato /$"."php_path/php<br />
<br />
<br />
Per maggiori informazioni su come impostare una pianificazione (cron) vedi:<br />
 - Cpanel <a href='http://www.cpanel.net/docs/cpanel/' target='_blank'>clicca qui</a><br />
 - Plesk <a href='http://www.swsoft.com/doc/tutorials/Plesk/Plesk7/plesk_plesk7_eu/plesk7_eu_crontab.htm' target='_blank'>clicca qui</a><br />
 - Interworx <a href='http://www.sagonet.com/interworx/tutorials/siteworx/cron.php' target='_blank'>clicca qui</a><br />
 - Informazioni Generali Linux crontab <a href='http://www.computerhope.com/unix/ucrontab.htm#01' target='_blank'>clicca qui</a><br />
<br />
Se hai bisogno di aiuto per configurare la vostra Pianificazione (cron job), si prega di visitare il nostro forum <a href='http://www.xcloner.com/support/forums/'>http://www.xcloner.com/support/forums/</a>");
define("LM_CRON_SETTINGS","Impostazioni Pianificazione (Cron)");
define("LM_CRON_MODE","Modalit&agrave; Archiviazione del Backup:");
define("LM_CRON_MODE_INFO"," <br />
      <small> Si prega di notare:* se si &egrave; scelto sul server locale, useremo il percorso di backup predefinito nella sezione Generale per archiviare il backup</small>
      <br />
      <small> Si prega di notare:** se si &egrave; scelto la modalit&agrave; invio con email, non garantiamo che il backup raggiunger&agrave; l'account di posta a causa delle possibili limitazioni del provider</small>");
define("LM_CRON_TYPE_INFO","<small><br /> Ti preghiamo di selezionare il tipo di backup che si desidera creare</small>");
define("LM_CRON_MYSQL_DETAILS","Opzioni Mysql");
define("LM_CRON_MYSQL_DROP","Aggiungi il comando Mysql Drop");
define("LM_CRON_TYPE","Tipo di backup:");
define("LM_CRON_FTP_DETAILS","Dettagli modalit&agrave; Ftp di memorizzazione:");
define("LM_CRON_FTP_SERVER","Server Ftp:");
define("LM_CRON_FTP_USER","Nome Utente Ftp:");
define("LM_CRON_FTP_PASS","Password Ftp:");
define("LM_CRON_FTP_PATH","Percorso Ftp:");
define("LM_CRON_FTP_DELB","Elimina backup dopo il trasferimento");
define("LM_CRON_EMAIL_DETAILS","Dettagli in modalit&agrave; e-mail:");
define("LM_CRON_EMAIL_ACCOUNT","Account e-mail:");
define("LM_CRON_COMPRESS","Comprimere file di backup:");
define("LM_RESTORE_TOP","Ripristino dei tuoi dati di backup");
define("LM_CREDIT_TOP","Info XCloner");
define("LM_CLONE_FORM_TOP","<h2>Inserisci i tuoi dati ftp qui sotto:</h2>");
define("LM_CONFIG_INFO_T_SAFEMODE","Php Safe_mode:");
define("LM_CONFIG_INFO_T_VERSION","Controllo Versione PHP:");
define("LM_CONFIG_INFO_T_MTIME","Php max_execution_time:");
define("LM_CONFIG_INFO_T_MEML","Php memory_limit:");
define("LM_CONFIG_INFO_T_BDIR","Php open_basedir:");
define("LM_CONFIG_INFO_T_EXEC","Supporto funzione exec():");
define("LM_CONFIG_INFO_T_TAR","Percorso sul server utilit&agrave; Tar:");
define("LM_CONFIG_INFO_T_ZIP","Percorso sul server utilit&agrave; Zip:");
define("LM_CONFIG_INFO_T_MSQL","Percorso sul server utilit&agrave; Mysqldump:");
define("LM_CONFIG_INFO_T_BPATH","Percorso Backup:");
define("LM_CONFIG_INFO_ROOT_PATH_SUB","il percorso iniziale di backup deve esistere ed essere leggibili in modo che XCloner per avviare il processo di backup");
define("LM_CONFIG_INFO_ROOT_BPATH_TMP","Cartella Temporanea");
define("LM_CONFIG_INFO_ROOT_PATH_TMP_SUB","il percorso <i>[Percorso Iniziale Backup/administrator/backups]</i> deve essere creato ed essere scrivibile affinch&egrave; XCloner possa funzionare correttamente.");
define("LM_CONFIG_INFO","Questa scheda mostrer&agrave; le informazioni generali del sistema e i percorsi");
define("LM_CONFIG_INFO_PATHS","Informazioni Generali percorsi:");
define("LM_CONFIG_INFO_PHP","Informazioni di configurazione Php:");
define("LM_CONFIG_INFO_TIME","<small>Questo controlla il tempo massimo (in secondi) che &egrave; consentito per l'esecuzione dello script sul server</small>");
define("LM_CONFIG_INFO_MEMORY","<small>Controlla la quantit&agrave; massima di memoria che lo script pu&ograve; utilizzare per i suoi processi</small>");
define("LM_CONFIG_INFO_BASEDIR","<small>Controlla i percorsi a cui lo script &egrave; consentito l'accesso, nessun valore significa che pu&ograve; accedere a qualsiasi percorso </small>");
define("LM_CONFIG_INFO_SAFEMODE","<small>safe mode dovr&agrave; essere impostato su Off in modo che XCloner possa funzionare correttamente</small>");
define("LM_CONFIG_INFO_VERSION","<small>PHP >=5.2.3 &egrave; necessario per godere di tutte le funzioni</small>");
define("LM_CONFIG_INFO_TAR","<small>Se lo script non &egrave; in grado determinare il percorso tar automaticamente, potrebbe essere necessario deselezionare la casella di controllo 'Attiva' vicino alla riga TAR nella scheda Generale</small>");
define("LM_CONFIG_INFO_ZIP","<small>Se lo script non &egrave; in grado determinare il percorso zip automaticamente, potrebbe essere necessario deselezionare la casella di controllo 'Attiva' vicino alla riga ZIP nella scheda Generale</small>");
define("LM_CONFIG_INFO_MSQL","<small>Se lo script non &egrave; in grado determinare il percorso mysqldump automaticamente, potrebbe essere necessario deselezionare la casella di controllo 'Attiva' vicino alla riga MYSQLDUMP nella scheda Generale</small>");
define("LM_CONFIG_INFO_EXEC","<small>Se questa funzione &egrave; disabilitata, &egrave; possibile deselezionare entrambe le caselle di controllo 'attiva' nella scheda Generale</small>");
define("LM_CONFIG_INFO_BPATH","<small>deve essere scrivibile in modo che XCloner possa creare l'archivio di backup</small>");
define("LM_TRANSFER_URL","Url Sito Web:");
define("LM_TRANSFER_URL_SUB","<small>Si prega di fornire l'URL del sito dove verr&agrave; spostato il backup, ad esempio http://www.sitename.com/, abbiamo bisogno di questo perch&egrave; ci si diriger&agrave; l&igrave; per accedere allo script di ripristino</small>");
define("LM_TRANSFER_FTP_HOST","Ftp hostname:");
define("LM_TRANSFER_FTP_HOST_SUB","ad esempio ftp.sitename.com");
define("LM_TRANSFER_FTP_USER","Ftp username:");
define("LM_TRANSFER_FTP_USER_SUB","ad esempio 'john'");
define("LM_TRANSFER_FTP_PASS","Ftp password:");
define("LM_TRANSFER_FTP_PASS_SUB","ad esempio 'johnpass'");
define("LM_TRANSFER_FTP_DIR","Cartella Ftp:");
define("LM_TRANSFER_FTP_DIR_SUB","Si prega di inserire la cartella ftp dove si desidera spostare il backup, ad esempio public_html/ oppure htdocs/ e assicuratevi che ci siano i permessi di scrittura per tutti");
define("LM_BACKUP_NAME","<b>Si prega di scegliere il nome del backup</b>");
define("LM_BACKUP_NAME_SUB","<small>Se lasciato vuoto si generer&agrave; un nome predefinito!</small>");
define("LM_COM_TITLE","Manager XCloner -");
define("LM_COM_TITLE_CONFIRM","Conferma Selezione cartella");
define("LM_COL_FILENAME","Nome file di Backup");
define("LM_COL_DOWNLOAD","Scarica");
define("LM_COL_AVALAIBLE","Interfaccia del programma");
define("LM_COL_SIZE","Dimensione");
define("LM_COL_DATE","Data di Backup");
define("LM_COL_FOLDER","<b>Cartelle e/o file esclusi</b>");
define("LM_DELETE_FILE_SUCCESS","file eliminato con successo<br />");
define("LM_DOWNLOAD_TITLE","Scarica");
define("LM_ARCHIVE_NAME","Nome archivio");
define("LM_NUMBER_FOLDERS","Numero di Cartelle");
define("LM_NUMBER_FILES","Numero di file");
define("LM_SIZE_ORIGINAL","Dimensioni del file originale");
define("LM_SIZE_ARCHIVE","Dimensioni del Archivio");
define("LM_DATABASE_ARCHIVE","Backup Database");
define("LM_CONFIRM_INSTRUCTIONS","<b>Si prega di selezionare le cartelle che si desidera escludere dall'archivio</b>
<br />- Per impostazione predefinita tutte le cartelle sono incluse, se si vuole escludere una cartella e le sue sottocartelle basta selezionare la casella accanto ad essa");
define("LM_CONFIRM_DATABASE","Backup Database");
define("LM_DATABASE_EXCLUDED","Esclusi");
define("LM_DATABASE_CURRENT","database corrente:");
define("LM_DATABASE_INCLUDE_DATABASES","Includi database Extra");
define("LM_DATABASE_INCLUDE_DATABASES_SUB","&egrave; possibile selezionare pi&ugrave; database da includere nel backup tenendo premuto il tasto CTRL e selezionando gli elementi desiderati con il mouse<br />
i database saranno memorizzati all'interno della cartella administrator/backups all'interno del vostro archivio");
define("LM_DATABASE_MISSING_TABLES","Errore: Definizioni Tabella non trovata");
define("LM_DATABASE_BACKUP_FAILED","Backup non riuscito, si prega di verificare che la cartella administrator/backups directory sia scrivibile!");
define("LM_DATABASE_BACKUP_COMPLETED","Backup Completato");
define("LM_RENAME_TOP","Rinominare le copie (clone) selezionati");
define("LM_RENAME","Rinomina copia");
define("LM_RENAME_TO"," a");

// --- CLONER RESTORE--- //

define("LM_CLONER_RESTORE","<h2>Come ripristinare un backup su diverse posizioni INFO!</h2><br />
<pre>
   Ripristino dei backup non &egrave; mai stato cos&igrave; facile!
   Con l'aiuto della nostra funzione clonazione dalla schermata <a href='plugins.php?page=xcloner_show&?option=com_cloner&task=view'>Amministrazione Backup</a>
   si sar&agrave; in grado di spostare il backup del sito web ovunque su Internet.

   Ecco cosa dovete fare:

   <b>Passo 1 - spostare il backup nell'host di ripristino</b>

    - andare nell'area 'Amministrazione Backup' di XCloner
    - dopo aver selezionato il vostro backup fare clic sul pulsante 'clone'
    - inserire i dati ftp di dove si vuole archiviare la copia (clone) di backup
    - dopo aver trasferito con successo il backup e lo script di ripristino al nuovo host, vi verr&agrave; dato un link per accedere alla fase successiva basato sull'url fornito per la posizione remota
    - dopo aver fatto clic sul collegamento fornito sarete inviati nella nuova posizione, come ad esempio un url del genere <b>http://my_restore_site.com/XCloner.php</b>

   <b>Note:</b> se questo processo fallisce per qualsiasi motivo, &egrave; necessario eseguire questa operazione:
   	1. Scaricare il backup sul vostro computer
   	2. Scaricare lo script di ripristino, tutti i file dalla cartella administrator/components/com_xcloner-backupandrestore/restore/
   	3. Caricare sia il backup che lo script di ripristino nella la posizione di ripristino
   	4. Avviare XCloner.php nel vostro browser e seguire la schermata di ripristino come specificato di seguito

   <b>Passo 2 - la schermata di ripristino XCloner.php:</b>

   <b>XCloner.php - lo script di ripristino</b>
    - in questo passo si dovrebbe avere gi&agrave; in posizione il backup creato e lo script di ripristino
    - inserire i nuovi dati di accesso MySQL, questo include il nuovo hostname mysql, nome utente e password, e un nuovo database se diverso da quello originale
    - inserisci l'URL della nuova posizione e la password
    - per ripristinare i file avete <b>2 opzioni:</b>

       	- 1. ripristinare i file tramite ftp, lo script simula un processo di caricamento ftp sul vostro server, questo
       	risolver&agrave; il problema dei permessi del punto 2.
       	- 2. ripristinare i file direttamente, con questo metodo dovreste estrarre dall'archivio i file sul vostro server, sar&agrave; pi&ugrave; veloce ma
       	si potrebbero verificare alcuni problemi con i permessi se si utilizza l'ftp per apportare molte modifiche al sito

    - dopo aver inviato con successo lo script si tenter&agrave; di spostare i file nel nuovo percorso, direttamente o tramite ftp e
    verr&agrave; installato il database
    - se tutto andr&agrave; ok la copia (clone) del nuovo sito sar&agrave; attiva e funzionante nella nuova posizione

    Per il supporto si prega di consultare i nostri forum <a href='http://www.xcloner.com/support/forums/' target='_blank'>http://www.xcloner.com/support/forums/</a>
    o inviare un'e-mail a <a href='mailto:info@xcloner.com'>info@xcloner.com</a>


</pre>");

// --- ABOUT CLONER---//

define("LM_CLONER_ABOUT","<h2>XCloner Backup</h2>
      XCloner &egrave; uno strumento che vi aiuter&agrave; a gestire i backup del sito, Crea/Ripristina/Sposta cos&igrave; il vostro sito sar&agrave; sempre al sicuro!
	  <br /><br />
      Funzionalit&agrave;:
       <ul>
			<li>script di pianificazione per generare backup automatici</li>
			<li>opzioni multiple di backup</li>
			<li>strumento di ripristino per spostare il sito rapidamente su altre posizioni</li>
			<li>archiviare il backup in locale, in remoto</li>
			<li>interfacce di backup AJAX/JSON</li>
			<li>codice autonomo, pu&ograve; effettuare il backup di ogni sito PHP/Mysql</li>
			<li>backup incrementali del database e dei file</li>
			<li>scansione incrementale dei file di sistema</li>
			<li>supporta amazon S3</li>
       </ul>
	   <br />

       Per segnalazioni e suggerimenti contattateci all'indirizzo info@xcloner.com o visitateci su <a href='http://www.xcloner.com'>http://www.xcloner.com</a>

      <br/><br/>

      XCloner.com © 2004-2011 | <a href='http://www.xcloner.com' target='_blank'>www.xcloner.com</a>
	  <br/><br/>
	  Translator Manca Cesare | <a href='http://www.pcteknet.it' target='_blank'>www.pcteknet.it</a>
	  Revision by Giuseppe Velardo - giuseppe.velardo@gmail.com
      <br/><p/><br/>");
	  
define("LM_LOGIN_TEXT","
<pre>
<b>Note:</b>
 1. Se siete su questa schermata per la prima volta, di default
    la username &egrave; '<i>admin</i>' e la password &egrave; '<i>admin</i>', ricorda che dovresti cambiarli dopo il login

 2. Se hai dimenticato la tua password, per reimpostarla &egrave; necessario aggiungere
    questo codice:
	<b>$"."_CONFIG[\"jcpass\"] = md5(\"my_new_pass\")
	
 3. Username e Password sono Case-Sensitive
</pre>
");
?>