all: test

composer.phar:
	curl -s https://getcomposer.org/installer | php -d detect_unicode=Off

vendor/bin/phpunit: composer.phar
	php composer.phar install

test: vendor/bin/phpunit
	vendor/bin/phpunit tests
