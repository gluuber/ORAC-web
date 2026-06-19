# NSW ORAC
NSW Ornithological Records Appraisal Committee web site.

## Requirements:
Apache, PHP, MySQL, and Server Side Includes (SSI).

## Apache configurations:
### Load the includes module...
```bash
LoadModule include_module modules/mod_include.so
```

### Configure Directory permissions...
```bash
<Directory "/var/www/html">
    Options +Includes
</Directory>
```

### Map the file extensions...
```bash
AddType text/html .shtml
AddOutputFilter INCLUDES .shtml
```

## MySQL connection:
Move `mysql_connection.php` to `mysql.connection.php` and change the settings to suit your local environment.

```php
<?php
define('MYSQL_HOST','localhost');
define('MYSQL_USER','<PROD_USER>');
define('MYSQL_PASSWORD','<PROD_PASS>');
define('MYSQL_DATABASE','<PROD_DB>');
?>
```

## Seed the database:
Log into the admin area:
```bash
/src/process/
```

Help can be found on the admin home page.