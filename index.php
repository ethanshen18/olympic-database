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
if (isset($_POST['addAthlete'])) executeQuery('addAthlete');
if (isset($_POST['updateAthleteMedalCount'])) executeQuery('updateAthleteMedalCount');
if (isset($_POST['deleteAthelete'])) executeQuery('deleteAthelete');
if (isset($_POST['addTeam'])) executeQuery('addTeam');
if (isset($_POST['deleteTeam'])) executeQuery('deleteTeam');

// begin accordian
echo "
    <div class='card' id='group'>
        <div class='card-header'>
            <ul class='nav nav-pills card-header-pills'>
                <li class='nav-item'>
                    <a class='nav-link' data-bs-toggle='collapse' href='#editCountries' role='button' aria-expanded='false'>Edit Countries</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' data-bs-toggle='collapse' href='#editTeams' role='button' aria-expanded='false'>Edit Teams</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' data-bs-toggle='collapse' href='#editAthletes' role='button' aria-expanded='false'>Edit Athletes</a>
                </li>
                <li class='nav-item'>
                    <a class='nav-link' data-bs-toggle='collapse' href='#search' role='button' aria-expanded='false'>Search</a>
                </li>
            </ul>
        </div>
        <div class='accordion-group'>
";

// country queries
echo "
    <div class='collapse' id='editCountries' data-bs-parent='#group'>
        <div class='card text-center border-0'>
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
                            <input type='submit' value='Delete' name='deleteCountry' class='btn btn-primary'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
";

// team queries
echo "
    <div class='collapse' id='editTeams' data-bs-parent='#group'>
        <div class='card text-center border-0'>
            <div class='card-body'>
                <div class='row'>
                    <div class='col'>
                        <h5 class='card-title'>Add team</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='team name' name='teamname'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='team size' name='teamsize'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='residency building' name='residency'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='country name' name='countryname'>
                            </div>
                            <input type='submit' value='Add' name='addTeam' class='btn btn-primary'>
                        </form>
                    </div>

                    <div class='col'>
                        <h5 class='card-title'>Delete team</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='team name' name='teamname'>
                            </div>
                            <input type='submit' value='Delete' name='deleteTeam' class='btn btn-primary'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
";

// athlete queries
echo "
    <div class='collapse' id='editAthletes' data-bs-parent='#group'>
        <div class='card text-center border-0'>
            <div class='card-body'>
                <div class='row'>
                    <div class='col'>
                        <h5 class='card-title'>Add athlete</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='athlete ID' name='athleteid'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='name' name='athleteName'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='age' name='athleteAge'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='competition' name='athletecompetition'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='medal count' name='athletemedalcount'>
                            </div>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='team name' name='athleteteamname'>
                            </div>
                            <input type='submit' value='Add' name='addAthlete' class='btn btn-primary'>
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
                            <input type='submit' value='Delete' name='deleteAthelete' class='btn btn-primary'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
";

// search queries
echo "
    <div class='collapse' id='search' data-bs-parent='#group'>
        <div class='card text-center border-0'>
            <div class='card-body'>
                <div class='row'>
                    <div class='col'>
                        <h5 class='card-title'>Find athletes by medal count</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='minimum medal count' name='medalCount'>
                            </div>
                            <input type='submit' value='Find' name='selection' class='btn btn-primary'>
                        </form>
                    </div>

                    <div class='col'>
                        <h5 class='card-title'>Find athlete residency</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='athlete name' name='name'>
                            </div>
                            <input type='submit' value='Find' name='join' class='btn btn-primary'>
                        </form>
                    </div>

                    <div class='col'>
                        <h5 class='card-title'>Find top athletes by country</h5>
                        <form method='POST' action='index.php'>
                            <input type='submit' value='Find' name='aggregation' class='btn btn-primary'>
                        </form>
                    </div>
                </div>

                <br><br>

                <div class='row'>
                    <div class='col'>
                        <h5 class='card-title'>Find average  youngest athlete age by country</h5>
                        <form method='POST' action='index.php'>
                            <input type='submit' value='Find' name='nested' class='btn btn-primary'>
                        </form>
                    </div>

                    <div class='col'>
                        <h5 class='card-title'>Show athlete details</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='projectionName' name='name'>
                                <label class='form-check-label' for='projectionName'>Athlete Name</label>
                            </div>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='projectionCompetition' name='competition'>
                                <label class='form-check-label' for='projectionCompetition'>Competition</label>
                            </div>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='projectionMedalCount' name='medalCount'>
                                <label class='form-check-label' for='projectionMedalCount'>Medal Count</label>
                            </div>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='projectionTeamName' name='team'>
                                <label class='form-check-label' for='projectionTeamName'>Team Name</label>
                            </div>
                            <input type='submit' value='Show' name='projection' class='btn btn-primary'>
                        </form>
                    </div>

                    <div class='col'>
                        <h5 class='card-title'>Find athlete by competitions</h5>
                        <form method='POST' action='index.php'>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='divisionSkating' name='skating'>
                                <label class='form-check-label' for='divisionSkating'>Skating</label>
                            </div>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='divisionHockey' name='hockey'>
                                <label class='form-check-label' for='divisionHockey'>Hockey</label>
                            </div>
                            <div class='form-group'>
                                <input type='checkbox' class='form-check-input' id='divisionSkiing' name='skiing'>
                                <label class='form-check-label' for='divisionSkiing'>Skiing</label>
                            </div>
                            <input type='submit' value='Find' name='division' class='btn btn-primary'>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
";

// end accordian
echo "
        </div>
    </div>
    <br>
";

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

// footer
echo "
            </div>
            <nav class='navbar navbar-dark bg-dark text-white justify-content-center py-5'>© 2022 Group #13</nav>
            <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
        </body>
    </html>
";

?>
