#!/bin/sh

phpunit6 --coverage-html ./test/coverage/ --whitelist src --bootstrap test/autoload.php test
