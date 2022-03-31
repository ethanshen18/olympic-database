# Olympic Games Database

## PHP Based SQL Database Application

This is a PHP based web application that displays an instance of our SQL Olympic Games database. It allows users to view the entire database at a glance and make edits by using the given HTML forms. User will be able to see the updates they make instantaneously. 

## Supported SQL Queries

### Insert Operation

Users can insert new countries, teams and athletes by submitting a form with all required attributes. 

### Delete Operation

Users can delete countries, teams and athletes with their primary key. 

### Update Operation

Users can update the medal count of countries and athletes, and update the residency of teams. 

### Selection

Users can search for athletes who have a certain number of medals. 

### Projection

Users can choose 1 - 4 attributes that they are interested to see for all athletes.

### Join

Users can search for the residency of any athletes. 

### Aggregation

Users can find the maximum individual medal count from each team. 

### Nested Aggregation with Group By

Users can find the average age of the youngest athletes from each team. 

### Division

Users can find athletes who participate in every competition. 

## Database Connection

In order to connect to the database, users need to create a new file in the root directory and name it `credential.php`. Then enter their Oracle database username and password according to the example below. 

```
<?php
    $db_conn = OCILogon("ora_CWL", "aXXXXXXXX", "dbhost.students.cs.ubc.ca:1522/stu");
?>
```

Replace `CWL` and `XXXXXXXX` with UBC CWL username and password. 

## View Application

To view the application, the source files need to be hosted on a PHP enabled server. For CPSC 304, we will use the UBC CS server which already has an Apache server running. Simply upload all source files to the UBC CS student's home directory under `/public_html`, and go to the following address to view the application.

```
https://www.students.cs.ubc.ca/~CWL
```

Replace `CWL` with UBC CWL username. 
