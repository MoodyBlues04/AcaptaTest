# AcaptaTest

## Installation
1. copy .env file ```cp .env.example .env``` and configure your app settings
2. put your Acapta Bearer into `ACAPTA_TOKEN` variable in `.env`
3. enjoy

## Usage
+ `./app/Modules/Api` - contains all api integration logic
+ `./app/Modules/Parsers/Acapta` - contains all parsing logic.
  Extensibility is achieved by using a strategy pattern that abstracts away the details of the data table implementation.
