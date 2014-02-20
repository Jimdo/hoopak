all: test

gen-php: thrift/scribe.thrift thrift/zipkinCore.thrift
	$(foreach f,$^,thrift --gen php $(f);)

composer.phar:
	curl -s https://getcomposer.org/installer | php -d detect_unicode=Off

vendor/bin/phpunit: composer.phar
	php composer.phar install

test: vendor/bin/phpunit
	vendor/bin/phpunit tests
