<?php
/**
* @package   booster
* @subpackage versions
* @author    Olivier Demah
* @copyright 2011 olivier demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class versionsCtrl extends jController {
    public $pluginParams = array(
        '*'     => array('auth.required'=>true,
                        'jacl2.right' =>'booster.admin.index'),
    );
    /**
     * Index page that list all the "waiting versions"
     */
    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $tpl->assign('datas_mod',jDao::get('boosteradmin~boo_versions_mod','booster')->findAll());
        $tpl->assign('datas_new',jDao::get('boosteradmin~boo_items_versions','booster')->findAllNotModerated());
        $rep->body->assign('MAIN',$tpl->fetch('versions_mod'));
        return $rep;
    }
    /**
     * Index page that list all the validated items
     */
    function indexAll() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $tpl->assign('datas',jDao::get('boosteradmin~boo_items_versions','booster')->findAllValidated());
        $rep->body->assign('MAIN',$tpl->fetch('versions_all'));
        return $rep;
    }
    /**
     * edit the new submitted versions
     */
    function editnew() {
        $form = jForms::create('boosteradmin~versions_mod',$this->intParam('id'));
        $form->initFromDao('boosteradmin~boo_versions');
        $form->setData('id',$this->intParam('id'));
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title',jLocale::get('boosteradmin~admin.version.validation.or.modification'));
        $tpl->assign('form',$form);
        $tpl->assign('item_by',jDao::get('booster~boo_items','booster')->get($form->getData('item_id'))->item_by);
        $tpl->assign('action','boosteradmin~versions:savenew');
        $tpl->assign('id',$this->intParam('id'));
        $rep->body->assign('MAIN',$tpl->fetch('edit'));
        return $rep;
    }
    /**
     * Save the new submitted version
     */
    function savenew() {
        $form = jForms::fill('boosteradmin~versions_mod',$this->intParam('id'));
        if ($form->check()) {
            // we validate the new item
            // then remove the data from the "waiting table" (items_mod)
            if ($form->getData('status_version')==1) {
                jMessage::add(jLocale::get('boosteradmin~admin.version_validated'));
            }
            //validator submit via the "Validate" button so we automaticaly validate the version
            elseif($form->getData('_validate')){
                $form->setData('status_version', 1);
                jMessage::add(jLocale::get('boosteradmin~admin.version_validated'));
            }
            // we just edit the new content of the version
            // but we didnt validate it so :
            // save all the modification
            else {
                jMessage::add(jLocale::get('boosteradmin~admin.version_saved_but_not_validated_yet'));
            }
            $form->saveToDao('boosteradmin~boo_versions');
            //update the date_version
            $daoItem = jDao::get('booster~boo_items');
            $rec = $daoItem->get($form->getData('item_id'));
            $rec->date_version = date("Y-m-d H:i:s");
            $daoItem->update($rec);

        }
        else {
            jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'boosteradmin~versions:index';
        return $rep;
    }
    /**
     * Edit the Modified Version for modetation
     */
    function editmod() {
        $form = jForms::create('boosteradmin~versions_mod',$this->intParam('id'));
        $form->initFromDao('boosteradmin~boo_versions_mod');
        $form->setData('id',$this->intParam('id'));
        $tpl = new jTpl();
        $rep = $this->getResponse('html');

        $item_by = 'undefined';
        $item = jDao::get('booster~boo_items','booster')->get($this->intParam('id'));
        if ($item !== false)
            $item_by = jDao::get('jcommunity~user','hfnu')->getById($item->item_by)->nickname;

        $tpl->assign('item_by',$item_by);

        $tpl->assign('title',jLocale::get('boosteradmin~admin.version.validation.or.modification'));
        $tpl->assign('form',$form);
        $tpl->assign('id',$this->intParam('id'));
        $tpl->assign('action','boosteradmin~versions:savemod');
        $rep->body->assign('MAIN',$tpl->fetch('edit'));
        return $rep;
    }
    /**
     * Save the Modified Versions
     */
    function savemod() {
        $form = jForms::fill('boosteradmin~versions_mod',$this->intParam('id'));
        if ($form->check()) {
            // we validate the modifications, so replace the old data
            // then remove the data from the "waiting table" (items_mod)
            if ($form->getData('status_version')==1 OR $form->getData('_validate')) {
                //in case, direct click on validate
                $form->setData('status_version', 1);

                $form->saveToDao('boosteradmin~boo_versions');
                //delete the moderated item from the "mirror" table
                jDao::get('boosteradmin~boo_versions_mod','booster')->delete($form->getData('id'));
                //msg to the admin ;)
                jMessage::add(jLocale::get('boosteradmin~admin.version_validated'));
            }
            // we just edit the modified content of the version
            // but we didnt validate it so :
            // save all the modification
            else {
                jMessage::add(jLocale::get('boosteradmin~admin.version_saved_but_not_validated_yet'));
                $form->saveToDao('boosteradmin~boo_versions_mod');
            }
        }
        else {
            jMessage::add(jLocale::get('boosteradmin~admin.invalid.data'));
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'boosteradmin~versions:index';
        return $rep;
    }
}
