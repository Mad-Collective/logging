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
	@docker rmi ${COMPONENT}-${CONTAINER}
endif

enter:
	@docker exec -ti ${COMPONENT}-${CONTAINER}-1 /bin/sh

test: unit integration

deps:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml exec -T ${CONTAINER} \
	 composer install --no-interaction

unit:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml exec -T ${CONTAINER} \
	 ${APP_ROOT}/ops/scripts/unit.sh ${PHP_VERSION}

integration:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml exec -T ${CONTAINER} \
	 ${APP_ROOT}/ops/scripts/integration.sh ${PHP_VERSION}

ps: status
status:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml ps

logs:
	@docker-compose -p ${COMPONENT} -f ops/docker/docker-compose.yaml logs

tag: # List last tag for this repo
	@git tag -l | sort -r |head -1

restart: nodev dev
