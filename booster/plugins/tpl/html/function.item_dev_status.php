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
 * displays the label of an item dev status
 *
 * @param jTpl $tpl template engine
 * @param string $statusId id of the dev status
 *  */
function jtpl_function_html_item_dev_status($tpl, $statusId)
{
    if ($statusId == 1) {
        echo htmlspecialchars(jLocale::get("booster~main.dev_status.unmaintained"));
    }
    else if ($statusId == 2) {
        echo htmlspecialchars(jLocale::get("booster~main.dev_status.gone"));
    }
    else {
        echo htmlspecialchars(jLocale::get("booster~main.dev_status.maintained"));
    }
}
