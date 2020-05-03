<?php
/**
* @package   booster
* @subpackage
* @author    laurentj
* @copyright 2011 laurent
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/


require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class adminLoginHtmlResponse extends jResponseHtml {

    function __construct() {
        parent::__construct();
        // Include your common CSS and JS files here
        $this->addCSSLink(jApp::config()->urlengine['jelixWWWPath'].'design/master_admin.css');
    }

    protected function doAfterActions() {
        $this->bodyTpl = 'master_admin~index_login';
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
       $this->title .= ($this->title !=''?' - ':'').'Administration';
       $this->body->assignIfNone('MAIN','');
    }
}
