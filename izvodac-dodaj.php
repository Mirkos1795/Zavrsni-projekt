<?php
session_start();
if(!isset($_SESSION) ||  $_SESSION['tipKorisnika'] !=1)  {
  header("location: index.php");
}
/*
Kada se prvi puta učita stranica znači da smo došli s linka "Dodaj novi"
te se stranica učitava GET metodom
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "zajednicko/baza.class.php";
  $baza = new Baza();

  if(isset($_POST["naziv"]) &&  isset($_POST["slika"])){
    $naziv = urediUnos($_POST["naziv"]);
    $slika = urediUnos($_POST["slika"]);

    $sql = "INSERT INTO izvodac (`naziv`,  `slika`, `promjena`) 
    VALUES ('$naziv','$slika', NOW())";

    // echo $sql;

    $rezultat = $baza->insert($sql);

    if ($rezultat > 0) {
      header("location: izvodaci.php");
    } else {
      echo "Dogodila se greška kod komunikacije s bazom!</br>";
    }
  } else {
    echo ("Nisu popunjena sva polja!");
  }
}
else {
  // povezivanje na bazu
  require_once "zajednicko/baza.class.php";
  $baza = new Baza();

  // trebamo izvaditi albume iz baze podataka
  $sql="SELECT id, naziv, bio, slika, promjena FROM izvodac ORDER BY naziv;";
  $rezultat = $baza->upit($sql);
  $izvodaci=[]; # prazna lista u kojoj ću pamtiti albume
  if ($rezultat->num_rows>0) {
    while ($zapis=$rezultat->fetch_assoc()) {
      $izvodaci[]=$zapis;
    }
  }


}

function urediUnos($unos)
{
  $unos = trim($unos);
  $unos = stripslashes($unos);
  $unos = htmlspecialchars($unos);
  return $unos;
}

?>

<?php include("zajednicko/header.php"); ?>



<body>

<div class="w3-container w3-ios-yellow banner"> <h3> Dodavanje novog izvođača </h3> </div>
  
    

 <div class="w3-panel w3-border">
   <div class="boxcrud">
    <form action="izvodac-dodaj.php" <?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?> method="post" class=" w3-container w3-white">
     
    <div>
        <label  class="w3-text-black" for="naziv">Naziv:</label>
        <input class="w3-input w3-white" type="text1" name="naziv" placeholder="Naziv izvodaca"   pattern="^[a-zA-Z][a-zA-Z0-9-_.]{1,12}$" title="Dopušteno je od 2 do 12 znakova" required/>
      </div>
      
      <div>
        <label   class="w3-text-black" for="slika">Slika:</label>
        <input class="w3-input w3-white" type="text1" name="slika" placeholder="Link na sliku izvodaca" pattern="https?://.+" title="Potrebno je upisati ispravni URL"  required/>
     
      </div>
     
      <div>
        <label  class="w3-text-black"  for="bio">Biografija:</label>
        <input class="w3-input w3-white" type="text1" name="godina" placeholder="Biografija"  pattern="^[a-zA-Z][a-zA-Z0-9-_.]{5,50}$" title="Dopušteno je od 6 do 50 znakova" required/>
      </div>



      <button class="w3-button w3-green w3-margin-top"  type="submit">Dodaj</button>
    
</form>
<br>
</div>
  </div>
</div>


<?php include("zajednicko/footer.php"); ?>