<?php

// takes a plain (no bound variables) SQL command and executes it
function executePlainSQL($cmdstr) { 
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */
function executeBoundSQL($cmdstr, $list) {
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function printResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function connectToDB() {
    global $db_conn;

    include "credential.php";

    if ($db_conn) {
        return true;
    } else {
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    OCILogoff($db_conn);
}

// drop and create all tables available in olympic.sql
function resetTables() {
    global $db_conn;
    global $tableNames;

    // drop old tables
    foreach (array_reverse(array_keys($tableNames)) as $key) executePlainSQL("drop table " . $key);

    // create new tables
    executePlainSQL("
        create table country
        (
            countryname       char(50),
            countrymedalcount int,
            primary key (countryname)
        )
    ");

    executePlainSQL("
        insert into country
        values ('Canada', 12)
    ");

    executePlainSQL("
        create table team
        (
            teamname    char(50),
            teamsize    int      not null,
            residency   char(50),
            countryname char(50) not null unique,
            primary key (teamname),
            foreign key (countryname) references country on delete cascade
        )
    ");

    executePlainSQL("
        insert into team
        values ('Team of Canada', 30, 'Building 1', 'Canada')
    ");

    executePlainSQL("
        create table athletebelongs
        (
            athleteid   int,
            name        char(50) not null,
            competition char(50) not null,
            medalcount  int,
            teamname    char(50),
            primary key (athleteid),
            foreign key (teamname) references team on delete cascade
        )
    ");

    executePlainSQL("
        insert into athletebelongs
        values (1, 'Jane Doe', 'Skating', 1, 'Team of Canada')
    ");

    executePlainSQL("
        create table onlineaudience
        (
            username char(50) not null,
            password char(50) not null,
            primary key (username)
        )
    ");

    executePlainSQL("
        insert into onlineaudience
        values ('User 1', 'Em52kqvj')
    ");

    executePlainSQL("
        create table inpersonaudience
        (
            audienceid   int      not null,
            audiencename char(50) not null,
            primary key (audienceid)
        )
    ");

    executePlainSQL("
        insert into inpersonaudience
        values (1, 'John Smith')
    ");

    executePlainSQL("
        create table competition
        (
            competitionname char(50),
            competitiontime char(50),
            venue           char(50),
            primary key (competitionname)
        )
    ");

    executePlainSQL("
        insert into competition
        values ('Skating', '2022-02-16 15:30:00', 'Arena 1')
    ");

    executePlainSQL("
        create table volunteer
        (
            volunteerid      int,
            name             char(50),
            responsibilities char(50),
            primary key (volunteerid)
        )
    ");

    executePlainSQL("
        insert into volunteer
        values (1, 'John Doe', 'Guide atheletes to locker rooms')
    ");

    executePlainSQL("
        create table mediastreamingplatform
        (
            mediaplatform  char(50),
            viewcount      int,
            watchduruation int,
            primary key (mediaplatform)
        )
    ");

    executePlainSQL("
        insert into mediastreamingplatform
        values ('BBC', 50000, 2000)
    ");

    executePlainSQL("
        create table sponsor
        (
            sponsorname char(50),
            address     char(50),
            primary key (sponsorname)
        )
    ");

    executePlainSQL("
        insert into sponsor
        values ('Nike', 'New York')
    ");

    executePlainSQL("
        create table represents
        (
            teamname    char(50),
            countryname char(50) not null unique,
            primary key (teamname),
            foreign key (teamname) references team on delete cascade,
            foreign key (countryname) references country on delete cascade
        )
    ");

    executePlainSQL("
        insert into represents
        values ('Team of Canada', 'Canada')
    ");

    executePlainSQL("
        create table attends
        (
            ticketnumber int,
            audienceid   int not null,
            primary key (ticketnumber),
            foreign key (audienceid) references inpersonaudience on delete cascade
        )
    ");

    executePlainSQL("
        insert into attends
        values (1, 1)
    ");

    executePlainSQL("
        create table ticket
        (
            ticketnumber    int,
            seat            int      not null,
            competitionname char(50) not null,
            primary key (ticketnumber),
            foreign key (competitionname) references competition on delete cascade
        )
    ");

    executePlainSQL("
        insert into ticket
        values (1, 50, 'Skating')
    ");

    executePlainSQL("
        create table ticketprice
        (
            competitionname char(50) not null,
            seat            int      not null,
            price           int      not null,
            primary key (competitionname, seat),
            foreign key (competitionname) references competition on delete cascade
        )
    ");

    executePlainSQL("
        insert into ticketprice
        values ('Skating', 50, 200)
    ");

    executePlainSQL("
        create table watches
        (
            username      char(50),
            mediaplatform char(50),
            starttime     char(50) not null,
            endtime       char(50) not null,
            primary key (username, mediaplatform),
            foreign key (username) references onlineaudience on delete cascade,
            foreign key (mediaplatform) references mediastreamingplatform on delete cascade
        )
    ");

    executePlainSQL("
        insert into watches
        values ('User 1', 'BBC', '2022-02-16 15:30:00', '2022-02-16 18:30:00')
    ");

    executePlainSQL("
        create table streams
        (
            competitionname char(50),
            mediaplatform   char(50),
            primary key (competitionname, mediaplatform),
            foreign key (competitionname) references competition on delete cascade,
            foreign key (mediaplatform) references mediastreamingplatform on delete cascade
        )
    ");

    executePlainSQL("
        insert into streams
        values ('Skating', 'BBC')
    ");

    executePlainSQL("
        create table streamprice
        (
            competitionname char(50),
            price           int not null,
            primary key (competitionname),
            foreign key (competitionname) references competition on delete cascade
        )
    ");

    executePlainSQL("
        insert into streamprice
        values ('Skating', 50000)
    ");

    executePlainSQL("
        create table competes
        (
            athleteid       int,
            competitionname char(50) not null,
            lockerroom      int,
            placement       int,
            primary key (athleteid, competitionname),
            foreign key (athleteid) references athletebelongs on delete cascade,
            foreign key (competitionname) references competition on delete cascade
        )
    ");

    executePlainSQL("
        insert into competes
        values (1, 'Skating', 5, 3)
    ");

    executePlainSQL("
        create table assists
        (
            volunteerid int,
            athleteid   int,
            primary key (volunteerid, athleteid),
            foreign key (volunteerid) references volunteer on delete cascade,
            foreign key (athleteid) references athletebelongs on delete cascade
        )
    ");

    executePlainSQL("
        insert into assists
        values (1, 1)
    ");

    executePlainSQL("
        create table athleteneed
        (
            athleteid int,
            needs     char(50),
            primary key (athleteid),
            foreign key (athleteid) references athletebelongs on delete cascade
        )
    ");

    executePlainSQL("
        insert into athleteneed
        values (1, 'Extra towels')
    ");

    executePlainSQL("
        create table funds
        (
            sponsorname char(50),
            teamname    char(50),
            amount      int not null,
            primary key (sponsorname, teamname),
            foreign key (sponsorname) references sponsor on delete cascade,
            foreign key (teamname) references team on delete cascade
        )
    ");

    executePlainSQL("
        insert into funds
        values ('Nike', 'Team of Canada', 200000)
    ");

    OCICommit($db_conn);
}

///////////////////////////////// Query Handlers ////////////////////////////////////////////////////////////

function addCountry() {
    global $db_conn;

    $tuple = array (
        ":bind1" => $_POST['countryName'],
        ":bind2" => $_POST['medalCount']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into country values (:bind1, :bind2)", $alltuples);
    OCICommit($db_conn);
}

function updateMedalCount() {
    global $db_conn;
    executePlainSQL("update country set countrymedalcount='" . $_POST['medalCount'] . "' where countryname='" . $_POST['countryName'] . "'");
    OCICommit($db_conn);
}

function deleteCountry() {
    global $db_conn;
    executePlainSQL("delete from country where countryname='" . $_POST['countryName'] . "'");
    OCICommit($db_conn);
}

// TODO: add more handlers here







///////////////////////////////// End Handlers ////////////////////////////////////////////////////////////

function executeQuery($func) {
    if (connectToDB()) {
        $func();
        disconnectFromDB();
    }
}

?>