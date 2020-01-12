# Vending Machine
Vending Machine is a PHP project based on Command Line Interface (CLI). It is used to create vending machine and manage the usage of it.

## Prerequisites
- This project required PHP scripting language to be installed first. This project is build on PHP version `7.1` so make sure you install newer or at least this PHP version to guarantee functionality.
- This project required composer dependency manager to be installed first. Please make sure that composer has been added to $PATH

## Setup
To run auto setup can be done by running `bin/setup`. But if it can't be done, please follow the following instruction:
- `composer install`
- `vendor/bin/phpunit --coverage-text --whitelist vending_machine.php tests/vending_machine_test.php`

## Running
It can be run with interactive command prompt based shell where commands can be typed in by running `bin/vending_machine`. If it can't be done, plase follow the following instruction:
- `php runtime.php`
