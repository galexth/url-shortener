# Engineer Test

## Application Description

Create a URL-shortener application to shorten URLs.

The application should be made up of an HTML and javascript based frontend that communicates with a backend API.

Users will be able to create short URLs from a full length URL, when they visit the short url it will redirect them to the original URL.

## Application Functionalities

Depending on the role you are applying for you will have been asked to implement the ​Base​ or Advanced​ test. 
The ​Advanced​ test includes all the functionality of the ​Base​ test. 
If you have only been asked to implement the ​Base​ test you are welcome to also attempt some or all of the Advanced​ requirements.

## Base

● User page to input a url and return a shortened URL.

● Input URL should be validated and respond with error if not a valid URL

● Regex based blacklist for URLs, urls that match the blacklist respond with an error

● Visiting the Shortened URLs must redirect to the original URL with a HTTP 302 redirect

● Hit counter for shortened URLs (increment with every hit)

● Admin page with URL List View:

○ Show list of URL records with:

■ Short Code

■ Full Url

■ Expiry (if any)

■ Number of hits

○ Filter List by Short Code


## Advanced

● Add a caching layer to avoid repeated database calls on popular URLs

● User can specify an expiration time for URLs, expired URLs must return HTTP 410

● Admin List View:

○ Implement Pagination

○ Sort by expiration date and number of hits

○ Filter List by keyword on full URL

○ Delete a URL (after deletion shortened URLs must return HTTP 410)


## Tech Stack

There is no required tech stack but suggestions include:
● PHP 7.4+ and modern PHP Framework like Laravel or Symfony

● Javascript based framework like React JS or Angular

● SQL based database

● Easy to run development environment (so we can test quickly)


## Implementation Guide

These are the things that we will be looking at when we review your test
● Split application into Frontend UI and Backend API

● Well designed API with sensible params, responses and error handling

● Clean, commented code

● Use OOP concepts to decouple code (no fat controllers)

● Unit Tests where possible

● Meaningful, incremental git commits

● Instructions on how to run the application



## Requirements

● Docker

## Instructions

```shell
sh init.sh
```

#### [Form](http://localhost:8000) (http://localhost:8000)

#### [List](http://localhost:8000/admin/urls) (http://localhost:8000/admin/urls)

#### [Usage](http://localhost:8000) (http://localhost:8000/{some_code_like_bx})

**config/decoder.php**

```php
return [
    'index' => env('DECODER_INDEX', 'bcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
];
```

**config/blacklist.php**
```php
return [
    'rules' => [
        'test\.com',
        'mov',
    ]
];
```

#### To run some tests

```shell
docker-compose exec php php artisan test
```

