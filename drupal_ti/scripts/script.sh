#!/bin/bash
# @file
# Behat integration - Script step.

set -e $DRUPAL_TI_DEBUG

# Ensure we are in the right directory, we need to overwrite this here
# since it is different from Drupal TI's default setup
DRUPAL_TI_DRUPAL_DIR="$TRAVIS_BUILD_DIR"

# Now go to the local behat tests, being within the project installation is
# needed for example for the drush runner.
cd "$DRUPAL_TI_BEHAT_DIR"

# We need to create a behat.yml file from behat.yml.dist.
drupal_ti_replace_behat_vars

# And run the tests, excluding any selenium tests
# To provide Selenium support, see http://jira.comicrelief.com/browse/PLAT-352
ARGS=( $DRUPAL_TI_BEHAT_ARGS )

echo $ARGS

## Now run the RND17 tests, we need to manually set some things here 
## as Drupal TI isn't used to running two different test suites
DRUPAL_TI_BEHAT_DIR="."
cd "$DRUPAL_TI_DRUPAL_DIR"
cd "$DRUPAL_TI_BEHAT_DIR"
drupal_ti_replace_behat_vars
composer install --no-progress -o
./vendor/bin/behat "${ARGS[@]}" --tags '~@not-on-travis'
