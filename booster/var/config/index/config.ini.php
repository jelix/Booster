;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=booster
startAction="default:index"

availableLocales="fr_FR,en_US"
pluginsPath="app:plugins/,lib:jelix-plugins/,module:jacl2db/plugins"
[responses]

[modules]


master_admin.access=2
jacl2db_admin.access=2
jauthdb_admin.access=2

jacl2.access=1
jacl2db.access=2
jauthdb.access=1


booster.access=2

[coordplugins]
auth="index/auth.coord.ini.php"
autolocale="index/autolocale.plugin.ini.php"
jacl2="index/jacl2.coord.ini.php"

[acl2]
driver=db
