parse:
	docker-compose run --rm php-cli php parse.php
composer-install:
	docker-compose run --rm php-cli composer install