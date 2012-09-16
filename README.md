## What is GetDump ?
GetDump is useful a database dump generator tool. This tool is working with multiple platform. (MySql, MsSql, Interbase, ... etc.) but now supported only the following platforms :

MySQL

## Usage :
```
php getdump.php --adapter=[my database server type : mysql|mssql|interbase] --host=[my database server host ip or domain] --username=[my username] --password=[my password] --database=[my database name]
```

## Example :
```
php getdump.php --adapter=mysql --host=127.0.0.1 --username=root --password=examplepassword --database=example_database
```
