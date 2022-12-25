;<?php die(''); ?>
;for security reasons , don't remove or modify the first line


availableLocales="fr_FR,en_US"
[responses]

[coordplugins]
auth="index/auth.coord.ini.php"
autolocale=1
jacl2=1

[acl2]
driver=db

hiddenRights=
hideRights=off
authAdapterClass=jAcl2JAuthAdapter
[autolocale]
;active la detection du changement de langage via l'url fournie
enableUrlDetection=on

;indique le nom du parametre url qui contient la langue choisie par l'utilisateur
urlParamNameLanguage=lang

; utilisation du langage indiqué dans le navigateur
useDefaultLanguageBrowser=on


[jacl2]
; What to do if a right is required but the user has not this right
; 1 = generate an error. This value should be set for web services (xmlrpc, jsonrpc...)
; 2 = redirect to an action
on_error=2

; locale key for the error message when on_error=1
error_message="jacl2~errors.action.right.needed"

; action to execute on a missing authentification when on_error=2
on_error_action="jelix~error:badright"
