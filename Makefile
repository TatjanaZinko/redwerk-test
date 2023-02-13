.PHONY: start stop restart build
-include .env

CURRENT_ID := $(shell id -u)
CURRENT_GROUP := $(shell id -g)

CURRENT_DIR=${shell pwd}
DATE=$$(date +%F_%H-%M-%S)
DB_DUMP_REAL_PATH=${CURRENT_DIR}/data/sql
DB_DUMP_PATH=/storage/dumps

BZIP2 := $(shell command -v bzip2 2> /dev/null)

DC := CURRENT_USER=${CURRENT_ID}:${CURRENT_GROUP} docker-compose
FPM := $(DC) exec phpfpm
MYSQL := $(DC) exec mysql
NGINX := $(DC) exec nginx

config-local:
	@$(eval POSTFIX = example)
	@cp ${CURRENT_DIR}/.env.${POSTFIX} ${CURRENT_DIR}/.env
	@echo "Config for this environment has been created"

start:
	@$(DC) up -d

stop:
	@$(DC) down
	@sudo rm -rf data/nginx-logs/*

restart: stop start

status:
	@docker-compose ps
	@docker ps -a

ssh-nginx:
	@${NGINX} sh

ssh-mysql:
	@${MYSQL} sh

ssh-fpm:
	@${FPM} sh

ssh-cli:
	@$(DC) run --rm cli bash

mysql-patch:
	@$(DC) run --rm cli bash -c 'wp search-replace https://www.ekransystem.com https://$(DOMAIN_CURRENT_SITE) --allow-root'

# mysql-change-string-example:
# 	@$(MYSQL) mysql --show-warnings -u $(MYSQL_USER) -p'$(MYSQL_PASSWORD)' $(MYSQL_DATABASE) -e "UPDATE underscore_db.wp_options SET option_value = 'https://${DOMAIN_CURRENT_SITE}' WHERE (option_id = '1');"
# 	@$(MYSQL) mysql --show-warnings -u $(MYSQL_USER) -p'$(MYSQL_PASSWORD)' $(MYSQL_DATABASE) -e "UPDATE underscore_db.wp_options SET option_value = 'https://${DOMAIN_CURRENT_SITE}' WHERE (option_id = '2');"

clean-all:
	@docker system prune -a --volumes
	@sudo rm -rf data/nginx-logs/*