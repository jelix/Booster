<?php

use JelixBooster\PackagistException;

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
        'item_by', 'status', 'dev_status', 'reviewed');

    protected $propertiesForRecordsOrder = array(
        'type_id'=>'', 'name'=>'asc', 'created'=>'', 'modified'=>'',
        'status'=>'', 'dev_status'=>'', 'reviewed'=>'');

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

    protected function _create($form, $resp, $tpl)
    {
        $tpl->assign('title', jLocale::get('booster~main.new.item'));
    }

    protected function _checkData($form, $calltype)
    {
        $composerId = $form->getData('item_composer_id');
        if ($composerId == '') {
            return true;
        }
        $booster = new \JelixBooster\Booster();
        if ($booster->isComposerPackageReferenced($composerId, $form->id())) {
            $form->setErrorOn('item_composer_id', jLocale::get('boosteradmin~admin.package.already.referenced'));
            return false;
        }
        return true;
    }

    protected function _beforeSaveCreate($form, $form_daorec)
    {
        $this->recToSave = $form_daorec;
        $form_daorec->modified = date('Y-m-d H:i:s');
        $form_daorec->review_date = date('Y-m-d H:i:s');
        $form_daorec->reviewed = 1;
        $form_daorec->item_by = jAuth::getUserSession()->id;
        if ($form->getData('_validate')) {
            $form_daorec->status = 1;
            jMessage::add(jLocale::get('boosteradmin~admin.item_validated'));
        }
    }

    protected function _afterCreate($form, $id, $resp)
    {
        $booster = new \JelixBooster\Booster();
        $booster->saveTags($id, $this->recToSave->tags, $this->recToSave->dev_status);
        $this->recToSave->image = $booster->saveImage($id, $form);
        jDao::get($this->dao, $this->dbProfile)->update($this->recToSave);
    }

    protected function _editUpdate($form, $resp, $tpl)
    {
        $tpl->assign('title', jLocale::get('booster~main.edit.item'));
    }

    protected function _beforeSaveUpdate($form, $form_daorec, $id)
    {
        $form_daorec->modified = date('Y-m-d H:i:s');
        $form_daorec->review_date = date('Y-m-d H:i:s');
        $form_daorec->reviewed = 1;

        if ($form->getData('_validate')) {
            $form_daorec->status = 1;
            jMessage::add(jLocale::get('boosteradmin~admin.item_validated'));
        }

        $booster = new \JelixBooster\Booster();
        $booster->saveTags($id, $form_daorec->tags, $form_daorec->dev_status);
        $form_daorec->image = $booster->saveImage($id, $form);
    }

    protected function _view($form, $resp, $tpl)
    {
        $versions = jDao::get('booster~boo_versions')->findByItem($form->getData('id'));
        $tpl->assign('versions', $versions);
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

            $booster = new \JelixBooster\Booster();
            $booster->saveTags($id, '', $booster::DEV_STATUS_GONE);
        }
        return true;
    }


    function startReview()
    {
        jDao::get($this->dao)->startReview();
        return $this->redirect('boosteradmin~items:index');
    }

    function reviewed()
    {
        if ($this->param('id')) {
            $items = jDao::get($this->dao);
            $items->reviewed($this->param('id'));
            $next = $items->nextToReview();
            if ($next) {
                return $this->redirect('boosteradmin~items:view', array('id'=>$next->id));
            }
            jMessage::add(jLocale::get('boosteradmin~admin.review.no.more'));
        }

        return $this->redirect('boosteradmin~items:index');
    }

    /**
     *
     */
    function createVersion()
    {
        $itemId = $this->intParam('itemid');
        $project = jDao::get($this->dao)->get($itemId);
        if (!$project) {
            jMessage::add('Unknown project');
            return $this->redirect('boosteradmin~items:index');
        }

        $form = jForms::get('boosteradmin~versions_mod');
        if (!$form) {
            $form = jForms::create('boosteradmin~versions_mod');
        }

        $form->setData('item_id', $itemId);

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title', jLocale::get('boosteradmin~admin.version.edit.new.for').' '.$project->name);
        $tpl->assign('action', 'boosteradmin~items:saveNewVersion');
        $tpl->assign('actionParams', array('itemid'=>$itemId));
        $tpl->assign('form',$form);
        $tpl->assign('modified',false);
        $tpl->assign('item_id', $itemId);
        $rep->body->assign('MAIN',$tpl->fetch('edit_version'));
        return $rep;
    }

    /**
     * Save a new version
     */
    function saveNewVersion()
    {
        $itemId = $this->intParam('itemid');
        $projectDao = jDao::get($this->dao);
        $project = $projectDao->get($itemId);
        if (!$project) {
            jMessage::add('Unknown project');
            return $this->redirect('boosteradmin~items:index');
        }

        $form = jForms::fill('boosteradmin~versions_mod');
        if ($form->check()) {
            //validator submit via the "Validate" button so we automaticaly validate the version
            if($form->getData('_validate')){
                $form->setData('status', 1);
                jMessage::add(jLocale::get('boosteradmin~admin.version_validated'));
            }
            $jelixVersionMin = $form->getData('id_jelix_version');
            $jelixVersionMax = $form->getData('id_jelix_version_max');

            if ($jelixVersionMin && $jelixVersionMax &&  $jelixVersionMin > $jelixVersionMax) {
                $form->setData('id_jelix_version', $jelixVersionMax);
                $form->setData('id_jelix_version_max', $jelixVersionMin);
            }

            //item_by
            $form->saveToDao('booster~boo_versions');
            //update the modified date of the project
            $project->modified = date("Y-m-d H:i:s");
            $projectDao->update($project);
            jForms::destroy('boosteradmin~versions_mod');
            return $this->redirect('boosteradmin~items:view', array('id'=>$itemId));
        }
        jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
        return $this->redirect('boosteradmin~items:createVersion', array('itemid'=>$itemId));
    }

    /**
     *
     */
    function editVersion()
    {
        $itemId = $this->intParam('itemid');
        $project = jDao::get($this->dao)->get($itemId);
        if (!$project) {
            jMessage::add('Unknown project');
            return $this->redirect('boosteradmin~items:index');
        }
        $vId = $this->intParam('id');

        // charger les modifications existantes...
        $form = jForms::get('boosteradmin~versions_mod', $vId);
        if (!$form) {
            $form = jForms::create('boosteradmin~versions_mod', $vId);
            $form->initFromDao('booster~boo_versions');

            $modified = jDao::get('boosteradmin~boo_versions_modifs')->findByVersionId($vId);
            $modified_fields = array();
            foreach($modified as $m){
                $form->setData($m->field, $m->new_value);
                $modified_fields[] = $m;
            }
        }
        else {
            $modified_fields = false;
        }

        $form->setData('item_id', $itemId);

        $form->setData('id',$this->intParam('id'));
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title', jLocale::get('boosteradmin~admin.version.edit.modif.of',
            array($form->getData('version_name'), $project->name)));

        $tpl->assign('form', $form);
        $tpl->assign('action', 'boosteradmin~items:saveVersion');
        $tpl->assign('actionParams', array('itemid'=>$itemId, 'id'=>$vId));
        $tpl->assign('id', $this->intParam('id'));
        $tpl->assign('modified', $modified_fields);
        $tpl->assign('item_id', $itemId);
        $rep->body->assign('MAIN', $tpl->fetch('edit_version'));
        return $rep;
    }
    /**
     * Save the new submitted version
     */
    function saveVersion()
    {
        $itemId = $this->intParam('itemid');
        $projectDao = jDao::get($this->dao);
        $project = $projectDao->get($itemId);
        if (!$project) {
            jMessage::add('Unknown project');
            return $this->redirect('boosteradmin~items:index');
        }
        $vId = $this->intParam('id');
        $form = jForms::fill('boosteradmin~versions_mod', $vId);
        if ($form->check()) {

            //validator submit via the "Validate" button so we automatically validate the version
            if($form->getData('_validate')){
                $form->setData('status', 1);
                jMessage::add(jLocale::get('boosteradmin~admin.version_validated'));
            }

            $jelixVersionMin = $form->getData('id_jelix_version');
            $jelixVersionMax = $form->getData('id_jelix_version_max');

            if ($jelixVersionMin && $jelixVersionMax &&  $jelixVersionMin > $jelixVersionMax) {
                $form->setData('id_jelix_version', $jelixVersionMax);
                $form->setData('id_jelix_version_max', $jelixVersionMin);
            }

            $form->saveToDao('booster~boo_versions');
            //update the modified date of the project
            $project->modified = date("Y-m-d H:i:s");
            $projectDao->update($project);
            jForms::destroy('boosteradmin~versions_mod', $vId);
            jDao::get('boosteradmin~boo_versions_modifs','booster')->deleteByVersionId($vId);
            return $this->redirect('boosteradmin~items:view', array('id'=>$itemId));
        }
        jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
        return $this->redirect('boosteradmin~items:editVersion', array('itemid'=>$itemId, 'id'=>$vId));

    }


    public function checkcomposer()
    {
        $composerId = $this->param('package');
        $booster = new \JelixBooster\Booster();
        $rep = $this->getResponse('json');

        if ($booster->isComposerPackageReferenced($composerId)) {
            $rep->data = array(
                'error' => jLocale::get('boosteradmin~admin.package.already.referenced')
            );
            return $rep;
        }

        try {
            $package = $booster->getComposerPackageInfo($composerId);

            $rep->data = array(
                'found' => true,
                'values' => [
                    'name' => ucwords(str_replace('/', ' ', $composerId)),
                    'type_id' => '',
                    'slogan' => '',
                    'author' => '',
                    'tags' => '',
                    'url_website' => '',
                    'url_repo' => '',
                    'dev_status' => 0
                ]
            );

            if (isset($package['type']) && isset(\JelixBooster\Booster::PACKAGIST_TYPE[$package['type']])) {
                $rep->data['values']['type_id'] = \JelixBooster\Booster::PACKAGIST_TYPE[$package['type']];
            }

            if (isset($package['description'])) {
                $rep->data['values']['slogan'] = $package['description'];
            }
            if (isset($package['authors'])) {

                $authors = array_map(function($author) {
                    return $author['name'];
                    }, $package['authors']);

                $rep->data['values']['author'] = implode(', ', $authors);
            }
            if (isset($package['keywords'])) {
                $rep->data['values']['tags'] = implode(', ', $package['keywords']);
            }
            if (isset($package['homepage'])) {
                $rep->data['values']['url_website'] = $package['homepage'];
            }

            if (isset($package['source']['url'])) {
                $url = $package['source']['url'];
                if (strpos($url, 'https://github.com/') === 0) {
                    $url = str_replace('.git', '/', $url);
                }
                $rep->data['values']['url_repo'] = $url;
            }

            if (isset($package['abandoned']) && $package['abandoned']) {
                $rep->data['values']['dev_status'] = 1;
            }

        }
        catch(PackagistException $e) {
            if ($e->getCode() == 404) {
                $rep->data = array(
                    'error' => jLocale::get('boosteradmin~admin.packagist.error.not.found')
                );
            }
            else {
                $rep->data = array(
                    'error' => jLocale::get('boosteradmin~admin.packagist.error')
                );
                jLog::log($e->getMessage(), 'warning');
            }
        }
        catch(\Exception $e) {
            $rep->data = array(
                'error' => $e->getMessage()
            );
        }


        return $rep;
    }
}
