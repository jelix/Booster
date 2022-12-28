<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @contributor Florian Lonqueu-Brochard, Laurent Jouanneau
* @copyright 2011 olivier demah, 2022 Laurent Jouanneau
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
        'cloud' => array('auth.required'=>false),
        'applis' => array('auth.required'=>false),
        'modules' => array('auth.required'=>false),
        'plugins' => array('auth.required'=>false),
        'packlang' => array('auth.required'=>false),
        'credits' => array('auth.required'=>false),
        'recommendation' => array( 'jacl2.right'=>'booster.recommendation')
    );

    protected static $per_page = 10;

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

        if ($this->param('search')) {
            $form = jForms::fill('booster~search');
            if ($form->check()) {
                $results = jClasses::getService('booster~booster')->search($form);
                $tpl->assign('search_results', $results);
                $rep->body->assign('is_search', true);
            }
        }
        $rep->body->assign('MAIN', $tpl->fetch('index'));
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

        $data = jDao::get('booster~boo_items','booster')->get($this->intParam('id', 0));
        // is the current user the author or the admin ?
        if ($this->userIsAdminOrAuthor($data->item_by)) {
            //so let's warn him if the item is moderated or not
            $tpl->assign('item_not_moderated',!$data->status);
        //the status is "not moderated" we don't display the item => 404
        } elseif (!$data || $data->status == 0) {
            throw new jHttp404NotFoundException();
        }

        $rep = $this->getResponse('html');
        $tpl->assign('data',$data);
        $tpl->assign('is_admin', jAcl2::check('booster.admin.index'));
        $tpl->assign('show_all_versions', true);
        $rep->title = $data->name;
        $rep->body->assign('MAIN',$tpl->fetch('view_item'));
        return $rep;
    }

    /**
     * Add an Item
     */
    function add() {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('booster~main.add.an.item');
        $form = jForms::get('booster~items');
        if ($form === null)
            $form = jForms::create('booster~items');
        $form->setData('item_by',jAuth::getUserSession()->id);
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('add_item'));
        return $rep;
    }


    /**
     * Save an Item
     */
    function saveItem()
    {
        $form = jForms::fill('booster~items');
        $saved = false;
        if ($form && $form->check()) {
            if ($form->getData('short_desc_fr') == ''  and
                $form->getData('short_desc') == ''
            ) {
                $form->setErrorOn('short_desc',jLocale::get('booster~main.desc.mandatory'));
                $form->setErrorOn('short_desc_fr',jLocale::get('booster~main.desc.mandatory'));
                return $this->redirect('booster~default:add');
            }

            $data = jClasses::getService('booster~booster')->saveItem();
            if (!empty($data)) {
                if(!jClasses::getService("booster~booster")->saveImage($data['id'], $form)){
                    jMessage::add(jLocale::get('booster~main.item.saved.failed'));
                }
                else{
                    jMessage::add(jLocale::get('booster~main.item.saved'));
                    $saved = true;
                    jEvent::notify('new_item_added', array('item_id' => $data['id']));
                    jForms::destroy('booster~items');
                }
            }
            else {
                jMessage::add(jLocale::get('booster~main.item.saved.failed'));
            }
        }

        return $this->redirect(
            ($saved) ? 'booster~default:addVersion' : 'booster~default:add',
            array('id'=>$data['id'], 'name'=>$data['name'])
        );
    }

    /**
     * Add a Version to the current Item
     */
    function addVersion() {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('booster~main.add.a.version');

        $form = jForms::get('booster~version');
        if ($form === null) {
            $form = jForms::create('booster~version');
        }
        $id = $this->intParam('id', 0);
        $form->setData('item_by',jAuth::getUserSession()->id);
        $form->setData('item_id', $id);

        $tpl = new jTpl();
        $tpl->assign('itemName',$this->param('name'));
        $tpl->assign('itemId', $id);
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('add_version'));
        return $rep;
    }

    /**
     * @param jFormsBase $form
     * @return boolean
     */
    protected function checkFilename($form)
    {
        // let's clean the filename
        // remove slashes
        $fileName = stripslashes($form->getData('filename'));
        if ($fileName) {
            // remove special chars
            // see http://php.net/filter_var
            // http://www.phpro.org/tutorials/Filtering-Data-with-PHP.html
            $fileName = filter_var($fileName, FILTER_SANITIZE_SPECIAL_CHARS ,
                array('flags' => FILTER_FLAG_STRIP_HIGH|FILTER_FLAG_STRIP_LOW)
            );
            // a filename don't have to have a slash in its name
            if ( strpos($fileName,'/') > 0  or strpos($fileName,'\\') > 0 ) {
                $form->setErrorOn('filename', jLocale::get('booster~main.invalid.filename'));
                return false;
            }
            else {
                $form->setData('filename',$fileName);
            }
        }
        return true;
    }

    /**
     * Save a Version
     */
    function saveVersion()
    {
        $form = jForms::fill('booster~version');
        if ($form->check()) {
            if ($this->checkFilename($form)) {
                $data = jClasses::getService('booster~booster')->saveVersion($form);
                if ($data) {
                    jMessage::add(jLocale::get('booster~main.version.saved'));
                    jEvent::notify('new_version_added', array('version_id' => $data));
                    jForms::destroy('booster~version');
                    $item = jDao::get('booster~boo_items', 'booster')->get($form->getData('item_id'));
                    return $this->redirect(
                        'booster~default:viewItem',
                        array('id' => $item->id, 'name' => $item->name)
                    );
                }
                jMessage::add(jLocale::get('booster~main.version.saved.failed'));
            }
        } else {
            jMessage::add(jLocale::get('booster~main.version.check.failed'));
        }

        return $this->redirect(
            'booster~default:addVersion',
            array(
                'id'=>$this->param('itemId'),
                'name'=>$this->param('itemName')
            )
        );
    }


    /**
     * EditItem
     */
    function editItem() {
        $id = $this->intParam('id');
        $data = jDao::get('booster~boo_items','booster')->get($id);

        if (!$this->userCanEditOrIsAuthor($data->item_by, 'item')) {
            throw new jHttp403ForbiddenException();
        }
        //if this item is not moderated
        //we'll just display a page with the item + a message to inform the user
        if ( jClasses::getService('booster~booster')->isModerated($id,'items') === false ) {
            $rep = $this->getResponse('html');
            $tpl = new jTpl();

            if(jAuth::isConnected()) {
                $tpl->assign('current_user',jAuth::getUserSession ()->id);
            }
            else {
                $tpl->assign('current_user','');
            }

            $tpl->assign('data',$data);
            $tpl->assign('item_not_moderated',1);
            $tpl->assign('show_all_versions', true);
            $rep->body->assign('MAIN',$tpl->fetch('view_item'));
            //$rep->body->assign('MENU',$tpl->fetch('menu'));
            return $rep;
        }

        $tags = implode(',', jClasses::getService("jtags~tags")->getTagsBySubject('booscope', $data->id) ) ;

        $form = jForms::get('booster~items',$data->id);
        if(!$form)
            $form = jForms::create('booster~items',$data->id);
        $form->initFromDao('booster~boo_items',null, 'booster');
        $form->setData('tags',$tags);
        $form->initModifiedControlsList();
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('id',$data->id);
        $tpl->assign('form',$form);
        $tpl->assign('item_not_moderated',0);
        $tpl->assign('action','booster~saveEditItem');
        $rep->body->assign('MAIN',$tpl->fetch('edit_item'));
        return $rep;
    }


    /**
     * Save the Edited Item
     */
    function saveEditItem() {
        $id = $this->intParam('id');

        $form = jForms::fill('booster~items',$id);
        $saved = false;

        if ($form->check()) {
            if (!$this->userCanEditOrIsAuthor($form->getData('item_by'), 'item')) {
                throw new jHttp403ForbiddenException();
            }

            if ($form->getData('short_desc_fr') == ''  and
                $form->getData('short_desc') == '' ) {
                $form->setErrorOn('short_desc',jLocale::get('booster~main.desc.mandatory'));
                $form->setErrorOn('short_desc_fr',jLocale::get('booster~main.desc.mandatory'));
            }
            else if (jClasses::getService('booster~booster')->saveEditItem($form)) {
                if ($form->getData('image') != '' &&
                    jClasses::getService("booster~booster")->saveImage($id, $form)
                ) {
                    jMessage::add(jLocale::get('booster~main.item.edit.success'));
                    jEvent::notify('item_edited', array('item_id' => $id));
                    $saved = true;
                    jForms::destroy('booster~items',$id);
                }
                else {
                    jMessage::add(jLocale::get('booster~main.item.editimage.failed'));
                }
            }
            else {
                jMessage::add(jLocale::get('booster~main.item.edit.failed'));
            }
        }

        $name = jDao::get('boo_items')->get($id)->name;

        return $this->redirect(
            $saved ? 'booster~default:viewItem' : 'booster~default:editItem',
            array('id'=>$id, 'name'=>$name)
        );
    }


    /**
     * EditVersion
     */
    function editVersion() {
        $id = $this->intParam('id');
        $data = jDao::get('booster~boo_versions','booster')->get($id);
        $user_id = jDao::get('booster~boo_items','booster')->get($data->item_id)->item_by;

        if (!$this->userCanEditOrIsAuthor($user_id, 'version')) {
            throw new jHttp403ForbiddenException();
        }
        //if this item is not moderated
        //we'll just display a page with the item + a message to inform the user
        if ( jClasses::getService('booster~booster')->isModerated($id,'versions') === false ) {
            $rep = $this->getResponse('html');
            $tpl = new jTpl();

            if(jAuth::isConnected()) {
                $tpl->assign('current_user',jAuth::getUserSession ()->id);
            }
            else {
                $tpl->assign('current_user','');
            }
            $data = jDao::get('booster~boo_items','booster')->get($data->item_id);
            $tpl->assign('data',$data);
            $tpl->assign('item_not_moderated',1);
            $tpl->assign('show_all_versions', true);
            $rep->body->assign('MAIN',$tpl->fetch('view_item'));
            return $rep;
        }

        $form = jForms::get('booster~version',$data->id);
        // if not
        if ($form === null) {
        // ... create it
            $form = jForms::create('booster~version',$data->id);
            $form->initFromDao('booster~boo_versions');
        }
        $form->initModifiedControlsList();
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('id',$data->id);
        $tpl->assign('form',$form);
        $tpl->assign('action','booster~saveEditVersion');
        $rep->body->assign('MAIN',$tpl->fetch('edit_version'));
        return $rep;

    }
    /**
     * Save the Edited Version
     */
    function saveEditVersion()
    {
        $id = $this->intParam('id');
        $form = jForms::fill('booster~version',$id);
        if ($form->check()) {

            $user_id = jDao::get('booster~boo_items','booster')->get($form->getData('item_id'))->item_by;
            if (!$this->userCanEditOrIsAuthor($user_id, 'version')) {
                throw new jHttp403ForbiddenException();
            }

            if ($this->checkFilename($form)) {
                if (jClasses::getService('booster~booster')->saveEditVersion($form)) {
                    jMessage::add(jLocale::get('booster~main.version.edit.success'));
                    jEvent::notify('version_edited', array('version_id' => $id));
                    jForms::destroy('booster~version');
                    $name = jDao::get('boo_items')->get($form->getData('item_id'))->name;
                    return $this->redirect(
                        'booster~default:viewItem',
                        array('id' => $form->getData('item_id'),'name'=>$name)
                    );
                }
                else {
                    jMessage::add(jLocale::get('booster~main.version.edit.failed'));
                }
            }
        }

        $name = jDao::get('boo_versions')->get($id)->version_name ;
        return $this->redirect(
            'booster~default:editVersion',
            array('id'=>$id, 'name'=>$name)
        );
    }
    /**
     * Cloud
     */
    function cloud () {
        $rep = $this->getResponse('html');

        $tag = $this->param('tag');
        $tag = str_replace(' ', '-', $tag);

        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getSubjectsByTags($tag, "booscope");

        $items = array();
        //get factory of the moderated item
        $dao = jDao::get('boo_items','booster');
        // get factory of the item which are waiting for moderation
        $daoMod = jDao::get('boosteradmin~boo_items_modifs','booster');
        foreach ($tags as $subj_id) {
            //get record not yet validated
            $rec = $daoMod->get($subj_id);
            //no record found so we can get the still moderated one
            if ( $rec === false )
                $rec = $dao->get($subj_id);
            //status ok ?
            if ($rec->status == 1)
                $items[] = $rec;
        }

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
        return $rep;
    }

    protected function displayList ($typeName, $typeId)
    {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('booster~main.'.$typeName.'.list');
        $datas = jDao::get('booster~boo_items','booster')->findByTypeIdPaginate($typeId, $this->param('offset'), self::$per_page);
        $tpl = new jTpl();
        if(jAuth::isConnected()) {
            $tpl->assign('current_user',jAuth::getUserSession ()->id);
        }
        else {
            $tpl->assign('current_user','');
        }
        $tpl->assign('datas', $datas);
        $tpl->assign('item_not_moderated','');
        $this->setPagination($tpl, 'booster~default:'.$typeName, $typeId, $this->param('offset'));
        $rep->body->assign('MAIN',$tpl->fetch('list'));
        return $rep;
    }

    /**
     * Display the list of applications
     */
    function applis () {
        return $this->displayList(
            'applis',
            1
        );
    }

    /**
     * Display the list of modules
     */
    function modules ()
    {
        return $this->displayList(
            'modules',
            2
        );
    }

    /**
     * Display the list of plugins
     */
    function plugins ()
    {
        return $this->displayList(
            'plugins',
            3
        );
    }

    /**
     * Display the list of packlang
     */
    function packlang ()
    {
        return $this->displayList(
            'packlang',
            4
        );
    }

    /**
     * Display the resources of the current user
     */
    function yourressources () {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('booster~main.your.ressources');
        $datas = jDao::get('booster~boo_items','booster')->findAllReportedBy(jAuth::getUserSession ()->id);
        $tpl = new jTpl();
        if(jAuth::isConnected()) {
            $tpl->assign('current_user',jAuth::getUserSession ()->id);
        }
        else {
            $tpl->assign('current_user','');
        }
        $tpl->assign('datas', $datas);
        $rep->body->assign('MAIN',$tpl->fetch('your_ressources'));
        return $rep;
    }

    public function validNewItem(){

        // is the current user is an admin ?
        if (jAuth::isConnected() and jAcl2::check('booster.admin.index') ) {

            $id = $this->param('id');
            jDao::get('booster~boo_items','booster')->setToValidated($id);

        }
        else {
            throw new jHttp403ForbiddenException();
        }

        return $this->redirect(
            'booster~default:viewItem',
            array('id' => $id, 'name' => $this->param('name'))
        );
    }

    function credits() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl;
        $rep->body->assign('MAIN',$tpl->fetch('credits'));
        return $rep;
    }


    public function recommendation()
    {
        $id = $this->intParam('id', 0);
        $name = $this->param('name');
        $state = $this->intParam('state');

        if(!$id || $name == '' || $state === null){
            return $this->redirect("booster~default:index");
        }

        jDao::get('booster~boo_items')->setRecommendation($id, ($state === 1));

        return $this->redirect("booster~default:viewItem", array('id' => $id, 'name' => $name));
    }

    protected function userIsAdminOrAuthor($user_id){
        if(!jAuth::isConnected())
            return false;

        return ($user_id == jAuth::getUserSession()->id) OR jAcl2::check('booster.admin.index');
    }

    protected function userCanEditOrIsAuthor($user_id, $type = 'item'){
        if(!jAuth::isConnected())
            return false;

        return ($user_id == jAuth::getUserSession()->id) OR jAcl2::check('booster.edit.' . $type);
    }

    protected function setPagination($tpl, $action, $type_id, $param_offset){
        $tpl->assign('count', jDao::get('booster~boo_items','booster')->countByTypeId($type_id));
        $tpl->assign('action', $action);
        $tpl->assign('index_result', $param_offset);
        $tpl->assign('per_page', self::$per_page);
        $tpl->assign('param_name', 'offset');
    }

}
