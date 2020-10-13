INSTALL
===================================



## Server Requirements

PHP version 7.2 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)

## Instructions

 1. Get the project from Github

 2. Unzip "writable.zip" and move the entire folder at the root level of the project

 3. Copy/paste the file ".env_default" and rename it to ".env"

 4. Create a new MySql database

 5. Edit .env file (uncommented lines)

 6. Load the database

    Go to URL http://localhost:8080/loaddb to import the database

----
