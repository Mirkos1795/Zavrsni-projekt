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

  if(isset($_POST["naziv"]) && isset($_POST["slika"]) &&  isset($_POST["tekst"]) && isset($_POST["link"]) && isset($_POST["album"]) && isset($_POST["zanr"])){
    $naziv = urediUnos($_POST["naziv"]);
    $slika = urediUnos($_POST["slika"]);
    $tekst = urediUnos($_POST["tekst"]);
    $link = urediUnos($_POST["link"]);
    $album_id = urediUnos($_POST["album"]);
    $zanr_id = urediUnos($_POST["zanr"]);

    $sql = "INSERT INTO pjesma(`naziv`, `tekst`, `slika`, `link`, `album_id`, `zanr_id`, `promjena`) 
    VALUES ('$naziv','$tekst','$slika','$link','$album_id','$zanr_id', NOW())";

    // echo $sql;

    $rezultat = $baza->insert($sql);

    if ($rezultat > 0) {
      header("location: pjesme.php");
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
  $sql="SELECT id, naziv FROM album ORDER BY naziv;";
  $rezultat = $baza->upit($sql);
  $albumi=[]; # prazna lista u kojoj ću pamtiti albume
  if ($rezultat->num_rows>0) {
    while ($zapis=$rezultat->fetch_assoc()) {
      $albumi[]=$zapis;
    }
  }

  $sql="SELECT id, naziv FROM zanr ORDER BY naziv;";
  $rezultat = $baza->upit($sql);
  $zanrovi=[]; # prazna lista u kojoj ću pamtiti zanrove
  if ($rezultat->num_rows>0) {
    while ($zapis=$rezultat->fetch_assoc()) {
      $zanrovi[]=$zapis;
    }
  }

  $sql="SELECT id, naziv FROM izvodac ORDER BY naziv;";
  $rezultat = $baza->upit($sql);
  $izvodaci=[]; # prazna lista u kojoj ću pamtiti izvodace
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

<div class="w3-container w3-ios-yellow banner"> <h3> Dodavanje nove pjesme </h3> </div>
  
  <div class="w3-panel w3-border">
     <div class="boxcrud">
    <form action="pjesma-dodaj.php" <?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?> method="post" class="w3-container w3-white w3-mobile w3-padding-top">
      <div>
        <label  class="w3-text-black" for="naziv">Naziv:</label>
        <input class="w3-input w3-white" type="text" name="naziv" placeholder="Naziv pjesme" pattern="^[a-zA-Z][a-zA-Z0-9-_.]{1,12}$" title="Dopušteno je od 2 do 12 znakova" required/>
      </div>
      <div>
        <label  class="w3-text-black" for="opis">Tekst:</label>
        <textarea class="w3-input w3-white" name="tekst" cols="30" rows="10" placeholder="Tekst pjesme" pattern="^[a-zA-Z][a-zA-Z0-9-_.]{5,50}$" title="Dopušteno je od 6 do 50 znakova" required></textarea>
      </div>
      <div>
        <label  class="w3-text-black" for="slika">Slika:</label>
        <input class="w3-input w3-white" type="text" name="slika" placeholder="Link na sliku izvođača" pattern="https?://.+" title="Potrebno je upisati ispravni URL" required />
      </div>
      <div>
        <label class="w3-text-black"  for="link">Link:</label>
        <input class="w3-input w3-white" type="text" name="link" placeholder="Linka na pjesmu" pattern="https?://.+" title="Potrebno je upisati ispravni URL" required/>
      </div>
      <div>
        <br>
        <br>
        <!-- dodavanje albuma  i zanra preko padajuće liste -->
        <label class="w3-text-black" for="album">Album:</label>
        <select name="album" >
          <?php foreach($albumi as $album): ?>
            <option  value="<?php echo $album['id'];?>"><?php echo $album['naziv'];?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <br>
        <br>

        <label class="w3-text-black" for="zanr">Žanr:</label>
        <select name="zanr">
          <?php foreach($zanrovi as $zanr): ?>
            <option  value="<?php echo $zanr['id'];?>"><?php echo $zanr['naziv'];  ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      

      <button class="w3-button w3-green w3-margin-top" type="submit">Dodaj</button>
    </form>
    <br>
          </div>
          </div>
          </div>
 

<?php include("zajednicko/footer.php"); ?>