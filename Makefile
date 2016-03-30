cs:
	php-cs-fixer fix --verbose

cs_dry_run:
	php-cs-fixer fix --verbose --dry-run

test:
	phpunit

docs:
	cd Resources/doc && sphinx-build -W -b html -d _build/doctrees . _build/html

ai:
	lessc Resources/public/less/sonata-translation.less > Resources/public/css/sonata-translation.css
