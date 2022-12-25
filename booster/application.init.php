<?php
/**
* @package   booster
* @subpackage 
* @author    laurentj
* @copyright 2011-2022 laurent
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

$appPath = dirname (__FILE__).'/';

require (__DIR__.'/vendor/autoload.php');

jApp::initPaths(
    $appPath,
    $appPath.'www/',
    $appPath.'var/',
    getenv('BOOSTER_JELIX_ORG_LOG_PATH'),
    $appPath.'var/config/',
    $appPath.'scripts/'
);
jApp::setTempBasePath(getenv('BOOSTER_JELIX_ORG_TEMP_PATH'));

require (__DIR__.'/vendor/jelix_app_path.php');
