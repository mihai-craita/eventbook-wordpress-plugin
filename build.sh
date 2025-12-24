#!/bin/bash

set -e  # Exit on any error
set -u  # Exit on undefined variables

# Configuration
BUILD_FOLDER="build"
PLUGIN_FOLDER="eventbook"
VERSION=$(grep -i "Version:" "$PLUGIN_FOLDER/eventbook.php" | head -1 | awk '{print $NF}' | tr -d '\r')

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}Starting build process for version ${VERSION}${NC}"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Error: composer is not installed${NC}"
    exit 1
fi

# Check if zip is installed
if ! command -v zip &> /dev/null; then
    echo -e "${RED}Error: zip is not installed${NC}"
    exit 1
fi

# Clean up any previous build
if [ -d "$BUILD_FOLDER" ]; then
    echo -e "${YELLOW}Cleaning up previous build...${NC}"
    rm -rf "$BUILD_FOLDER"
fi

# Create build directory
mkdir -p "$BUILD_FOLDER"

# Copy plugin files
echo "Copying plugin files..."
cp -r "./$PLUGIN_FOLDER" "$BUILD_FOLDER/"

# Install production dependencies
echo "Installing production dependencies..."
composer install -q --optimize-autoloader --no-dev --working-dir="./$BUILD_FOLDER/$PLUGIN_FOLDER"

# Remove development files
echo "Removing development files..."
rm -rf "./$BUILD_FOLDER/$PLUGIN_FOLDER/tests"
rm -f "./$BUILD_FOLDER/$PLUGIN_FOLDER/phpunit.xml"
rm -f "./$BUILD_FOLDER/$PLUGIN_FOLDER/.gitignore"
rm -f "./$BUILD_FOLDER/$PLUGIN_FOLDER/.env"

# Copy README to plugin directory
echo "Copying README..."
cp ./README.md "./$BUILD_FOLDER/$PLUGIN_FOLDER/"

# Create archives
echo "Creating archives..."
cd "$BUILD_FOLDER"

# Create ZIP archive
zip -qr "./eventbook-${VERSION}.zip" "./$PLUGIN_FOLDER"

# Create TAR.GZ archive
tar -zcf "./eventbook-plugin-${VERSION}.tar.gz" "./$PLUGIN_FOLDER"

# Clean up temporary directory
rm -rf "./$PLUGIN_FOLDER"

cd ..

echo -e "${GREEN}Build finished successfully!${NC}"
echo "Archives created:"
echo "  - $BUILD_FOLDER/eventbook-${VERSION}.zip"
echo "  - $BUILD_FOLDER/eventbook-plugin-${VERSION}.tar.gz"
