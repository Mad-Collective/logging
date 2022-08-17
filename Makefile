COMPONENT := pluggitlogging
CONTAINER := php80
IMAGES ?= false
APP_ROOT := /app/logging

all: dev nodev

dev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml up -d

nodev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml rm -f > /dev/null
ifeq ($(IMAGES),true)
	@docker rmi ${COMPONENT}_${CONTAINER}
endif

test: unit integration

deps:
	@composer install --no-interaction

unit:
	@${APP_ROOT}/ops/scripts/unit.sh

integration:
	@${APP_ROOT}/ops/scripts/integration.sh

ps: status
status:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml ps

logs:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yml logs

tag: # List last tag for this repo
	@git tag -l | sort -r |head -1

restart: nodev dev
