#!/bin/bash
# Simple script to install drupal for travis-ci running.
set -e $DRUPAL_TI_DEBUG

# Ensure the right Drupal version is installed.
if [ -d "$DRUPAL_TI_DRUPAL_DIR" ]
then
	return
fi

# HHVM env is broken: https://github.com/travis-ci/travis-ci/issues/2523.
PHP_VERSION=`phpenv version-name`
if [ "$PHP_VERSION" = "hhvm" ]
then
	# Create sendmail command, which links to /bin/true for HHVM.
	BIN_DIR="$TRAVIS_BUILD_DIR/../drupal_travis/bin"
	mkdir -p "$BIN_DIR"
	ln -s $(which true) "$BIN_DIR/sendmail"
	export PATH="$BIN_DIR:$PATH"
fi

# Create database and install Drupal.
mysql -e "create database $DRUPAL_TI_DB"
echo "Database: "$DRUPAL_TI_DB" created."

# Create environment.yml on the fly
databases:
  default:
    default:
      database: $DRUPAL_TI_DB
      username: root
      password:
      prefix:
      host: 127.0.0.1
      port:
      namespace: Drupal\\Core\\Database\\Driver\\mysql
      driver: mysql

settings:
  hash_salt: kzWT4Q5kJe2DkfS72PrATBUfkw54RKzMCbQg933K1Qwe0ZKtonOV_xdmuCac
EOF
echo 'File: environment.yml has been created.'

# Clear caches and run a web server.
drupal_ti_clear_caches
drupal_ti_run_server

# Start xvfb and selenium.
drupal_ti_ensure_xvfb
drupal_ti_ensure_webdriver
