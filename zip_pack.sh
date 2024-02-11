#!/bin/bash

./setup_jbxl

rm -rf .git .gitignore jbxl/.git ibxl/.gitignore

(cd ..; zip -r mod_apply.zip mod_apply/)

