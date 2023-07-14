COMPONENT := logging
CONTAINER := phpcli
IMAGES ?= false
APP_ROOT := /app/logging

all: dev nodev

dev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml up -d --build

nodev:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml rm -f > /dev/null
ifeq ($(IMAGES),true)
	@docker rmi ${COMPONENT}_${CONTAINER}
endif

enter:
	@docker exec -ti ${COMPONENT}_${CONTAINER}_1 /bin/sh

test: unit integration

deps:
	@composer install --no-interaction

unit:
	@docker exec -t ${COMPONENT}_${CONTAINER}_1 ${APP_ROOT}/ops/scripts/unit.sh ${PHP_VERSION}

integration:
	@docker exec -t ${COMPONENT}_${CONTAINER}_1 ${APP_ROOT}/ops/scripts/integration.sh ${PHP_VERSION}

ps: status
status:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml ps

logs:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml logs

tag: # List last tag for this repo
	@git tag -l | sort -r |head -1

restart: nodev dev
