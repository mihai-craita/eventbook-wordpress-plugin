#!/bin/bash

BUILD_FOLDER="build"

mkdir -p $BUILD_FOLDER
cp -r ./eventbook $BUILD_FOLDER
composer install --optimize-autoloader --no-dev --working-dir=./$BUILD_FOLDER/eventbook
rm -rf ./$BUILD_FOLDER/eventbook/tests
rm ./$BUILD_FOLDER/eventbook/phpunit.xml
tar -zcvf ./$BUILD_FOLDER/eventbook-plugin.tar.gz build/eventbook/ -C ./build
rm -rf  ./$BUILD_FOLDER/eventbook
