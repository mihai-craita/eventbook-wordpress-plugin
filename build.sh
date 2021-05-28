#!/bin/bash

BUILD_FOLDER="build"

mkdir -p $BUILD_FOLDER
cp -r ./eventbook $BUILD_FOLDER
composer install -q --optimize-autoloader --no-dev --working-dir=./$BUILD_FOLDER/eventbook
rm -rf ./$BUILD_FOLDER/eventbook/tests
rm ./$BUILD_FOLDER/eventbook/phpunit.xml
cp ./README.md ./$BUILD_FOLDER/eventbook/.
echo 'Start building archives'
cd $BUILD_FOLDER
zip -qr ./evenbook.zip ./eventbook/*
tar -zcf ./eventbook-plugin.tar.gz -C ./ ./eventbook
rm -rf  ./eventbook
echo 'Build finished'
