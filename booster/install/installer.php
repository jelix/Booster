<?php
/**
* @package   booster
* @author    Olivier Demah
* @copyright 2011 FoxMaSk
* @link      http://www.foxmask.info
* @license   http://gnu.org All rights reserved
*/

require_once (dirname(__FILE__).'/../application.init.php');

jApp::setEnv('install');

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();
