SHELL = /bin/bash

.PHONY: setup

install:
	@echo "Installing dependencies..."
	composer install
	bower install

config:
	@echo "Creating configuration file..."
	php bin/phing

run:
	php -S localhost:8000 -t ./public

setup: install config
	@echo "Done!";
