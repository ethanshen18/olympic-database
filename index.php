<?php

$success = True; // keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in credential.php

// define all table named for easier batch processing
$tableNames = array(
    "COUNTRY"                   => "Country",
    "TEAM"                      => "Team",
    "ATHLETEBELONGS"            => "Athlete",
    "ONLINEAUDIENCE"            => "Online Audience",
    "INPERSONAUDIENCE"          => "In-Person Audience",
    "COMPETITION"               => "Competition",
    "VOLUNTEER"                 => "Volunteer",
    "MEDIASTREAMINGPLATFORM"    => "Media Streaming Platform",
    "SPONSOR"                   => "Sponsor",
    "REPRESENTS"                => "Represents",
    "ATTENDS"                   => "Attends",
    "TICKET"                    => "Ticket",
    "TICKETPRICE"               => "Ticket Price",
    "WATCHES"                   => "Watches",
    "STREAMS"                   => "Streams",
    "STREAMPRICE"               => "Stream Price",
    "COMPETES"                  => "Competes",
    "ASSISTS"                   => "Assists",
    "ATHLETENEED"               => "Athlete Need",
    "FUNDS"                     => "Funds",
);

// include php functions
include "functions.php";

// html header
echo "
    <html>
        <head>
            <!-- Required meta tags -->
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        
            <!-- Bootstrap CSS -->
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css' integrity='sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm' crossorigin='anonymous'>
        
            <title>CPSC 304 Project</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                } 
                
                body {
                    font-family: 'Verdana', sans-serif;
                }
                
                #content {
                    margin: 20px;
                }
                
                table {
                    font-size: 12px;
                }            
            </style>
        </head>

        <body>
            <nav class='navbar navbar-dark bg-dark text-white justify-content-between py-3'>
                <a class='navbar-brand' href='#'>
                    <img src='logo.png' height='30' class='d-inline-block align-top'> Olympic Games Database
                </a>
                <form class='form-inline my-2 my-lg-0' method='POST' action='index.php'>
                    <input class='btn btn-outline-warning my-2 my-sm-0' type='submit' value='Reset Database' name='reset'>
                </form>
            </nav>
            <div id='content'>
";

// GET and POST endpoints
if (isset($_POST['reset'])) executeQuery('resetTables');
if (isset($_POST['addCountry'])) executeQuery('addCountry');
if (isset($_POST['updateMedalCount'])) executeQuery('updateMedalCount');
if (isset($_POST['deleteCountry'])) executeQuery('deleteCountry');
if (isset($_POST['addAthletebelongs'])) executeQuery('addAthletebelongs');
if (isset($_POST['updateAthleteMedalCount'])) executeQuery('updateAthleteMedalCount');
if (isset($_POST['deleteAthelete'])) executeQuery('deleteAthelete');
if (isset($_POST['addTeam'])) executeQuery('addTeam');
if (isset($_POST['deleteTeam'])) executeQuery('deleteTeam');

// display all tables on load
if (connectToDB()) {

    echo "<div class='card-columns'>";

    foreach ($tableNames as $key => $tableName) {

        $result = executePlainSQL("select * from $key");
        $numCols = oci_num_fields($result);

        echo "
            <div class='card'>
                <div class='card-header bg-dark text-white'>$tableName</div>
                <div class='card-body'>
        ";

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
        echo "</div>";
        echo "</div>";
    }

    echo "</div>";

    OCICommit($db_conn);
    disconnectFromDB();
}

// country queries
echo "
    <div class='card text-center'>
        <div class='card-header bg-dark text-white'>Country Queries</div>
        <div class='card-body'>
            <div class='row'>
                <div class='col'>
                    <h5 class='card-title'>Add country</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='country name' name='countryName'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='medal count' name='medalCount'>
                        </div>
                        <input type='submit' value='Add' name='addCountry' class='btn btn-primary'>
                    </form>
                </div>

                <div class='col'>
                    <h5 class='card-title'>Update medal count</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='country name' name='countryName'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='new medal count' name='medalCount'>
                        </div>
                        <input type='submit' value='Update' name='updateMedalCount' class='btn btn-primary'>
                    </form>
                </div>

                <div class='col'>
                    <h5 class='card-title'>Delete country</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='country name' name='countryName'>
                        </div>
                        <br><br>
                        <input type='submit' value='Delete' name='deleteCountry' class='btn btn-primary'>
                    </form>
                </div>
            </div>
        </div>
    </div>
";

// athlete queries
echo "
    <div class='card text-center'>
        <div class='card-header bg-dark text-white'>Athlete Queries</div>                   
        <div class='card-body'>
            <div class='row'>
                <div class='col'>
                    <h5 class='card-title'>Add athlete</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='athlete id' name='athleteid'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='athlete name' name='athleteName'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='competition' name='athletecompetition'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='athlete medal count' name='athletemedalcount'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='teamname' name='athleteteamname'>
                        </div>
                        <input type='submit' value='Add' name='addAthletebelongs' class='btn btn-primary'>
                    </form>
                </div>

                <div class='col'>
                    <h5 class='card-title'>Update medal count</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='athlete ID' name='athleteid'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='new medal count' name='athletemedalcount'>
                        </div>
                        <input type='submit' value='Update' name='updateAthleteMedalCount' class='btn btn-primary'>
                    </form>
                </div>

                <div class='col'>
                    <h5 class='card-title'>Delete athlete</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='athlete ID' name='athleteid'>
                        </div>
                        <br><br>
                        <input type='submit' value='Delete' name='deleteAthelete' class='btn btn-primary'>
                    </form>
                </div>
            </div>
        </div>
    </div>
";

// team queries
echo "
    <div class='card text-center'>
        <div class='card-header bg-dark text-white'>Team Queries</div>                   
        <div class='card-body'>
            <div class='row'>
                <div class='col'>
                    <h5 class='card-title'>Add Team</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='teamname' name='teamname'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='team size' name='teamsize'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='residency address' name='residency'>
                        </div>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='Country belongs' name='countryname'>
                        </div>
                        <input type='submit' value='Add' name='addTeam' class='btn btn-primary'>
                    </form>
                </div>

                <div class='col'>
                    <h5 class='card-title'>Delete Team</h5>
                    <form method='POST' action='index.php'>
                        <div class='form-group'>
                            <input type='text' class='form-control' placeholder='Team name' name='teamname'>
                        </div>
                        <br><br>
                        <input type='submit' value='Delete' name='deleteTeam' class='btn btn-primary'>
                    </form>
                </div>
            </div>
        </div>
    </div>
";





// footer
echo "
            </div>
            <nav class='navbar navbar-dark bg-dark text-white justify-content-center py-5'>© 2022 Group #13</nav>
        </body>
    </html>
";

?>
