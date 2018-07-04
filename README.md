# pyrocms-api

## Introduction

supported pyrocms an api service

## Usage

step 1. install with composer

```
composer require papajo/pyrocms-api:^1.0 --no-scripts
```

step 2. add PyrocmsapiServiceProvider to config/app.php

```
'providers' => [

    /*
    * Pyrocmsapi Service Provider
    */
    Pyrocmsapi\Providers\PyrocmsapiServiceProvider::class,

]
```

step 3. call or edit api route in vendor/papajo/pyrocms-api/Routes/api.php

tips: all api uri prefix is /api/


## PyroCMS

PyroCMS is an easy to use, powerful, and modular CMS and development platform built with Laravel 5.

[https://github.com/pyrocms/pyrocms](https://github.com/pyrocms/pyrocms)
