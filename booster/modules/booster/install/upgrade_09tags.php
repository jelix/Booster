<?php
class boosterModuleUpgrader_09tags extends \Jelix\Installer\Module\Installer {
 
    public $targetVersions = array('1.4.1');
    public $date = '2023-01-07 13:00';
 
    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        $db = $helpers->database()->dbConnection();
        $db->exec('ALTER TABLE boo_items ADD COLUMN `tags` varchar(255) DEFAULT NULL');
        $db->exec("ALTER TABLE boo_versions MODIFY COLUMN `stability` enum('pre-alpha','alpha','beta','stable','mature') NOT NULL DEFAULT 'stable'");

        $itemList = $db->query('SELECT id FROM boo_items');
        foreach ($itemList as $item) {
            $tagsList = $db->query(
                'SELECT t.tag_name 
                FROM sc_tags_tagged tt 
                INNER JOIN sc_tags t ON (t.tag_id = tt.tag_id)
                WHERE tt.tt_subject_id = '.$item->id.' and tt_scope_id=\'booscope\''
            );
            $tags = [];
            foreach($tagsList as $tag) {
                $tags[] = $tag->tag_name;
            }
            $db->exec('UPDATE boo_items SET tags='.$db->quote(implode(',', $tags))." WHERE id=".$item->id);
        }

    }
}