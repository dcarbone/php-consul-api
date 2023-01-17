.PHONY: php-cs-fixer
php-cs-fixer:
	./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix \
		--allow-risky=yes \
		--config ./tools/php-cs-fixer/php-consul-api-rules.php_cs \
		src
	./tools/php-cs-fixer/vendor/bin/php-cs-fixer fix \
		--allow-risky=yes \
		--config ./tools/php-cs-fixer/php-consul-api-rules.php_cs \
		tests