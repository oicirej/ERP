<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['Voornaam'])) {
    header('Location: Login.php');
    exit;
}
?>
<?php
global $conn;
$conn = null;

function connect()
{
  global $conn;
  //$servername="localhost";$username  ="root"; $password  =""; $dbname    ="us3/4",$port=3306;
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "us3/4";
  $port = 3306;
  $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
}

function disconnect()
{
  global $conn;
  mysqli_close($conn);
}

/**
 * @return bool|\mysqli_result
 */
function zoekKlanten()
{
  global $conn;
  // Als er een zoekterm is ingevuld
  if (isset($_GET['zoekterm'])) {
    $zoekterm = $_GET['zoekterm'];

    $sql = "SELECT * FROM klanten WHERE ID LIKE '%$zoekterm%' OR bedrijfsnaam LIKE '%$zoekterm%' OR voornaam LIKE '%$zoekterm%' OR tussenvoegsel LIKE '%$zoekterm%' OR achternaam LIKE '%$zoekterm%' OR functie LIKE '%$zoekterm%' OR email LIKE '%$zoekterm%' OR telefoonnummer LIKE '%$zoekterm%' OR adres LIKE '%$zoekterm%'";
    $result = mysqli_query($conn, $sql);
  } else {
    // Anders haal alle klanten op
    $sql = "SELECT ID, bedrijfsnaam, voornaam, tussenvoegsel, achternaam, functie, email, telefoonnummer, adres FROM klanten";
    $result = mysqli_query($conn, $sql);
  }
  return $result;
}

/**
 * @return bool|\mysqli_result
 */
function zoekMedewerkers()
{
  global $conn;
  if (isset($_GET['zoekterm'])) {
    $zoekterm = $_GET['zoekterm'];
    $sql = "SELECT * FROM medewerkers WHERE ID LIKE '%$zoekterm%' OR Voornaam LIKE '%$zoekterm%' OR Tussenvoegsel LIKE '%$zoekterm%' OR Achternaam LIKE '%$zoekterm%' OR Geboortedatum LIKE '%$zoekterm%' OR functie LIKE '%$zoekterm%' OR Werkemail LIKE '%$zoekterm%' OR telefoonnummer LIKE '%$zoekterm%' OR adres LIKE '%$zoekterm%'";
    $result = mysqli_query($conn, $sql);
  } else {
    // SQL query to retrieve data from the "medewerkers" table
    $sql = "SELECT ID, Voornaam, Tussenvoegsel, Achternaam, Geboortedatum, Functie, Werkmail, Kantoorruimte FROM medewerkers";
    $result = $conn->query($sql);
  }
  return $result;
}

/**
 * @return bool|\mysqli_result
 */
function laadOpdrachten()
{
  global $conn;
  // SQL query to retrieve data from the "medewerkers" table
  $sql = "SELECT ID, Klantnaam, Titel, Omschrijving, Aanvraagdatum, Kennis  FROM Opdrachten";
  $result = mysqli_query($conn, $sql);
  return $result;
}

/**
 * @return bool|\mysqli_result
 */
function laadWerkzaamheden()
{
  global $conn;
  // SQL query to retrieve data from the "werkzaamheden" table
  $sql = "SELECT ID, MedewerkerID, Opdrachten ID, , Aantal_Uren, Project_Naam, Omschrijving_Werkzaamheden FROM werkzaamheden";
  $result = mysqli_query($conn, $sql);
  return $result;
  }
  
  /**
   * @param \mysqli_result $result
   *
   * @return void
   */
  function toonKlanten(mysqli_result $result)
  {
    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr><th>ID</th><th>Bedrijfsnaam</th><th>Voornaam</th><th>Tussenvoegsel</th><th>Achternaam</th><th>Functie</th><th>Email</th><th>Telefoonnummer</th><th>Adres</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['bedrijfsnaam'] . "</td><td>" . $row['voornaam'] . "</td><td>" . $row['tussenvoegsel'] . "</td><td>" . $row['achternaam'] . "</td><td>" . $row['functie'] . "</td><td>" . $row['email'] . "</td><td>" . $row['telefoonnummer'] . "</td><td>" . $row['adres'] . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "Geen resultaten gevonden.";
    }
  }
  
  /**
   * @param \mysqli_result $result
   *
   * @return void
   */
  function toonMedewerkers(mysqli_result $result)
  {
    if ($result->num_rows > 0) {
      // Output data in a table
      echo "<table><tr><th>ID</th><th>Voornaam</th><th>Tussenvoegsel</th><th>Achternaam</th><th>Geboortedatum</th><th>Functie</th><th>Werkmail</th><th>Kantoorruimte</th></tr>";
      while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Voornaam"] . "</td><td>" . $row["Tussenvoegsel"] . "</td><td>" . $row["Achternaam"] . "</td><td>" . $row["Geboortedatum"] . "</td><td>" . $row["Functie"] . "</td><td>" . $row["Werkmail"] . "</td><td>" . $row["Kantoorruimte"] . "</td></tr>";
      }
      echo "</table>";
    } else {
      echo "No results found";
    }
  }
  
  function toonOpdrachten(mysqli_result $result)
  {
    echo "<table id='table1'>";
    // output table headers van html
    echo "<tr><th>ID</th><th>Klantnaam</th><th>Title</th><th>Omschrijving</th><th>Aanvraagdatum</th><th>Kennis</th></tr>";
    // output data om elke klommen gegevens uit te halen
    while ($row = $result->fetch_assoc()) {
      // output van de table kl
      echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Klantnaam"] . "</td><td>" . $row["Titel"] .
      "</td><td>" . $row["Omschrijving"] . "</td><td>" . $row["Aanvraagdatum"] . "</td><td>" . $row["Kennis"];
  }
  // end table
  echo "</table>";
  // als er geen rijen gevonden zijn, betekent dit dat er geen overeenkomsten zijn met de databases.
  // In dat geval wordt de string "No results" geprint.
  if (mysqli_num_rows($result) == 0) {
    echo "No results";
  }
}

function toonWerkzaamheden(mysqli_result $result)
{
  // Tabel met gegevens tonen
  echo "<table id='table'>";
  echo "<tr><th>ID</th><th>Voornaam</th><th>Achternaam</th><th>Uren</th><th>Project</th><th>Werkzaamheden</th></tr>";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Voornaam"] . "</td><td>" . $row["Achternaam"] . "</td><td>" . $row["Aantal_Uren"] . "</td><td>" . $row["Project_Naam"] . "</td><td>" . $row["Omschrijving_Werkzaamheden"] . "</td></tr>";
  }
  echo "</table>";
  // als er geen rijen gevonden zijn, betekent dit dat er geen overeenkomsten zijn met de databases.
  // In dat geval wordt de string "No results" geprint.
  if (mysqli_num_rows($result) == 0) {
    echo "No results";
  }
}
?>  