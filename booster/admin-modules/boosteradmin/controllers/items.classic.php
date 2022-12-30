<?php
/**
* @package   booster
* @subpackage items
* @author    Olivier Demah, Laurent Jouanneau
* @copyright 2011 olivier demah, 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class itemsCtrl extends jController {
    public $pluginParams = array(
        '*'     => array('auth.required'=>true,
                         'jacl2.right' =>'booster.admin.index'),
    );
    /**
     * Index page that list all the "waiting items"
     */
    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $conn = jDb::getConnection('booster');
        $itemModified = $conn->query('SELECT bi.id, bi.name, bim.date, bi.item_by 
            FROM boo_items_modifs bim 
            INNER JOIN boo_items bi ON (bim.item_id = bi.id) GROUP BY bi.id, bi.name, bi.item_by');
        $tpl->assign('datas_mod', $itemModified);
        $tpl->assign('datas_new',jDao::get('booster~boo_items','booster')->findAllNotModerated());
        $rep->body->assign('MAIN',$tpl->fetch('items_mod'));
        return $rep;
    }
    /**
     * Index page that list all the validated items
     */
    function indexAll() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $tpl->assign('datas',jDao::get('booster~boo_items','booster')->findAllValidated());
        $rep->body->assign('MAIN',$tpl->fetch('items_all'));
        return $rep;
    }
    /**
     * edit the new submitted item
     */
    function editnew() {
        $id = $this->intParam('id');
        $form = jForms::create('boosteradmin~items_mod', $id);
        $form->initFromDao('booster~boo_items');
        $form->setData('id',$id);

        $tags = implode(',', jClasses::getService("jtags~tags")->getTagsBySubject('booscope', $id) ) ;
        $form->setData('tags', $tags);
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('id',$id);
        $tpl->assign('title',jLocale::get('boosteradmin~admin.item.validation.or.modification'));
        $tpl->assign('form',$form);
        $tpl->assign('item_by',jDao::get('booster~boo_items','booster')->get($id)->item_by);
        $tpl->assign('action','boosteradmin~items:savenew');
        $rep->body->assign('MAIN',$tpl->fetch('edit'));
        return $rep;
    }
    /**
     * Save the new submitted item
     */
    function savenew() {
        $id = $this->intParam('id');
        $form = jForms::fill('boosteradmin~items_mod',$id);
        $rep = $this->getResponse('redirect');

        if ($form->check()) {
            if ($form->getData('short_desc_fr') == ''  and
                $form->getData('short_desc') == '' ) {
                $form->setErrorOn('short_desc', jLocale::get('booster~main.desc.mandatory'));
                $form->setErrorOn('short_desc_fr', jLocale::get('booster~main.desc.mandatory'));
                $rep->action='add';
                return $rep;
            }

            // we validate the new item
            // then remove the data from the "waiting table" (items_mod)
            if ($form->getData('status')==1) {
                jMessage::add(jLocale::get('boosteradmin~admin.item_validated'));
            }
            //validator submit via the "Validate" button so we automaticaly validate the item
            elseif($form->getData('_validate')){
                $form->setData('status', 1);
                jMessage::add(jLocale::get('boosteradmin~admin.item_validated'));
            }
            // we just edit the new content of the item
            // but we didn't validate it so :
            // save all the modification
            else {
                jMessage::add(jLocale::get('boosteradmin~admin.item_saved_but_not_validated_yet'));
            }
            $booster = new \JelixBooster\Booster();
            $booster->saveImage($id, $form);

            $form->saveToDao('booster~boo_items');
            jClasses::getService("jtags~tags")->saveTagsBySubject(explode(',', $form->getData('tags')), 'booscope', $id);

            jForms::destroy('boosteradmin~items_mod',$id);
        }
        else {
            jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
        }

        $rep->action = 'boosteradmin~items:index';
        return $rep;
    }
    /**
     * Edit the Modified Item for moderation
     */
    function editmod() {

        $id = $this->intParam('id');
        $form = jForms::create('boosteradmin~items_mod',$id);
        $form->initFromDao('booster~boo_items');
        $form->setData('id', $id);
        $tags = implode(',', jClasses::getService("jtags~tags")->getTagsBySubject('booscope', $id) ) ;
        $form->setData('tags', $tags);
        $name = $form->getData('name');

        $modified = jDao::get('boosteradmin~boo_items_modifs')->findByItemId($id);
        $modified_fields = array();
        foreach($modified as $m) {
            if ($m->field == 'image') {
                /** @var jFormsControlImageUpload $ctrlImage */
                $ctrlImage = $form->getControl('image');
                // we copy the image into the temporary directory, so jForms::saveFile will work as usual.
                $source = \jApp::wwwPath('images-items/'.$m->new_value);
                $target = $ctrlImage->getTempFile($m->new_value);
                copy($source, $target);
                $ctrlImage->setNewFile($m->new_value);
            }
            else {
                $form->setData($m->field, $m->new_value);
            }

            if($m->field == 'type_id') {
                $dao_type = jDao::get('booster~boo_type');
                $m->new_value = $dao_type->get($m->new_value)->type_name;
                $m->old_value = $dao_type->get($m->old_value)->type_name;
            }

            $modified_fields[] = $m;
        }


        $tpl = new jTpl();
        $tpl->assign('id',$id);
        $tpl->assign('name',$name);
        $tpl->assign('modified',$modified_fields);
        $tpl->assign('title',jLocale::get('boosteradmin~admin.item.validation.or.modification'));
        $tpl->assign('form',$form);
        $tpl->assign('action','boosteradmin~items:savemod');

        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('edit_items_modifs'));
        return $rep;
    }
    /**
     * Save the Modified Item
     */
    function savemod() {
        $rep = $this->getResponse('redirect');
        $id = $this->intParam('id');
        $form = jForms::fill('boosteradmin~items_mod',$id);
        if ($form->check()) {
            if ($form->getData('short_desc_fr') == '' && $form->getData('short_desc') == '' ) {
                $form->setErrorOn('short_desc',jLocale::get('booster~main.desc.mandatory'));
                $form->setErrorOn('short_desc_fr',jLocale::get('booster~main.desc.mandatory'));
                $rep->action='add';
                return $rep;
            }

            $tagStr = str_replace('.',' ',$form->getData("tags"));
            $tags = explode(",", $tagStr);
            jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'booscope', $id);

            $booster = new \JelixBooster\Booster();
            $booster->saveImage($id, $form);

            $form->saveToDao('booster~boo_items');

            jDao::get('boosteradmin~boo_items_modifs','booster')->deleteByItemId($id);
            jMessage::add(jLocale::get('boosteradmin~admin.item_validated'));

            jForms::destroy('boosteradmin~items_mod',$id);
            $rep->action = 'boosteradmin~items:index';
        }
        else {
            jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
            $rep->action = 'boosteradmin~items:editmod';
            $rep->params = array('id' => $id);
        }
        return $rep;
    }

    function delete()
    {
        $id = $this->intParam('id');
        $dao = jDao::get('booster~boo_items');
        $rec = $dao->get($id);
        if ($rec) {
            if ($rec->image) {
                unlink(\jApp::wwwPath('images-items/'.$rec->image));
            }
            jDao::get('boosteradmin~boo_items_modifs')->deleteByItemId($id);
            $versionsDao = jDao::get('boosteradmin~boo_versions');
            $versionsModifDao = jDao::get('boosteradmin~boo_versions_modifs');
            foreach($versionsDao->findByItem($id) as $version) {
                $versionsModifDao->deleteByVersionId($version->version_id);
            }

            $dao->delete($id);
            jMessage::add(jLocale::get('boosteradmin~admin.item.deleted'));
        }
        else {
            jMessage::add(jLocale::get('boosteradmin~admin.item.not.deleted'));
        }
        return $this->redirect('boosteradmin~items:indexAll');
    }

}
