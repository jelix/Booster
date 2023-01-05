<?php
class boosterModuleUpgrader_08review extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.4.0');
    public $date = '2023-01-05 08:00';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        $db = $helpers->database()->dbConnection();
        $db->exec('ALTER TABLE boo_items ADD COLUMN `reviewed` int(12) DEFAULT 0');
        $db->exec('ALTER TABLE boo_items ADD COLUMN `review_date` datetime default NULL');
    }
}