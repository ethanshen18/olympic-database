<?php

function executeSQL($cmdstr) { 
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        $e = OCI_Error($db_conn)['message'];
        echo "
            <div class='alert alert-danger' role='alert'>
                Cannot parse the following command: $cmdstr<br>$e
            </div>
        ";
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        $e = oci_error($statement)['message'];
        echo "
            <div class='alert alert-danger' role='alert'>
                Cannot execute the following command: $cmdstr<br>$e
            </div>
        ";
        $success = False;
    }

    return $statement;
}

function connectToDB() {
    global $db_conn;

    include "credential.php";

    if ($db_conn) {
        return true;
    } else {
        $e = OCI_Error()['message'];
        echo "
            <div class='alert alert-danger' role='alert'>
                Failed to connect to database!<br>$e
            </div>
        ";
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

    $file = file_get_contents('olympic.sql');
    $queries = explode(';', $file);
    
    foreach ($queries as $query) {
        executeSQL($query);
    }

    OCICommit($db_conn);
}

// print query result as table
function printTable($result) {
    $numCols = oci_num_fields($result);

    echo "<div class='table-responsive'><table class='table table-hover text-left'>";

    // print table header
    echo "<thead><tr>";
    for ($i = 1; $i <= $numCols; $i++) {
        $col_name = oci_field_name($result, $i);
        echo "<th scope='col'>$col_name</th>";
    }
    echo "</tr></thead>";

    // print table content
    echo "<tbody>";
    while (($row = oci_fetch_row($result)) != false) {
        echo "<tr>";
        for ($i = 0; $i < $numCols; $i++) {
            echo "<td>";
            echo $row[$i];
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "<tbody>";
    echo "</table>";
    echo "</div>";
}

function addCountry() {
    global $db_conn;
    $countryname = $_POST['countryname'];
    $medalcount = $_POST['medalcount'];
    executeSQL("insert into country values ('$countryname', $medalcount)");
    OCICommit($db_conn);
}

function updateCountry() {
    global $db_conn;
    $countryname = $_POST['countryname'];
    $medalcount = $_POST['medalcount'];
    executeSQL("update country set countrymedalcount='$medalcount' where countryname='$countryname'");
    OCICommit($db_conn);
}

function deleteCountry() {
    global $db_conn;
    $countryname = $_POST['countryname'];
    executeSQL("delete from country where countryname='$countryname'");
    OCICommit($db_conn);
}

function addTeam() {
    global $db_conn;
    $teamname = $_POST['teamname'];
    $teamsize = $_POST['teamsize'];
    $residency = $_POST['residency'];
    $countryname = $_POST['countryname'];
    executeSQL("insert into team values ('$teamname', $teamsize, '$residency', '$countryname')");
    OCICommit($db_conn);
}

function updateTeam() {
    global $db_conn;
    $teamname = $_POST['teamname'];
    $residency = $_POST['residency'];
    executeSQL("update team set residency='$residency' where teamname='$teamname'");
    OCICommit($db_conn);
}

function deleteTeam() {
    global $db_conn;
    $teamname = $_POST['teamname'];
    executeSQL("delete from team where teamname='$teamname'");
    OCICommit($db_conn);
}

function addAthlete() {
    global $db_conn;
    $athleteid = $_POST['athleteid'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $medalcount = $_POST['medalcount'];
    $teamname = $_POST['teamname'];
    executeSQL("insert into athletebelongs values ($athleteid, '$name', $age, $medalcount, '$teamname')");
    OCICommit($db_conn);
}

function updateAthlete() {
    global $db_conn;
    $athleteid = $_POST['athleteid'];
    $medalcount = $_POST['medalcount'];
    executeSQL("update athletebelongs set medalcount='$medalcount' where athleteid='$athleteid'");
    OCICommit($db_conn);
}

function deleteAthelete() {
    global $db_conn;
    $athleteid = $_POST['athleteid'];
    executeSQL("delete from athletebelongs where athleteid='$athleteid'");
    OCICommit($db_conn);
}

function selection() {
    global $db_conn;

    $medalCount = $_POST['medalCount'];

    $result = executeSQL("
        select * 
        from athletebelongs 
        where medalcount >= $medalCount
    ");

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: athletes with at least $medalCount medals</div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function projection() {
    global $db_conn;

    $selection = "athleteid";

    if (isset($_POST['name'])) $selection .= ", name";
    if (isset($_POST['age'])) $selection .= ", age";
    if (isset($_POST['medalcount'])) $selection .= ", medalcount";
    if (isset($_POST['teamname'])) $selection .= ", teamname";

    $result = executeSQL("select $selection from athletebelongs");

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: athlete details </div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function joinQuery() {
    global $db_conn;

    $name = $_POST['name'];

    $result = executeSQL("
        select athletebelongs.name, team.residency 
        from athletebelongs, team 
        where athletebelongs.teamname = team.teamname and athletebelongs.name like '%$name%'
    ");

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: $name's residency </div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function aggregation() {
    global $db_conn;

    $result = executeSQL('
        select max(medalcount) as "Top individual medal count", teamname 
        from athletebelongs 
        group by teamname
    ');

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: the maxiumum individual medal count from each team</div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function nested() {
    global $db_conn;

    $result = executeSQL('
    WITH temp(age) as(
        SELECT min(age)
        FROM athletebelongs, team
        WHERE athletebelongs.teamname = team.teamname
        GROUP BY team.teamname)
        SELECT avg(age) as "Average minimum age"
        FROM temp
    ');

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: the average age of the youngest athletes from each team </div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function division() {
    global $db_conn;

    $result = executeSQL("
        select * 
        from athletebelongs A 
        where not exists (
            select competitionname 
            from competition B
            where not exists (
                select C.competitionname 
                from competes C 
                where C.athleteid = A.athleteid and B.competitionname = C.competitionname
            )
        )
    ");

    // print query results
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: athletes who participate in every competition </div>
            <div class='card-body'>
    ";
    printTable($result);
    echo "
            </div>
        </div>
        <br>
    ";

    OCICommit($db_conn);
}

function executeQuery($func) {
    if (connectToDB()) {
        $func();
        disconnectFromDB();
    }
}

?>