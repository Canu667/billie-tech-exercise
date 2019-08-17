install:
    cd ./liker
    cp ./.env.dist ./.env
    composer install
    cd ..

    cd ./mailer
    cp ./.env.dist ./.env
    composer install
    cd ..

	docker-compose up -d
	docker exec -it liker-app ./wait-for-it.sh db:3306
	docker exec -it liker-app ./bin/console do:mi:mi --quiet
