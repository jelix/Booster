<?php
class boosterModuleUpgrader_jelixversion extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.3.1');
    public $date = '2023-01-01';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers) {

        $db = $helpers->database()->dbConnection();
        $db->exec('ALTER TABLE boo_versions MODIFY COLUMN `id_jelix_version` int(12) DEFAULT NULL');
        $db->exec('ALTER TABLE boo_versions MODIFY COLUMN `id_jelix_version_max` int(12) DEFAULT NULL');

    }
}