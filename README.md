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
Change the `mysql.connection.php` settings to suit your local environment.

```php
<?php
define('MYSQL_HOST','localhost');
define('MYSQL_USER','<PROD_USER>');
define('MYSQL_PASSWORD','<PROD_PASS>');
define('MYSQL_DATABASE','<PROD_DB>');
?>
```

## Seed the database:
```bash
/src/process/insert-data.php?dataset=case
/src/process/insert-data.php?dataset=review
/src/process/insert-data.php?dataset=barc
```

Source CSV files can be found under `/src/process/src`

```bash
Review List.csv
BARC_Index_of_Cases.csv
Index of Cases.csv
```
