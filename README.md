# AcaptaTest

## Installation
1. run `git clone <project link>` and `composer install` to install dependencies
2. copy .env file with ```cp .env.example .env``` and configure your app settings
3. put your Acapta Bearer into `ACAPTA_TOKEN` variable in `.env`
4. now, after `php artisan serve` you will find data table on index page 
5. enjoy

## Project structure
+ `./app/Modules/Api` - contains all api integration logic
+ `./app/Modules/Parsers/Acapta` - contains all parsing logic.
  Extensibility is achieved by using a strategy pattern that abstracts away the details of the data table implementation.
