# enverido/php
The official PHP library for the enverido licensing platform. You can use this library to integrate enverido with your product.

Make sure you have an [enverido](https://www.enverido.com/) account before you begin!

## Installation
Read below for installation instructions. 

### Composer
To install using composer, simply:

`composer require enverido/php`

## Documentation
For instructions on this library's use, read the official
documentation found [here](https://docs.cogative.com/display/ENVD/PHP).

## Testing
When executing tests, you need to add a .env  file to your tests directory. You may use the .env.example file in the same
directory as an example of the values to enter. These values must be valid at enverido.com for tests to pass as expected.

Once you've added this file, simply run the following command to make sure everything works:

`vendor\bin\phpunit --configuration="./phpunit.xml"`

## Contributing
If you wish to contribute, please make a pull request with an explanation of any changes you've made.