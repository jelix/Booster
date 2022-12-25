;<?php die(''); ?>
;for security reasons , don't remove or modify the first line


[responses]
html=adminHtmlResponse
htmlauth=adminLoginHtmlResponse

[coordplugins]
auth="adminboost/auth.coord.ini.php"
jacl2=1

[jacl2]
on_error=2
; locale key for the error message when on_error=1
error_message="jacl2~errors.action.right.needed"

; action to execute on a missing authentification when on_error=2
on_error_action="jelix~error:badright"

[acl2]
hiddenRights=
hideRights=off
driver=db
authAdapterClass=jAcl2JAuthAdapter
