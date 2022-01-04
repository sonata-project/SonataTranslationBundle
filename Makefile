# DO NOT EDIT THIS FILE!
#
# It's auto-generated by sonata-project/dev-kit package.

all:
	@echo "Please choose a task."
.PHONY: all

lint: lint-composer lint-yaml lint-xml lint-xliff lint-php
.PHONY: lint

lint-composer:
	composer-normalize --dry-run
	composer validate
.PHONY: lint-composer

lint-yaml:
	yamllint .

.PHONY: lint-yaml

lint-xml:
	find . -name '*.xml' \
		-not -path './vendor/*' \
		-not -path './src/Resources/public/vendor/*' \
		| while read xmlFile; \
	do \
		XMLLINT_INDENT='    ' xmllint --encode UTF-8 --format "$$xmlFile"|diff - "$$xmlFile"; \
		if [ $$? -ne 0 ] ;then exit 1; fi; \
	done

.PHONY: lint-xml

lint-xliff:
	find . -name '*.xliff' \
		-not -path './vendor/*' \
		-not -path './src/Resources/public/vendor/*' \
		| while read xmlFile; \
	do \
		XMLLINT_INDENT='  ' xmllint --encode UTF-8 --format "$$xmlFile"|diff - "$$xmlFile"; \
		if [ $$? -ne 0 ] ;then exit 1; fi; \
	done

.PHONY: lint-xliff

lint-php:
	php-cs-fixer fix --ansi --verbose --diff --dry-run
.PHONY: lint-php

lint-symfony: lint-symfony-container lint-symfony-twig lint-symfony-xliff lint-symfony-yaml
.PHONY: lint-symfony

lint-symfony-container:
	bin/console lint:container
.PHONY: lint-symfony-container

lint-symfony-twig:
	run: bin/console lint:twig src tests
.PHONY: lint-symfony-twig

lint-symfony-xliff:
	run: bin/console lint:xliff src tests
.PHONY: lint-symfony-xliff

lint-symfony-yaml:
	run: bin/console lint:yaml src tests
.PHONY: lint-symfony-yaml

cs-fix: cs-fix-php cs-fix-xml cs-fix-xliff cs-fix-composer
.PHONY: cs-fix

cs-fix-php:
	php-cs-fixer fix --verbose
.PHONY: cs-fix-php

cs-fix-xml:
	find . -name '*.xml' \
		-not -path './vendor/*' \
		-not -path './src/Resources/public/vendor/*' \
		| while read xmlFile; \
	do \
		XMLLINT_INDENT='    ' xmllint --encode UTF-8 --format "$$xmlFile" --output "$$xmlFile"; \
	done
.PHONY: cs-fix-xml

cs-fix-xliff:
	find . -name '*.xliff' \
		-not -path './vendor/*' \
		-not -path './src/Resources/public/vendor/*' \
		| while read xmlFile; \
	do \
		XMLLINT_INDENT='  ' xmllint --encode UTF-8 --format "$$xmlFile" --output "$$xmlFile"; \
	done
.PHONY: cs-fix-xliff

cs-fix-composer:
	composer-normalize
.PHONY: cs-fix-composer

build:
	mkdir $@

test:
	vendor/bin/phpunit -c phpunit.xml.dist
.PHONY: test

coverage:
	vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml
.PHONY: coverage

docs:
	cd docs && sphinx-build -W -b dirhtml -d _build/doctrees . _build/html
.PHONY: docs

phpstan:
	vendor/bin/phpstan --memory-limit=1G analyse
.PHONY: phpstan

psalm:
	vendor/bin/psalm --php-version=8.0
.PHONY: psalm
