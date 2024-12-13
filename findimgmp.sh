#!/bin/bash

# Specify the root directory to search
root_dir="/var/www/html"

# Search for files containing the string "xmlns:xlink"
find "$root_dir" -type f -exec grep -H "xmlns:xlink" {} \;
