<?php

// takes a plain (no bound variables) SQL command and executes it
function executePlainSQL($cmdstr) { 
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

/* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */
function executeBoundSQL($cmdstr, $list) {
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

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($statement, $bind, $val);
            unset ($val);
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            $e = OCI_Error($statement)['message'];
            echo "
                <div class='alert alert-danger' role='alert'>
                    Cannot execute the following command: $cmdstr<br>$e
                </div>
            ";
            $success = False;
        }
    }
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
        executePlainSQL($query);
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

function addAthlete() {
    global $db_conn;

    $tuple = array(
        ":bind1" => $_POST['athleteid'],
        ":bind2" => $_POST['athleteName'],
        ":bind3" => $_POST['athleteAge'],
        ":bind4" => $_POST['athletecompetition'],
        ":bind5" => $_POST['athletemedalcount'],
        ":bind6" => $_POST['athleteteamname']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into athletebelongs values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
    OCICommit($db_conn);
}

function updateAthleteMedalCount() {
    global $db_conn;
    executePlainSQL("update athletebelongs set medalcount='" . $_POST['athletemedalcount'] . "' where athleteid='" . $_POST['athleteid'] . "'");
    OCICommit($db_conn);
}

function deleteAthelete() {
    global $db_conn;
    executePlainSQL("delete from athletebelongs where athleteid='" . $_POST['athleteid'] . "'");
    OCICommit($db_conn);
}

function addTeam() {
    global $db_conn;

    $tuple = array(
        ":bind1" => $_POST['teamname'],
        ":bind2" => $_POST['teamsize'],
        ":bind3" => $_POST['residency'],
        ":bind4" => $_POST['countryname']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into team values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
    OCICommit($db_conn);
}

function deleteTeam() {
    global $db_conn;
    executePlainSQL("delete from team where teamname='" . $_POST['teamname'] . "'");
    OCICommit($db_conn);
}

function selection() {
    global $db_conn;

    $medalCount = $_POST['medalCount'];

    $result = executePlainSQL("select * from athletebelongs where medalcount >= $medalCount");
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

// function projection() {
//     global $db_conn;

//     $result = executePlainSQL("");

//     OCICommit($db_conn);
// }

function joinQuery() {
    global $db_conn;

    $name = $_POST['name'];

    $result = executePlainSQL("select athletebelongs.name, team.residency from athletebelongs, team where athletebelongs.teamname = team.teamname and athletebelongs.name like '%$name%'");
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

    $result = executePlainSQL('select max(medalcount), teamname from athletebelongs group by teamname');
    echo "
        <div class='card'>
            <div class='card-header bg-success text-white'>Search result: top individual medal count from each team</div>
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

// function nest() {
//     global $db_conn;

//     $result = executePlainSQL("");

//     OCICommit($db_conn);
// }

// function division() {
//     global $db_conn;

//     $result = executePlainSQL("");

//     OCICommit($db_conn);
// }

///////////////////////////////// End Handlers ////////////////////////////////////////////////////////////
function executeQuery($func) {
    if (connectToDB()) {
        $func();
        disconnectFromDB();
    }
}

?>