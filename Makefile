.PHONY: dev-docker
dev-docker:
	docker buildx build -f docker/dev.Dockerfile -t php-dev:7.4-php-consul-api --load .

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