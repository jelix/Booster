<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @copyright 2011 Olivier Demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class tagsitemZone extends jZone {
    protected $_tplname='zone.tagsitem';

    protected function _prepareTpl(){
        $item_id = (int) $this->param('id');
        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getTagsBySubject('booscope',$item_id);
        $this->_tpl->assign('tags',$tags);
    }
}
