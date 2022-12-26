<?php
class boosterModuleUpgrader_dev_status extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.1.0');
    public $date = '2022-12-26';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers) {

        $db = $helpers->database()->dbConnection();

        $db->exec('ALTER TABLE boo_items ADD COLUMN `dev_status` int(1) NOT NULL default 0');
        $db->exec('ALTER TABLE boo_items ADD COLUMN `item_composer_id` varchar(255) default NULL');
        $db->exec('ALTER TABLE boo_items ADD COLUMN `url_download` varchar(255) default NULL');
        $db->exec('ALTER TABLE boo_items DROP COLUMN item_info_id');
    }
 
} 