<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @copyright 2011 olivier demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handle the booster business stuff
 */
class booster {
    /**
     * fonction to save one Item
     */
    function saveItem() {
        $data = array();
        $id_booster = 0;

        $form = jForms::fill('booster~items');
        $dao = jDao::get('booster~boo_items');
        $record = jDao::createRecord('booster~boo_items');
        $record->name = $form->getData('name');
        $record->item_info_id = $form->getData('item_info_id');
        $record->short_desc = $form->getData('short_desc');
        $record->type_id = $form->getData('type_id');
        $record->url_website = $form->getData('url_website');
        $record->url_repo = $form->getData('url_repo');
        $record->author = $form->getData('author');
        $record->item_by = $form->getData('item_by');
        $record->status = 0; //will need moderation

        if ($dao->insert($record)) {
            $id_booster = $record->id;
            $data['id']=$id_booster;
            $data['name'] = $form->getData('name');
        }

        if ($id_booster != 0) {
            $tagStr ='';
            $tagStr = str_replace('.',' ',$form->getData("tags"));
            $tags = explode(",", $tagStr);

            jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id_booster);
        }

        return $data;
    }
    /**
     * fonction to save one Version
     * @param object $form
     * @return boolean
     */
    function saveVersion($form) {
        $dao = jDao::get('booster~boo_versions');
        $record = jDao::createRecord('booster~boo_versions');
        $record->version_name = $form->getData('version_name');
        $record->status = 0; //will need moderation
        $record->item_id = $form->getData('item_id');
        $record->last_changes = $form->getData('last_changes');
        $record->stability = $form->getData('stability');
        $record->filename = $form->getData('filename');
        $record->download_url = $form->getData('download_url');
        return ($dao->insert($record)) ? true : false;
    }
    /**
     * fonction to save one Editing Item
     * to the dedicated waiting table
     */
    function saveEditItem($form) {
        $data = array();

        $form = jForms::fill('booster~items');
        $dao = jDao::get('boosteradmin~boo_items_mod');
        $record = jDao::createRecord('boosteradmin~boo_items');
        $record->name = $form->getData('name');
        $record->item_info_id = $form->getData('item_info_id');
        $record->short_desc = $form->getData('short_desc');
        $record->type_id = $form->getData('type_id');
        $record->url_website = $form->getData('url_website');
        $record->url_repo = $form->getData('url_repo');
        $record->author = $form->getData('author');
        $record->item_by = $form->getData('item_by');
        $record->status = 0; //will need moderation
        $return = ($dao->insert($record)) ? true : false;
        $id_booster = $form->getData('id');

        $tagStr ='';
        $tagStr = str_replace('.',' ',$form->getData("tags"));
        $tags = explode(",", $tagStr);

        jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id_booster);

        return $return;
    }
    /**
     * fonction to save one Editing Item
     * to the dedicated waiting table
     * @param object $form
     * @return boolean 
     */
    function saveEditVersion($form) {
        $dao = jDao::get('boosteradmin~boo_versions_mod');
        $record = jDao::createRecord('boosteradmin~boo_versions_mod');
        $record->version_name = $form->getData('version_name');
        $record->status = 0; //will need moderation
        $record->item_id = $form->getData('item_id');
        $record->last_changes = $form->getData('last_changes');
        $record->stability = $form->getData('stability');
        $record->filename = $form->getData('filename');
        $record->download_url = $form->getData('download_url');
        $record->id =  $form->getData('id');
        return ($dao->insert($record)) ? true : false;
    }
}
