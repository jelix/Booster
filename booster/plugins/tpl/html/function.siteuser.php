<?php
/**
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author     Laurent Jouanneau
 * @copyright  2023 Laurent Jouanneau
 * @link       https://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * displays the user corresponding to the given id
 *
 * @param jTpl $tpl template engine
 * @param string $userId id of the user into the jcommunity table
 *  */
function jtpl_function_html_siteuser($tpl, $userId, $defaultName = 'Unknown')
{
    static $allUsers = array();

    if (isset($allUsers[$userId])) {
        $nickname = $allUsers[$userId];
    }
    else {

        $user = jDao::get('jcommunity~user', 'hfnu')->getById($userId);
        if ($user) {
            $allUsers[$userId] = $nickname = $user->nickname;
        }
        else {
            $allUsers[$userId] = $nickname = $defaultName;
        }
    }

    echo htmlspecialchars($nickname);
}
