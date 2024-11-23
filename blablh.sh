#!/bin/bash
for i in database/migrations/*; do
    echo "File: $i" >> blabla;
    cat "$i" >> blabla;
    echo -e "\n---\n" >> blabla;
done

