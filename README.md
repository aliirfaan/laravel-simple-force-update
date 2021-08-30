# Laravel Simple Force Update

Very often we want clients, especially mobile applications to update to the latest version. To enable force update feature, we need to keep release versions in our database and compare with the version of the device. This package creates a table to keep releases and provides a service to suggest actions based on version comparison.

## Features

* Table structure to keep versions with support for multiple applications and platforms
* Uses semantic versioning format to keep versions
* Provides interface so that you can implement your own force update logic

## Default force update logic
* candidate version == max version ||  candidate version > max version : No action
* candidate version < max version && candidate version > min version : Action: update available
* candidate version < min version : Action: update required / force update
* candidate version == min version : Action: update available

## Requirements

* [Composer](https://getcomposer.org/)
* [Laravel](http://laravel.com/)
* [naneau/semver](https://github.com/naneau/semver)

## Installation

You can install this package on an existing Laravel project with using composer:

```bash
 $ composer require aliirfaan/laravel-simple-force-update
```

Register the ServiceProvider by editing **config/app.php** file and adding to providers array:

```php
  aliirfaan\LaravelSimpleForceUpdate\SimpleForceUpdateProvider::class,
```

Note: use the following for Laravel <5.1 versions:

```php
 'aliirfaan\LaravelSimpleForceUpdate\SimpleForceUpdateProvider',
```

Publish files with:

```bash
 $ php artisan vendor:publish --provider="aliirfaan\LaravelSimpleForceUpdate\SimpleForceUpdateProvider"
```

or by using only `php artisan vendor:publish` and select the `aliirfaan\LaravelSimpleForceUpdate\SimpleForceUpdateProvider` from the outputted list.

Apply the migrations:

```bash
 $ php artisan migrate
 ```

## Usage

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use aliirfaan\LaravelSimpleForceUpdate\Services\SimpleForceUpdateService;

class TestController extends Controller
{
    public function index(Request $request, SimpleForceUpdateService $simpleForceUpdateService)
    {
        // get all versions by application. You may have multiple applications/mobile apps
        $appName = 'default';
        $platform = null;

        $result = $simpleForceUpdateService->getVersions($appName, $platform);
        dd($result);

        // get version by application and platform.
        $appName = 'default';
        $platform = 'android';

        $result = $simpleForceUpdateService->getVersions($appName, $platform);
        dd($result);

        // get update action based on a candidate version.
        $candidateVersion = '2.0.9'; // this is normally the version currently installed on the client/device
        $appName = 'default';
        $platform = 'android';

        $result = $simpleForceUpdateService->getApplicationCompatibility($candidateVersion, $appName, $platform);
        dd($result);

    }
}
```

## Implement your own force update logic

To implement your own force update logic, create a class that extends the **aliirfaan\LaravelSimpleForceUpdate\Contracts\AbstractSimpleForceUpdate** class

```php
<?php

namespace App\Services;

use aliirfaan\LaravelSimpleForceUpdate\Contracts\AbstractSimpleForceUpdate;

class CustomForceUpdateService extends AbstractSimpleForceUpdate 
{
    /**
     * You can override this function or create another function
     */
    public function getApplicationCompatibility($candidateVersion, $appName = 'default', $platform = 'android')
    {
        // your logic
    }
}
```

## License

The MIT License (MIT)

Copyright (c) 2020

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.