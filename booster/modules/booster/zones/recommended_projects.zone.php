<?php
/**
* @package   booster
* @subpackage booster
* @author    Laurent Jouanneau
* @copyright 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license    All rights reserved
*/

class recommended_projectsZone extends jZone
{
    protected $_tplname='zone.recommended_projects';

    protected function _prepareTpl()
    {
        $dao = jDao::get('boo_items');
        $this->_tpl->assign('results', $dao->findRecommended());
    }
}
