#!/bin/bash

source /etc/profile.d/php_vars.sh
php booster/install/configurator.php -v
php booster/install/installer.php -v
sudo rm -rf /var/cache/sites/booster.jelix.org/www/
