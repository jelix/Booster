#!/bin/bash

database=$1
file=$2


mysql -u jelixwww -pjelix $1 < /dumps/$file
