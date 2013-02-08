Test application for the Transactional module
=============================================
Since the module relies on shutdown functions and overrides a core framework class (Request_Client_Internal), it cannot be usefully tested using regular PHPUnit tests. Instead, it's necessary to create a test application.

How to test
===========
* unpack your chosen version of the Kohana framework to a directory, which shall be called `$ROOT` below
* remove/rename `install.php`
* add the transactional module to `$ROOT/modules/transactional` and to `Kohana::modules` in `bootstrap.php`
* copy/merge the directory `$ROOT/modules/transactional/tests/application` to `$ROOT/application`
* Install your chosen database, and create a table like this: `CREATE TABLE test (changed INTEGER);` - and be sure to make it support transactions, e.g. by adding `ENGINE=InnoDB` in MySQL.  
* add a `database.php` to `$ROOT/application/config`, with a default config for that database
* Configure your PHP webserver to use `$ROOT` as docroot, and to support the cURL PHP extension as well as the one for your chosen database.

That's it for the setup! Now:

* Open the URL of your server with no additional path, e.g. `http://localhost`
* you should see a table of test results. If the last two columns (Response Code and DB changed) are completely green, everything worked as it should. Cells with a red background indicate a failed test.
