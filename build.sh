#!/bin/bash

composer install
php bin/console d:m:diff
php bin/console d:m:m
