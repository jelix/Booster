<?php
class boosterModuleUpgrader_deletedateversion extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.3.2');
    public $date = '2023-01-01 20:00';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        $db = $helpers->database()->dbConnection();
        $db->exec('ALTER TABLE boo_items DROP COLUMN `date_version`');
    }
}