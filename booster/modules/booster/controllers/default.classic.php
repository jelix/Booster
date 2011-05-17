<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @contributor Florian Lonqueu-Brochard
* @copyright 2011 olivier demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Main controler to handle add/edit of the public actions of the Booster
 */
class defaultCtrl extends jController {
    public $pluginParams = array(
        '*'     => array('auth.required'=>true),
        'index' => array('auth.required'=>false),
        'viewItem' => array('auth.required'=>false),
        'search' => array('auth.required'=>false),
        'cloud' => array('auth.required'=>false)
    );
    /**
     *Main Page
     */
    function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        if(jAuth::isConnected()) {
            $tpl->assign('current_user',jAuth::getUserSession ()->id);
        }
        else {
            $tpl->assign('current_user','');
        }

        if( $this->param('search')) {
            $form = jForms::fill('booster~search');
            if ($form->check()) {
                $results = jClasses::getService('booster~booster')->search();
                $tpl->assign('search_results', $results);
            }
        }
        else{
            $dao = jDao::get('booster~boo_items');
            $tpl->assign('datas',$dao->findLastCreated($GLOBALS['gJConfig']->booster['last_items_created']));
        }

        $rep->body->assign('MAIN',$tpl->fetch('index'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }
    /**
     * View Item page
     */
    function viewItem() {

        $tpl = new jTpl();
        if(jAuth::isConnected()) {
            $tpl->assign('current_user',jAuth::getUserSession ()->id);
        }
        else {
            $tpl->assign('current_user','');
        }

        $data = jDao::get('booster~boo_items')->get( $this->param('id') );

        if ($data->status == 0) {
            $rep = $this->getResponse('html',true);
            $rep->bodyTpl = 'jelix~404.html';
            $rep->setHttpStatus('404', 'Not Found');
            return $rep;
        }

        $rep = $this->getResponse('html');
        $rep->addJSLink($GLOBALS['gJConfig']->urlengine['basePath'].'jelix/jquery/jquery.js');
        $tpl->assign('data',$data);
        $rep->body->assign('MAIN',$tpl->fetch('view_item'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }
    /**
     * Add an Item
     */
    function add() {
        $rep = $this->getResponse('html');
        $rep->title .= jLocale::get('booster~main.add.an.item');
        $form = jForms::create('booster~items');
        $form->setData('item_by',jAuth::getUserSession()->id);
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('add_item'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }
    /**
     * Save an Item
     */
    function saveItem() {
        $rep = $this->getResponse('redirect');
        $form = jForms::fill('booster~items');
        if ($form->check()) {
            if ($data = jClasses::getService('booster~booster')->saveItem()) {
                jMessage::add(jLocale::get('booster~main.item.saved'));
                $saved = true;
            }
            else {
                $saved = false;
                jMessage::add(jLocale::get('booster~main.item.saved.failed'));
            }
        } else {
            $saved = false;
            jMessage::add(jLocale::get('booster~main.item.check.failed'));
        }
        $rep->params = array('id'=>$data['id'],'name'=>$data['name']);
        $rep->action = ($saved) ? 'booster~addVersion' : 'booster~index';
        return $rep;
    }
    /**
     * Add a Version to the current Item
     */
    function addVersion() {
        $rep = $this->getResponse('html');
        $rep->title .= jLocale::get('booster~main.add.a.version');
        $form = jForms::create('booster~version');
        $form->setData('item_by',jAuth::getUserSession()->id);
        $form->setData('item_id',$this->intParam('id'));

        $tpl = new jTpl();
        $tpl->assign('itemName',$this->param('name'));
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('add_version'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }
    /**
     * Save a Version
     */
    function saveVersion() {
        $rep = $this->getResponse('redirect');
        $form = jForms::fill('booster~version');
        if ($form->check()) {
            if (jClasses::getService('booster~booster')->saveVersion($form)) {
                jMessage::add(jLocale::get('booster~main.version.saved'));
                $saved = true;
                $item = jDao::get('booster~boo_items')->get($form->getData('item_id'));
                if ($item->status = 1) {
                    $rep->action = 'viewItem';
                    $rep->params = array('id'=> $item->id,'name'=>$item->name);
                }
                else {
                    $rep->action = 'index';
                }
            }
            else {
                $saved = false;
                jMessage::add(jLocale::get('booster~main.version.saved.failed'));
                $rep->action = 'index';
            }
        } else {
            $saved = false;
            jMessage::add(jLocale::get('booster~main.version.check.failed'));
            $rep->action = 'index';
        }
        return $rep;
    }
    /**
     * EditItem
     */
    function editItem() {
        $id = $this->intParam('id');
        $data = jDao::get('booster~boo_items')->get($id);

        if ($data->user_id != jAuth::getUserSession()->id  or
            ! jAcl2::check('booster.edit.item')) {
            $rep = $this->getResponse('html');
            $rep->bodyTpl = 'jelix~403.html';
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $form = jForms::create('booster~items',$data->id);
        $form->initFromDao('booster~boo_items');

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title',jLocale::get('booster~main.item.edit'));
        $tpl->assign('id',$data->id);
        $tpl->assign('form',$form);
        $tpl->assign('action','booster~saveEditItem');
        $rep->body->assign('MAIN',$tpl->fetch('edit'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }
    /**
     * Save the Edited Item
     */
    function saveEditItem() {
        $id = $this->intParam('id');

        $form = jForms::fill('booster~items',$id);

        if ($form->check()) {
            if ($form->getData('item_by') != jAuth::getUserSession()->id  or
                ! jAcl2::check('booster.edit.item')) {
                $rep = $this->getResponse('html');
                $rep->bodyTpl = 'jelix~403.html';
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            else {
                if (jClasses::getService('booster~booster')->saveEditItem($form)) {
                    jMessage::add(jLocale::get('booster~main.item.edit.success'));
                }
                else {
                    jMessage::add(jLocale::get('booster~main.item.edit.failed'));
                }
            }
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'booster~index';
        return $rep;
    }
    /**
     * EditItem
     */
    function editVersion() {
        $id = $this->intParam('id');
        $data = jDao::get('booster~boo_versions')->get($id);
        $user_id = jDao::get('booster~boo_items')->get($data->item_id)->item_by;

        if ($user_id != jAuth::getUserSession()->id  or
            ! jAcl2::check('booster.edit.version')) {
            $rep = $this->getResponse('html');
            $rep->bodyTpl = 'jelix~403.html';
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $form = jForms::create('booster~version',$data->id);
        $form->initFromDao('booster~boo_versions');
        $form->setData('item_by',$user_id);

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title',jLocale::get('booster~main.version.edit'));
        $tpl->assign('id',$data->id);
        $tpl->assign('form',$form);
        $tpl->assign('action','booster~saveEditVersion');
        $rep->body->assign('MAIN',$tpl->fetch('edit'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;

    }
    /**
     * Save the Edited Version
     */
    function saveEditVersion() {
        $id = $this->intParam('id');
        $form = jForms::fill('booster~version',$id);

        if ($form->check()) {
            if ($form->getData('item_by') != jAuth::getUserSession()->id  or
                ! jAcl2::check('booster.edit.version')) {
                $rep = $this->getResponse('html');
                $rep->bodyTpl = 'jelix~403.html';
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            else {
                if (jClasses::getService('booster~booster')->saveEditVersion($form)) {
                    jMessage::add(jLocale::get('booster~main.version.edit.success'));
                }
                else {
                    jMessage::add(jLocale::get('booster~main.version.edit.failed'));
                }
            }
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'booster~index';
        return $rep;
    }

    /**
     * Cloud
     */
    function cloud () {
        $rep = $this->getResponse('html');

        $tag = $this->param('tag');

        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getSubjectsByTags($tag, "booscope");

        $items = array();
        $dao = jDao::get('boo_items');
        foreach ($tags as $subj_id)
            $items[] = $dao->get($subj_id);

        $tpl = new jTpl();
        if(jAuth::isConnected()) {
            $tpl->assign('current_user',jAuth::getUserSession ()->id);
        }
        else {
            $tpl->assign('current_user','');
        }
        $tpl->assign('items',$items);
        $tpl->assign('tag',$tag);
        $rep->body->assign('MAIN', $tpl->fetch('tag'));
        $rep->body->assign('MENU',$tpl->fetch('menu'));
        return $rep;
    }

}
