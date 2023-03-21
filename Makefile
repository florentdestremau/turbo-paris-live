install:
	composer install
	npm install
	npm run dev
	docker-compose up -d
	symfony console doctrine:fixtures:load -n
	symfony serve -d

start:
	docker-compose up -d
	symfony serve -d

stop:
	docker-compose stop
	symfony server:stop
