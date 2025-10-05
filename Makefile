up:
	docker compose up -d
stop:
	docker compose stop
bash:
	docker compose exec -it backend bash
pint:
	docker compose exec -it backend vendor/bin/pint
init:
	docker compose up -d
	docker compose exec -it backend php -r "file_exists('.env') || copy('.env.example', '.env');"
	docker compose exec -it backend composer install
	docker compose exec -it backend php artisan key:generate
	docker compose exec -it backend php artisan migrate:fresh
	docker compose exec -it backend php artisan jwt:secret
larastan:
	docker compose exec -it backend vendor/bin/phpstan --memory-limit=1024M
test:
	docker compose exec -it backend php artisan test
