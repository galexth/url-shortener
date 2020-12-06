## URL Shortener

```shell
sh init.sh
```

#### [Form](http://localhost:8000) (http://localhost:8000)

#### [List](http://localhost:8000/admin/urls) (http://localhost:8000/admin/urls)

config/decoder.php

```php
return [
    'index' => env('DECODER_INDEX', 'bcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
];
```

config/blacklist.php
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
php artisan test
```

