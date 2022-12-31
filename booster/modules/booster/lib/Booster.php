<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah, Florian Lonqueu-Brochard, Laurent Jouanneau
* @copyright 2011 Olivier Demah, Florian Lonqueu-Brochard, 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

namespace JelixBooster;


/**
 * Class that handle the booster business stuff
 */
class Booster {

    const TYPE_APPLICATION = 1;
    const TYPE_MODULE = 2;
    const TYPE_PLUGIN = 3;
    const TYPE_LANGPACK = 4;
    const TYPE_LIBRARY = 5;

    /**
     * save a new Item
     *
     * @param \jFormsBase $form
     */
    function saveItem($form) {
        $data = array();

        $dao = \jDao::get('booster~boo_items','booster');
        $record = \jDao::createRecord('booster~boo_items','booster');
        $record->name           = $form->getData('name');
        $record->item_composer_id = $form->getData('item_composer_id');
        $record->slogan     = $form->getData('slogan');
        $record->slogan_fr     = $form->getData('slogan_fr');
        $record->short_desc  = $form->getData('short_desc');
        $record->short_desc_fr  = $form->getData('short_desc_fr');
        $record->type_id        = $form->getData('type_id');
        $record->url_website    = $form->getData('url_website');
        $record->url_repo       = $form->getData('url_repo');
        $record->url_download   = $form->getData('url_download');
        $record->dev_status     = $form->getData('dev_status');
        $record->author         = $form->getData('author');
        $record->item_by        = $form->getData('item_by');
        $record->status         = 0; //will need moderation

        if ($dao->insert($record)) {
            $id_booster = $record->id;
            $data['id']     = $id_booster;
            $data['name']   = $form->getData('name');

            $tagStr = str_replace('.',' ',$form->getData("tags"));
            $tags = explode(",", $tagStr);

            \jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id_booster);

            $record->image = $this->saveImage($id_booster, $form, false);
            $dao->update($record);
        }

