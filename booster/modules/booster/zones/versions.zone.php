<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @copyright 2011 Olivier Demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class versionsZone extends jZone {
    protected $_tplname='zone.versions';

    protected function _prepareTpl(){

        $item_id = (int) $this->param('id');
        if($this->param('show_all_versions')){
            $datas = jDao::get('booster~boo_versions','booster')->findAllValidated($item_id);
        }
        else{
            $datas = jDao::get('booster~boo_versions','booster')->findLastValidated($item_id);
        }
        $this->_tpl->assign('versions',$datas);
    }
}
