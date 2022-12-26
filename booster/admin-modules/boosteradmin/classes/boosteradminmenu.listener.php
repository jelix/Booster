<?php
/**
* @package   booster
* @subpackage boosteradmin
* @author    Olivier Demah, Laurent Jouanneau
* @copyright 2011 olivier demah, 2022 Laurent Jouanneau
* @link      http://www.jelix.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/

class boosteradminmenuListener extends jEventListener{

    function onmasteradminGetMenuContent ($event) {
        $chemin = jApp::config()->urlengine['basePath'] . 'themes/' . jApp::config()->theme .'/';
        if ( jAcl2::check('booster.admin.index')) {
            $event->add(new masterAdminMenuItem('booster','Booster', '', 20));

            $item = new masterAdminMenuItem('items',
                        jLocale::get('boosteradmin~admin.items.validated'),
                        jUrl::get('boosteradmin~items:indexAll'),
                        301,
                        'booster');
            $item->icon = $chemin . 'icons/item.png';
            $event->add($item);

            $item = new masterAdminMenuItem('itemsnew',
                        jLocale::get('boosteradmin~admin.items.not.validated'),
                        jUrl::get('boosteradmin~items:index'),
                        302,
                        'booster');
            $item->icon = $chemin . 'icons/item_mod.png';
            $event->add($item);


            $item = new masterAdminMenuItem('versions',
                        jLocale::get('boosteradmin~admin.versions.validated'),
                        jUrl::get('boosteradmin~versions:indexAll'),
                        303,
                        'booster');
            $item->icon = $chemin . 'icons/version.png';
            $event->add($item);

            $item = new masterAdminMenuItem('versionsnew',
                        jLocale::get('boosteradmin~admin.versions.not.validated'),
                        jUrl::get('boosteradmin~versions:index'),
                        304,
                        'booster');
            $item->icon = $chemin . 'icons/version_mod.png';
            $event->add($item);

            $item = new masterAdminMenuItem('jelixversions',
                        jLocale::get('boosteradmin~admin.versions.jelix.label'),
                        jUrl::get('boosteradmin~jelixversions:index'),
                        305,
                        'booster');
            $event->add($item);

        }
    }

    function onmasterAdminGetDashboardWidget ($event) {
        if ( jAcl2::check('booster.admin.index')) {
            $box = new masterAdminDashboardWidget();
            $box->title = jLocale::get('boosteradmin~admin.task.todo');
            $box->content = jZone::get('boosteradmin~tasktodo');
            $event->add($box);
        }
    }

    function onmasteradminGetInfoBoxContent ($event) {
        if ( jAcl2::check('booster.admin.index')) {
            $event->add(new masterAdminMenuItem('portal',
                jLocale::get('boosteradmin~admin.back.to.website'),
                jUrl::get('booster~default:index'),
                100,
                'booster'));
        }
    }
}
