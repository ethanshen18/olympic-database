# Olympic Games Database

## PHP Based SQL Database Application

This is a PHP based application that allow users to view, add, and delete entries from our Olympic games database. 


## Add Credential

In order to connect to the database, you need to create a new file called credential.php and add in the following line. 

Replace CWL and XXXXXXXX with your UBC username and password. 

```
<?php
    $db_conn = OCILogon("ora_CWL", "aXXXXXXXX", "dbhost.students.cs.ubc.ca:1522/stu");
?>
```
