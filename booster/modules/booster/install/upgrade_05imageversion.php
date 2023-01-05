<?php
class boosterModuleUpgrader_05imageversion extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.3.0');
    public $date = '2022-12-30';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers) {

        $db = $helpers->database()->dbConnection();

        $db->exec('ALTER TABLE boo_items ADD COLUMN `image` varchar(255) default NULL');
        foreach($db->query('Select id from boo_items') as $rec) {
            $image = md5('id:'.$rec->id).'.png';
            if (file_exists(\jApp::wwwPath('images-items/'.$image))) {
                $db->exec('UPDATE boo_items SET image='.$db->quote($image).' WHERE id='.$rec->id);
            }
        }

        $db->exec('ALTER TABLE boo_versions ADD COLUMN `version_date` datetime default NULL');
        $db->exec('ALTER TABLE boo_versions ADD COLUMN `id_jelix_version_max` int(12) DEFAULT NULL');
        $db->exec('UPDATE boo_versions SET version_date = created');
        $db->exec('UPDATE boo_versions SET id_jelix_version_max = id_jelix_version');
        $db->exec('ALTER TABLE boo_versions MODIFY COLUMN `id_jelix_version_max` int(12) NOT NULL');

        $db->exec('DELETE FROM boo_jelix_versions WHERE id=4');
    }
}