# NSW ORAC
NSW Ornithological Records Appraisal Committee web site.

## Requirements:
Apache, PHP, MySQL, and Server Side Includes (SSI).

## Apache configurations:
### Load the includes module...
`LoadModule include_module modules/mod_include.so`

### Configure Directory permissions...
```<Directory "/var/www/html">
    Options +Includes
</Directory>```

### Map the file extensions...
`AddType text/html .shtml`
`AddOutputFilter INCLUDES .shtml`

## MySQL connection
Change the `mysql.connection.php` settings to suit your local environment.

## Seed DB
`/src/process/insert-data.php?dataset=case`
`/src/process/insert-data.php?dataset=review`
`/src/process/insert-data.php?dataset=barc`
