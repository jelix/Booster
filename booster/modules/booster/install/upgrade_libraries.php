<?php
class boosterModuleUpgrader_libraries extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.2.0');
    public $date = '2022-12-28';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers) {

        $db = $helpers->database()->dbConnection();

        $db->exec('INSERT INTO '.$db->prefixTable('boo_type').' (`id`, `type_name`) 
            VALUES (5, \'Library\')');

        $db->exec('UPDATE '.$db->prefixTable('boo_type').' SET type_name=\'Plugin\' WHERE id = 3');
        $db->exec('UPDATE '.$db->prefixTable('boo_type').' SET type_name=\'LangPack\' WHERE id = 4');

        $db->exec('ALTER TABLE boo_items ADD COLUMN `slogan` varchar(255) default NULL');
        $db->exec('ALTER TABLE boo_items ADD COLUMN `slogan_fr` varchar(255) default NULL');

    }
}