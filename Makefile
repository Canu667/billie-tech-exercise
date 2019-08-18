install:
	cd ./liker && cp env.dist .env && cp behat.yml.dist behat.yml && composer install

	cd ./mailer && cp env.dist .env &&composer install

	docker-compose up -d
	docker exec -it liker-app ./wait-for-it.sh db:3306
	docker exec -it liker-app ./bin/console do:mi:mi --quiet

runTests:
	cd ./liker && ./vendor/bin/behat && php bin/phpunit
	cd ./mailer && ./bin/phpunit
