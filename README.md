## Web Crawler

Command line tool to crawl a webpage and return the assets and linked pages.

## Requirements
```
Composer
PHP 7.3 or higher
```

## Setup
**1. Download all dependencies by running following command**
```
composer install
```
**2. Run docker containers using laravel sail**
```
./vendor/bin/sail up -d
```
**3. Copy .env.example to .env**
```
cp .env.example .env
```
**4. Generate key**
```
./vendor/bin/sail artisan key:generate
```



## Execute
**Tool will output the linked pages and assets used on each page**

```
./vendor/bin/sail artisan crawler:crawl https://medium.com/inside-sumup --depth=1
```

## Testing 

To run all tests:

```
./vendor/bin/sail artisan test 
```
