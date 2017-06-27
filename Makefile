
ifndef JELIX_ORG_DB_NAME
    JELIX_ORG_DB_NAME=jelix_www
endif
ifndef JELIX_ORG_DB_HOST
    JELIX_ORG_DB_HOST=127.0.0.1
endif
ifndef JELIX_ORG_DB_LOGIN
    JELIX_ORG_DB_LOGIN=jelix
endif
ifndef JELIX_ORG_DB_PASSWD
    JELIX_ORG_DB_PASSWD=jelix
endif

ifndef BOOSTER_JELIX_ORG_DB_NAME
    BOOSTER_JELIX_ORG_DB_NAME=jelix_booster
endif
ifndef JELIX_ORG_DB_HOST
    BOOSTER_JELIX_ORG_DB_HOST=127.0.0.1
endif
ifndef JELIX_ORG_DB_LOGIN
    BOOSTER_JELIX_ORG_DB_LOGIN=jelix
endif
ifndef JELIX_ORG_DB_PASSWD
    BOOSTER_JELIX_ORG_DB_PASSWD=jelix
endif

ifndef BOOSTER_JELIX_ORG_DEPLOY_TARGET
    BOOSTER_JELIX_ORG_DEPLOY_TARGET=/tmp/booster.jelix.org
endif

booster/var/config/profiles.ini.php:
	cp booster/var/config/profiles.ini.php.dist booster/var/config/profiles.ini.php
	@sed -i "s!__JELIX_ORG_DB_NAME__!$(JELIX_ORG_DB_NAME)!" booster/var/config/profiles.ini.php
	@sed -i "s!__JELIX_ORG_DB_HOST__!$(JELIX_ORG_DB_HOST)!" booster/var/config/profiles.ini.php
	@sed -i "s!__JELIX_ORG_DB_LOGIN__!$(JELIX_ORG_DB_LOGIN)!" booster/var/config/profiles.ini.php
	@sed -i "s!__JELIX_ORG_DB_PASSWD__!$(JELIX_ORG_DB_PASSWD)!" booster/var/config/profiles.ini.php
	@sed -i "s!__BOOSTER_JELIX_ORG_DB_NAME__!$(BOOSTER_JELIX_ORG_DB_NAME)!" booster/var/config/profiles.ini.php
	@sed -i "s!__BOOSTER_JELIX_ORG_DB_HOST__!$(BOOSTER_JELIX_ORG_DB_HOST)!" booster/var/config/profiles.ini.php
	@sed -i "s!__BOOSTER_JELIX_ORG_DB_LOGIN__!$(BOOSTER_JELIX_ORG_DB_LOGIN)!" booster/var/config/profiles.ini.php
	@sed -i "s!__BOOSTER_JELIX_ORG_DB_PASSWD__!$(BOOSTER_JELIX_ORG_DB_PASSWD)!" booster/var/config/profiles.ini.php

.PHONY: build
build: clean booster/var/config/profiles.ini.php

.PHONY: clean
clean:
	rm -f booster/var/config/profiles.ini.php

.PHONY: deploy
deploy: build
	rsync -av --delete --ignore-times --checksum --include-from=.build-files ./ $(BOOSTER_JELIX_ORG_DEPLOY_TARGET)


