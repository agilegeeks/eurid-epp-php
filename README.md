# Eurid EPP SDK

Eurid EPP SDK is a small library that allows you to connect with the EURid registry via the EPP protocol.

## How to use

This library was written using PHP's PSR-4 specification for autoloading. The library is available via composer repository, you can add it to your 'composer.json' like this:

```
...
"require": {
    "agilegeeks/eurid-epp-php": "dev-master",
},
"autoload": {
   "psr-4": {
       "AgileGeeks\\EPP\\Eurid\\": "src"
    }
}
...
```

Once installed, you can use the library like in the following example:

```
<?php
use AgileGeeks\EPP\Eurid\Client;

$client = new Client(
    $host = 'eurid_epp_host',
    $user = 'eurid_epp_user',
    $pass = 'eurid_epp_pass',
    $debug = 'true/false',
    $port = '700',
    $timeout = '30',
    $ssl = 'true/false',
    $context = null
);
$domain = "eurid.eu";

try {
    $client->login();
    $response = $client->checkDomains(domain);
}

die(var_dump($response));
```

## Contributions
The library is not fully implemented. Feel free to submit a pull request if you find that it's missing something you may need, or simply add it as an issue and we will sort it out.

## License
Copyright (c) 2018 Agile Geeks SRL-D

Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.














