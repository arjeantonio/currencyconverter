.DEFAULT_GOAL := help
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'
	@echo

.PHONY: lint
lint: ## Runs linters and static-analysis
	vendor/bin/phpcs -p --standard=PSR12 src/ tests/
	vendor/bin/psalm --show-info=true

.PHONY: test
test: ## Runs all unit tests with PHPUnit and Testdox
	./vendor/bin/phpunit --testdox --colors