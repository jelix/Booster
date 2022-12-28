;<?php die(''); ?>
;for security reasons , don't remove or modify the first line
;this file doesn't list all possible properties. See lib/jelix/core/defaultconfig.ini.php for that


locale=en_US
charset=UTF-8

; see http://www.php.net/manual/en/timezones.php for supported values
timeZone="Europe/Paris"

theme=v2



; default domain name to use with jfullurl for example.
; Let it empty to use $_SERVER['SERVER_NAME'] value instead.
; Let it empty to use $_SERVER['SERVER_NAME'] value instead.
domainName=


[modules]
jcommunity.installparam=manualconfig

jelix.enabled=on
master_admin.enabled=on
jacl2db_admin.enabled=on
jauthdb_admin.enabled=on
jacl2.enabled=on
jacl2db.enabled=on
jauth.enabled=off
jauthdb.enabled=off
jcommunity.enabled=on
jtags.enabled=on
boosteradmin.enabled=on
booster.enabled=on
jelix.installparam[wwwfiles]=vhost
jcommunity.dbprofile=hfnu
jauthdb_admin.dbprofile=hfnu

[coordplugins]
;name = file_ini_name or 1

[tplplugins]
defaultJformsBuilder=html

[responses]
html=myHtmlResponse

[error_handling]
;errorMessage="A technical error has occured (code: %code%). Sorry for this inconvenience."

;[compilation]
checkCacheFiletime=on
force=off

[urlengine]

; this is the url path to the jelix-www content (you can found this content in lib/jelix-www/)
; because the jelix-www directory is outside the yourapp/www/ directory, you should create a link to
; jelix-www, or copy its content in yourapp/www/ (with a name like 'jelix' for example)
; so you should indicate the relative path of this link/directory to the basePath, or an absolute path.
jelixWWWPath="jelix/"


; enable the parsing of the url. Set it to off if the url is already parsed by another program
; (like mod_rewrite in apache), if the rewrite of the url corresponds to a simple url, and if
; you use the significant engine. If you use the simple url engine, you can set to off.
enableParser=on

multiview=off

; basePath corresponds to the path to the base directory of your application.
; so if the url to access to your application is http://foo.com/aaa/bbb/www/index.php, you should
; set basePath = "/aaa/bbb/www/".
; if it is http://foo.com/index.php, set basePath="/"
; Jelix can guess the basePath, so you can keep basePath empty. But in the case where there are some
; entry points which are not in the same directory (ex: you have two entry point : http://foo.com/aaa/index.php
; and http://foo.com/aaa/bbb/other.php ), you MUST set the basePath (ex here, the higher entry point is index.php so
; : basePath="/aaa/" )
basePath=

entrypointExtension=.php

notFoundAct=

assetsRevision=autoconfig

[fileLogger]
default=messages.log
error=errors.log
warning=errors.log
notice=errors.log
deprecated=errors.log
strict=errors.log
debug=debug.log

[mailLogger]
;email = root@localhost
;emailHeaders = "Content-Type: text/plain; charset=UTF-8\nFrom: webmaster@yoursite.com\nX-Mailer: Jelix\nX-Priority: 1 (Highest)\n"

[logger]
default="file,memory"
error="file,memory"
warning="file,memory"
notice="file,memory"
sql=memory
debug=memory

[debugbar]
plugins="sqllog,sessiondata,defaultlog"

[mailer]
webmasterEmail="webmaster@jelix.org"
webmasterName="Webmaster Booster.jelix.org"

; How to send mail : "mail" (mail()), "sendmail" (call sendmail), "smtp" (send directly to a smtp)
;                   or "file" (store the mail into a file, in filesDir directory)
mailerType=mail
; Sets the hostname to use in Message-Id and Received headers
; and as default HELO string. If empty, the value returned
; by SERVER_NAME is used or 'localhost.localdomain'.
hostname=
sendmailPath="/usr/sbin/sendmail"

; if mailer = file, fill the following parameters
; this should be the directory in the var/ directory, where to store mail as files
filesDir="mails/"

; if mailer = smtp , fill the following parameters

; SMTP hosts.  All hosts must be separated by a semicolon : "smtp1.example.com:25;smtp2.example.com"
smtpHost=localhost
; default SMTP server port
smtpPort=25
; secured connection or not. possible values: "", "ssl", "tls"
smtpSecure=
; SMTP HELO of the message (Default is hostname)
smtpHelo=
; SMTP authentication
smtpAuth=off
smtpUsername=
smtpPassword=
; SMTP server timeout in seconds
smtpTimeout=10



[acl2]
; example of driver: "db"
driver=db

[sessions]
; If several applications are installed in the same documentRoot but with
; a different basePath, shared_session indicates if these application
; share the same php session
shared_session=off

; indicate a session name for each applications installed with the same
; domain and basePath, if their respective sessions shouldn't be shared
name=

; Use alternative storage engines for sessions
;storage = "files"
;files_path = "app:var/sessions/"
;
; or
;
;storage = "dao"
;dao_selector = "jelix~jsession"
;dao_db_profile = ""


[forms]
; define input type for datetime widgets : "textboxes" or "menulists"
;controls.datetime.input = "menulists"
; define the way month labels are displayed widgets: "numbers", "names" or "shortnames"
;controls.datetime.months.labels = "names"
; define the default config for datepickers in jforms
;datepicker = default

[datepickers]
;default = jelix/js/jforms/datepickers/default/init.js

[jResponseHtml]
;plugins=debugbar

;concatene et compress les fichier CSS
minifyCSS=off

;concatene et compress les fichier JS
minifyJS=off

; check all filemtime() of original js files to check if minify's cache should be generated again.
; Should be set to "off" on production servers (i.e. manual empty cache needed when a file is changed) :
minifyCheckCacheFiletime=off


[wikieditors]
default.engine.name=wr3
default.wiki.rules=wr3_to_xhtml

[booster]
title="Jelix Booster: Applis, Modules, Plugins, Pack de langues pour Jelix"
last_items_created=5
moderators_list="laurent@jelix.org, olivier@foxmask.info"

[webassets_common]
booster.css[]="$theme/css/reset.css"
booster.css[]="$theme/css/text.css"
booster.css[]="$theme/css/booster.css"
booster.js[]="booster/js/booster.js"
booster.js[]="booster/js/charCount.js"
booster.require="jquery"

jacl2_admin.css[]="$jelix/design/jacl2.css"
jacl2_admin.js[]="$jelix/js/jacl2db_admin.js"
jacl2_admin.require[]=jquery_ui

jauthdb_admin.js[]="$jelix/js/authdb_admin.js"
jauthdb_admin.require[]=jquery_ui
