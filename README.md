# Olympic Games Database

## PHP Based SQL Database Application

This is a PHP based web application that displays an instance of our SQL Olympic Games database. It allows users to view the entire database at a glance and make edits by using the given HTML forms. User will be able to see the updates they make instantaneously. 

## Add Credential

In order to connect to the database, users need to create a new file called credential.php and specify their Oracle database username and password using the sample file below. 

```
<?php
    $db_conn = OCILogon("ora_CWL", "aXXXXXXXX", "dbhost.students.cs.ubc.ca:1522/stu");
?>
```

Replace CWL and XXXXXXXX with UBC CWL username and password. 

## View Application

To view the application, the PHP and CSS files need to be hosted on a PHP enabled server. For CPSC 304, we will use the UBC CS server which already has an Apache server running. Simply upload all PHP and CSS files to the UBC CS student's home directory, and go to the following address to view the application.

```
https://www.students.cs.ubc.ca/~CWL/
```

Replace CWL with UBC CWL username. 