        return $data;
    }
    /**
     * save a new Version
     * @param \jFormsBase $form
     * @return boolean
     */
    function saveVersion($form) {
        $dao = \jDao::get('booster~boo_versions','booster');
        $record = \jDao::createRecord('booster~boo_versions','booster');
        $record->version_name   = $form->getData('version_name');
        $record->version_date   = $form->getData('version_date');
        $record->status         = 0; //will need moderation

        $jelixVersionMin = $form->getData('id_jelix_version');
        $jelixVersionMax = $form->getData('id_jelix_version_max');

        if ($jelixVersionMin <= $jelixVersionMax) {
            $record->id_jelix_version = $jelixVersionMin;
            $record->id_jelix_version_max = $jelixVersionMax;
        }
        else {
            $record->id_jelix_version = $jelixVersionMax;
            $record->id_jelix_version_max = $jelixVersionMin;
        }

        $record->item_id        = $form->getData('item_id');
        $record->last_changes   = $form->getData('last_changes');
        $record->stability      = $form->getData('stability');
        $record->filename       = $form->getData('filename');
        $record->download_url   = $form->getData('download_url');
        return ($dao->insert($record)) ? true : false;
    }

    /**
     * function to save one Editing Item
     * to the dedicated "waiting table"
     *
     * @param \jFormsBase $form
     */
    function saveEditItem($form) {
        $dao_modif = \jDao::get('boosteradmin~boo_items_modifs');
        $id = $form->getData('id');
        $this->saveImage($id, $form, false);
        foreach($form->getModifiedControls() as $field => $old_value){
            if($field == '_submit')
                continue;

            $record = \jDao::createRecord('boosteradmin~boo_items_modifs');
            $record->field = $field;
            $record->item_id = $id;
            $record->old_value = $old_value;
            $record->new_value = $form->getData($field);
            $dao_modif->insert($record);
        }

        return true;
    }
    /**
     * function to save one Editing Item
     * to the dedicated waiting table
     * @param \jFormsBase $form
     * @return boolean
     */
    function saveEditVersion($form) {
        $dao_modif = \jDao::get('boosteradmin~boo_versions_modifs');
        foreach ($form->getModifiedControls() as $field => $old_value) {
            if($field == '_submit')
                continue;

            $record = \jDao::createRecord('boosteradmin~boo_versions_modifs');
            $record->field = $field;
            $record->version_id = $form->getData('id');
            $record->old_value = $old_value;
            $record->new_value = $form->getData($field);
            $dao_modif->insert($record);
        }

        return true;
    }
    /**
     * function that search items according to criteria in the form
     *
     * @param \jFormsBase $form
     * @return array    items corresponding to the search
     */
    function search($form)
    {
        $name           = $form->getData('name');
        $types          = $form->getData('types');
        $author         = $form->getData('author_by');
        $jelix_versions = $form->getData('jelix_versions');
        $tags           = $form->getData('tags');
        $reco           = $form->getData('recommendation') == '1';
        $devStatus      = $form->getData('dev_status');

        // we have uncheck every checkboxes and empty every fields
        // so let's get all the records
        if ($name === '' &&
            $types === '' &&
            $author === '' &&
            $jelix_versions === '' &&
            $tags === '' &&
            !$reco &&
            $devStatus === ''
            ) {
            return \jDao::get('booster~boo_items', 'booster')->findAllValidated();
        }

        $cond   = '';

        $c = \jDb::getConnection('booster');
        //columns
        $q = 'SELECT items.id,
                    items.status as status,
                    items.name,
                    items.item_composer_id,
                    items.slogan,
                    items.slogan_fr,
                    items.short_desc,
                    items.short_desc_fr,
                    type.id AS type_id,
                    items.url_website,
                    items.url_repo,
                    items.url_download,
                    items.image,
                    items.author,
                    items.item_by,
                    items.recommendation,
                    items.dev_status,
                    type.type_name,
                    versions.id AS version_id,
                    versions.version_name,
                    versions.version_date,
                    versions.last_changes,
                    versions.stability,
                    versions.filename,
                    versions.download_url,
                    versions.status AS status_version,
                    versions.created,
                    versions.edited,
                    versions.modified,
                    versions.id_jelix_version,
                    versions.id_jelix_version_max,
                    jelix_version_min.version as version_jelix_min,
                    jelix_version_max.version as version_jelix_max
                    ';


        //tables
        $from = '
                FROM '.
                $c->prefixTable('boo_items').' AS items
                INNER JOIN ' . $c->prefixTable('boo_type').' AS type ON ( items.type_id = type.id)
                LEFT JOIN ' . $c->prefixTable('boo_versions').' AS versions ON ( items.id=versions.item_id )
                LEFT JOIN ' . $c->prefixTable('boo_jelix_versions'). ' AS jelix_version_min ON (versions.id_jelix_version=jelix_version_min.id )
                LEFT JOIN ' . $c->prefixTable('boo_jelix_versions'). ' AS jelix_version_max ON (versions.id_jelix_version_max=jelix_version_max.id )';

        //where conditions
        $where = "
                WHERE
                    items.status = 1
                    AND (versions.status = 1 OR versions.status IS NULL)" ;

        if($types !== '') {
            $cond .= ' AND type_id = '.intval($types);
        }

        //Name
        if($name !== '') {
            $val = $c->quote('%'.$name.'%');
            $cond .= " AND (name LIKE ".$val." or item_composer_id LIKE ".$val.")";
        }
        //Author
        if($author !== '') {
            $cond .= " AND  author LIKE ".$c->quote('%'.$author.'%')." ) ";
        }

        //version
        if ($jelix_versions !== '') {
            $cond .= ' AND id_jelix_version <= '.intval($jelix_versions);
            $cond .= ' AND id_jelix_version_max >= '.intval($jelix_versions);
        }

        if ($devStatus !== '') {
            $cond .= ' AND dev_status = '.intval($devStatus);
        }
        else {
            $cond .= ' AND (dev_status = 0 OR  dev_status = 1)';
        }

        if ($reco) {
            $cond .= ' AND items.recommendation = 1 ';
        }

        $orderby = ' ORDER BY versions.created desc ';

        $sql = $q.$from.$where.$cond.$orderby;
        //get the datas
        $datas = $c->query($sql);

        $items = $results = array();
        foreach($datas as $item) {
            $items[$item->id] = $item;
        }

        // tags ?
        if( !empty($tags)) {
            //get tags
            $srvTags = \jClasses::getService("jtags~tags");
            $subjects = $srvTags->getSubjectsByTags($tags, "booscope");
            foreach($subjects as $id){
                // get records of this tags
                if(isset($items[$id]) OR empty($items))
                    $results[$id] = $items[$id];
            }
        }
        // no tag !
        else {
            $results = $items;
        }
        return $results;
    }


    /**
     * Check if a given item is moderated or waiting for validation
     * @param int $id the id of the Item
     * @return boolean
     */
    function isModerated($id,$source)
    {
        if($source == 'items'){
            $cnx = \jDb::getConnection();
            $rs = $cnx->limitQuery('SELECT 1 FROM boo_items_modifs WHERE item_id = '.$cnx->quote($id), 0,1);
            return $rs->fetch() == false;
        }

        if($source == 'versions'){
            $cnx = \jDb::getConnection();
            $rs = $cnx->limitQuery('SELECT 1 FROM boo_versions_modifs WHERE version_id = '.$cnx->quote($id), 0,1);
            return $rs->fetch() == false;
        }
        return false;
    }


    public function getImageFileName($id, $originalFileName)
    {
        if (preg_match('/\\.(\w+)$/', $originalFileName, $m)) {
            $ext = $m[1];
        }
        else {
            $ext = 'png';
        }
        return md5('id:'.$id).'.'.$ext;
    }

    /**
     * @param string $id
     * @param \jFormsBase $form
     * @return string
     */
    public function saveImage($id, $form, $fromModeration = true)
    {
        if ($form->getData('image') == '') {
            return '';
        }
        $directory = \jApp::wwwPath('images-items/');
        $image_name = $this->getImageFileName($id, $form->getData('image'));
        /** @var \jFormsControlImageUpload $control */
        $control = $form->getControl('image');
        $image_name = $control->getUniqueFileName($directory, $image_name);
        if ($form->saveFile('image', $directory, $image_name, $fromModeration)) {
            return $image_name;
        }
        return '';
    }

}
