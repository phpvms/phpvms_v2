#
#
#

.PHONY: install-cs
install-cs:
	composer global require "squizlabs/php_codesniffer=*"
	composer global require "wimg/php-compatibility=*"
	phpcs --config-set installed_paths ~/.composer/vendor/wimg/php-compatibility

.PHONY: run-cs-53
run-cs-53:
	phpcs -p . --standard=PHPCompatibility --warning-severity=0  --runtime-set testVersion 5.3

.PHONY: run-cs-70
run-cs-70:
	phpcs -p . --standard=PHPCompatibility --warning-severity=0 --runtime-set testVersion 7.0- 
