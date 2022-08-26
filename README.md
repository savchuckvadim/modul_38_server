Educational project - on skillfactory course.
API Backend
for start dev:
1) copy from .env.example to .env
2) create BD to localhost Apache and change env :

`DB_CONNECTION=mysql

DB_HOST=127.0.0.1`

`DB_PORT=3306`

`DB_DATABASE=modul_38`

`DB_USERNAME=root`

`DB_PASSWORD=`

3) Install dependences: `composer install`

4) Install Sanctum:

`composer require laravel/sanctum

php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`

and change .env

5) Install Fortify:

`composer require laravel/fortify

sail artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"`

6) `php artisan migrate:fresh --seed`
7) `php artisan serve` 
8) project starting on http://127.0.0.1:8000