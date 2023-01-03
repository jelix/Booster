<?php
/**
* @package   booster
* @subpackage items
* @author    Olivier Demah, Laurent Jouanneau
* @copyright 2011 olivier demah, 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class itemsCtrl extends jControllerDaoCrudFilter
{
    public $pluginParams = array(
        '*'     => array('auth.required'=>true,
                         'jacl2.right' =>'booster.admin.index'),
    );


    protected $dao = 'booster~boo_items';

    protected $form = 'boosteradmin~items_mod';

    protected $propertiesForList = array(
        'type_id', 'name', 'created', 'modified',
        'item_by', 'status', 'dev_status');

    protected $propertiesForRecordsOrder = array(
        'type_id'=>'', 'name'=>'asc', 'created'=>'', 'modified'=>'',
        'status'=>'', 'dev_status'=>'');

    protected $listTemplate = 'boosteradmin~items_list';

    /**
     * template to display the form.
     *
     * @var string
     */
    protected $editTemplate = 'boosteradmin~items_edit';

    /**
     * template to display a record.
     *
     * @var string
     */
    protected $viewTemplate = 'boosteradmin~items_view';


    protected $filterForm = 'boosteradmin~items_filter';

    protected $recToSave;

    protected function _beforeSaveCreate($form, $form_daorec)
    {
        $this->recToSave = $form_daorec;
        $form_daorec->modified = date('Y-m-d H:i:s');
        $form_daorec->item_by = jAuth::getUserSession()->id;
    }

    protected function _afterCreate($form, $id, $resp)
    {
        $tagStr = str_replace('.',' ',$form->getData("tags"));
        $tags = explode(",", $tagStr);

        \jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id);

        $booster = new \JelixBooster\Booster();
        $this->recToSave->image = $booster->saveImage($id, $form);
        jDao::get($this->dao, $this->dbProfile)->update($this->recToSave);
    }

    protected function _editUpdate($form, $resp, $tpl)
    {
        $tags = implode(',', jClasses::getService("jtags~tags")->getTagsBySubject('booscope', $form->id()) ) ;
        $form->setData('tags', $tags);
    }

    protected function _beforeSaveUpdate($form, $form_daorec, $id)
    {
        $form_daorec->modified = date('Y-m-d H:i:s');

        $tagStr = str_replace('.',' ',$form->getData("tags"));
        $tags = explode(",", $tagStr);

        \jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id);

        $booster = new \JelixBooster\Booster();
        $form_daorec->image = $booster->saveImage($id, $form);
    }

    protected function _view($form, $resp, $tpl)
    {
        // FIXME : display versions
    }

    function _delete($id, $resp)
    {
        $id = $this->intParam('id');
        $dao = jDao::get('booster~boo_items', $this->dbProfile);
        $rec = $dao->get($id);
        if ($rec) {
            if ($rec->image && file_exists(\jApp::wwwPath('images-items/'.$rec->image))) {
                unlink(\jApp::wwwPath('images-items/'.$rec->image));
            }
            jDao::get('boosteradmin~boo_items_modifs')->deleteByItemId($id);
            $versionsDao = jDao::get('booster~boo_versions');
            $versionsModifDao = jDao::get('boosteradmin~boo_versions_modifs');
            foreach($versionsDao->findByItem($id) as $version) {
                $versionsModifDao->deleteByVersionId($version->id);
            }
        }
        return true;
    }

}
