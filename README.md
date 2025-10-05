<p align="center"><img alt="POS System" src=""></p>


## About POS System

...

## Deploy
```shell
    composer install
```

```shell
    npm install
```

```shell
    npm run build
```

```shell
    php artisan storage:link
```

```shell
    php artisan migrate
```

```shell
    php artisan key:generate
```

Run lang for js

```shell
    php artisan lang:js -c -s lang
```

## Seeder For Test
```shell
    php artisan db:seed
```

## Clear Cache
```shell
    php artisan optimize:clear
    php artisan view:clear
```

## Sample commands
```shell
    php artisan migrate:rollback --step=1
    php artisan migrate:refresh
    php artisan make:model Sample --migration
    php artisan db:seed --class=TestDatabaseSeeder
```

## Admin login
- admin / 123456a@
