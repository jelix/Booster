<?php
/**
* @package   booster
* @subpackage booster
* @author    Olivier Demah
* @copyright 2011 Olivier Demah
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class authorZone extends jZone {

    protected function _createContent()
    {
        $user = jDao::get('jcommunity~user', 'hfnu')->getById((int) $this->param('id'));
        if ($user) {
            return '<li>
                    <img src="'.jApp::urlBasePath().'themes/'.jApp::config()->theme.'icons/user_gray.png" alt=""/>
                    '.htmlspecialchars(jLocale::get('booster~main.item_by')).' '.
                    htmlspecialchars($user->nickname). '
                </li>';
        }
        return '';
    }
}
