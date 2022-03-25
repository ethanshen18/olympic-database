<?php

$success = True; // keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in credential.php

// define all table named for easier batch processing
$tableNames = array(
    "COUNTRY",
    "TEAM",
    "ATHLETEBELONGS",
    "ONLINEAUDIENCE",
    "INPERSONAUDIENCE",
    "COMPETITION",
    "VOLUNTEER",
    "MEDIASTREAMINGPLATFORM",
    "SPONSOR",
    "REPRESENTS",
    "ATTENDS",
    "TICKET",
    "TICKETPRICE",
    "WATCHES",
    "STREAMS",
    "STREAMPRICE",
    "COMPETES",
    "ASSISTS",
    "ATHLETENEED",
    "FUNDS",
);

// include php functions
include "functions.php";

// html header
echo "
    <html>
        <head>
            <title>CPSC 304 Project</title>
            <link rel='stylesheet' href='style.css'>
        </head>

        <body>
            <div id='header'>Olympic Games Database</div>
            <div id='content'>
";

// GET and POST endpoints
if (isset($_POST['reset'])) executeQuery('resetTables');
if (isset($_POST['addCountry'])) executeQuery('addCountry');
if (isset($_POST['updateMedalCount'])) executeQuery('updateMedalCount');
if (isset($_POST['deleteCountry'])) executeQuery('deleteCountry');

// display all tables on load
if (connectToDB()) {
    foreach ($tableNames as $tableName) {

        $result = executePlainSQL("select * from " . $tableName);
        $numCols = oci_num_fields($result);

        echo "<div class='table-container'><b>" . $tableName . "</b><table>";

        // print table header
        echo "<tr>";
        for ($i = 1; $i <= $numCols; $i++) {
            echo "<td><b>" . oci_field_name($result, $i) . "</b></td>";
        }
        echo "</tr>";

        // print table content
        while (($row = oci_fetch_row($result)) != false) {
            echo "<tr>";
            for ($i = 0; $i < $numCols; $i++) {
                echo "<td>";
                echo $row[$i];
                echo "</td>";
            }
            echo "</tr>";
        }

        echo "</table></div>";
    }

    OCICommit($db_conn);
    disconnectFromDB();
}

// show reset database button
echo "
    <br><br>
    <form method='POST' action='index.php'>
        Click here to reset the database to initial state: 
        <input type='submit' value='Reset Database' name='reset'>
    </form>
    <br><br>
";

// country queries
echo "
    <hr>

    <div class='section-title'><b>Country Queries</b></div>

    <div class='query'>
        Add Country<br><br>
        <form method='POST' action='index.php'>
            Country Name: <input type='text' name='countryName'><br><br>
            Medal Count: <input type='text' name='medalCount'><br><br>
            <input type='submit' value='Add' name='addCountry'></p>
        </form>
    </div>

    <div class='query'>
        Update Medal Count<br><br>
        <form method='POST' action='index.php'>
            Country Name: <input type='text' name='countryName'><br><br>
            New Medal Count: <input type='text' name='medalCount'><br><br>
            <input type='submit' value='Update' name='updateMedalCount'></p>
        </form>
    </div>

    <div class='query'>
        Delete Country<br><br>
        <form method='POST' action='index.php'>
            Country Name: <input type='text' name='countryName'><br><br><br><br>
            <input type='submit' value='Delete' name='deleteCountry'></p>
        </form>
    </div>
";

// footer
echo "
            </div>
            <div id='footer'>© 2022 Group #13</div>
        </body>
    </html>
";

?>
