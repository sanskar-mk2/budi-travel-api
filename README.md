# Budi Travel API

## Steps to run
 -  Copy `.env.example` to `.env` and fill in the values
 -  `npm install`
 -  `composer install`
 -  `php artisan key:generate`
 -  `php artisan migrate --seed`
 -  `php artisan storage:link`
 - `extension=sodium` in your php.ini

Add firebase credentials to the root folder.
