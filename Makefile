UID := $(shell id -u)
GID := $(shell id -g)
CONTAINER := "affiliates-app"
USER := www-data

build:
	$(info Make: Building environment image.)
	UID=$(UID) GID=$(GID) docker-compose build --no-cache
	@make -s clean
 
up:
	$(info Make: Starting environment containers.)
	UID=$(UID) GID=$(GID) docker-compose up -d

down:
	$(info Make: Stopping environment containers.)
	@docker-compose stop
 
restart:
	$(info Make: Restarting environment containers.)
	@make -s up
	@make -s down

init:
	$(info Make: Initializing environment.)
	@docker exec -u $(USER) $(CONTAINER) touch database/database.sqlite
	@docker exec -u $(USER) $(CONTAINER) cp .env.example .env
	@docker exec -u $(USER) $(CONTAINER) composer install --ansi
	@docker exec -u $(USER) $(CONTAINER) php artisan key:generate
	@docker exec -u $(USER) $(CONTAINER) php artisan migrate:refresh --seed --force

docs:
	$(info Make: Generate application documentation.)
	@docker exec -u $(USER) $(CONTAINER) ./vendor/bin/doctum.php update --force doctum.config.php

test:
	$(info Make: Starting environment tests.)
	@docker exec -u $(USER) $(CONTAINER) php artisan test --group affiliates

dusk:
	$(info Make: Starting environment dusk tests.)
	@docker exec -u $(USER) $(CONTAINER) php artisan dusk

testall:
	$(info Make: Starting environment full tests.)
	@docker exec -u $(USER) $(CONTAINER) php artisan test
	@docker exec -u $(USER) $(CONTAINER) php artisan dusk

shell:
	$(info Make: Starting environment shell.)
	@docker exec -u $(USER) -it $(CONTAINER) sh

clean:
	@docker system prune --volumes --force
