# Olympic Games Database

## PHP Based SQL Database Application

This is a PHP based application that allow users to view, add, and delete entries from our Olympic games database. 


## Add Credential

In order to connect to the database, you need to create a new file called credential.php and add in the following line. 

```
<?php
    $db_conn = OCILogon("ora_CWL", "aXXXXXXXX", "dbhost.students.cs.ubc.ca:1522/stu");
?>
```

Replace CWL and XXXXXXXX with your UBC login username and password. 

## View Application

To view the application, upload all php and html files to your UBC CS server directory, and go to the following address and replace CWL with your UBC login username

```
https://www.students.cs.ubc.ca/~CWL/
```
