
.PHONY: dist

up:
	@docker-compose up -d

vendor:
	@docker-compose run --rm vtiger bash -c "cd modules/Composer && rm -fr vendor composer.lock"
	@docker-compose run --rm vtiger bash -c "cd modules/Composer && composer install"

dist:
	@zip -qq -r dist/Composer.zip manifest.xml languages

import: dist up
	@docker-compose exec vtiger php -f /var/www/html/vtlib/tools/console.php -- --import=/app/dist/Composer.zip
	@docker-compose exec vtiger cat /var/www/html/composer.json

update: dist up
	@docker-compose exec vtiger php -f /var/www/html/vtlib/tools/console.php -- --update=/app/dist/Composer.zip
	@docker-compose exec vtiger cat /var/www/html/composer.json
