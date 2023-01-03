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
 * displays the label of an item status
 *
 * @param jTpl $tpl template engine
 * @param string $statusId id of the status
 *  */
function jtpl_function_html_item_status($tpl, $statusId)
{
    if ($statusId) {
        echo htmlspecialchars(jLocale::get("booster~main.status.validated"));
    }
    else {
        echo htmlspecialchars(jLocale::get("booster~main.status.not_validated"));
    }
}
