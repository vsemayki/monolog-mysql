monolog-mysql
=============

MySQL Handler for Monolog, which allows to store log messages in a MySQL table.
It can log text messages to a `monolog` table.
The class further is added extra attributes - `channel`, `level`, `ip`, `created_at` and `user_agent`, which are stored in a separate database fields, and can be used for later analyzing and sorting.

# Installation
monolog-mysql is available via composer:

```bash
composer require vsemayki/monolog-mysql:^2.0
```

# Usage
First of all, import `dump.sql`.

Just use it as any other Monolog Handler, push it to the stack of your Monolog Logger instance. The Handler however needs some parameters:

- **$pdo** PDO Instance of your database. Pass along the PDO instantiation of your database connection with your database selected.
- **$table** The table name where the logs should be stored
- **$level** can be any of the standard Monolog logging levels. Use Monologs statically defined contexts. _Defaults to Logger::INFO_
- **$bubble** _Defaults to true_

# Examples
Given that `$pdo` is your database instance, you could use the class as follows:

```php
<?php
use MySQLHandler\MySQLHandler;

$mySQLHandler = new MySQLHandler($pdo, 'monolog');

//Create logger
$logger = new \Monolog\Logger('db_logger');
$logger->pushHandler($mySQLHandler);

//Now you can use the logger, and further attach additional information
$logger->addInfo('User has been created, woohoo!', ['action' => 'user/create']);
```

# License
This tool is free software and is distributed under the MIT license. Please have a look at the LICENSE file for further information.
