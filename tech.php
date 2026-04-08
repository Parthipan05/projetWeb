<?php
$titre = "Page Tech";
$description = "Page technique - Film Ghibli et géolocalisation IP";
require_once("./includes/header.inc.php");
?>


<section>
    <h2> Film Ghibli aléatoire</h2>

    <?php
    // 1. On récupère les données brutes (flux JSON) directement depuis l'URL de l'API
    $json = file_get_contents("https://ghibliapi.vercel.app/films");

    // 2. On traduit ce texte JSON en un tableau PHP compréhensible par notre serveur
    // Le paramètre "true" est indispensable pour obtenir un tableau associatif (array)
    $films = json_decode($json, true);

    // 3. array_rand() sélectionne une "case" (un index) au hasard dans notre tableau de films
    $index_aleatoire = array_rand($films);


    // 4. On isole le film gagnant du tirage au sort dans une variable $film pour l'afficher plus bas
    $film = $films[$index_aleatoire];
    ?>

    <h3><?= htmlspecialchars($film['title']) ?> </h3>
    <p lang="ja"><?= htmlspecialchars($film['original_title']) ?></p>
    <p><strong>Année de sortie :</strong> <?= htmlspecialchars($film['release_date']) ?></p>
    <p><strong>Description :</strong> <?= htmlspecialchars($film['description']) ?></p>

    <figure>
        <img src="<?= htmlspecialchars($film['image']) ?>" alt="Affiche du film <?= htmlspecialchars($film['title']) ?>">
        <figcaption>Affiche — <?= htmlspecialchars($film['title']) ?></figcaption>
    </figure>

    <figure>
        <img src="<?= htmlspecialchars($film['movie_banner']) ?>"
            alt="Bannière du film <?= htmlspecialchars($film['title']) ?>">
        <figcaption>Bannière — <?= htmlspecialchars($film['title']) ?></figcaption>
    </figure>
</section>


<hr>

<section>
    <h2>Localisation</h2>

    <?php
    $ip_visiteur = $_SERVER['REMOTE_ADDR'];
    if ($ip_visiteur == '::1' || $ip_visiteur == '127.0.0.1') {
        $ip_visiteur = '193.54.115.235';
    }

    $cle_api = "a985b6bfd4ae239a8b1b11a341009746";
    $url = "https://api.whatismyip.com/ip-address-lookup.php?key=" . $cle_api . "&input=" . $ip_visiteur . "&output=xml";

    $contenu_brut = file_get_contents($url);
    $donnees_geo = simplexml_load_string($contenu_brut);

    if ($donnees_geo !== false) {
        $ville  = $donnees_geo->City;
        $region = $donnees_geo->Region;
        $pays   = $donnees_geo->Country;
    ?>
        <p>Ton adresse IP (<?= htmlspecialchars($ip_visiteur) ?>) nous indique que tu es probablement près de :</p>
        <ul>
            <li><strong>Ville :</strong> <?= htmlspecialchars($ville)  ?></li>
            <li><strong>Région :</strong> <?= htmlspecialchars($region) ?></li>
            <li><strong>Pays :</strong> <?= htmlspecialchars($pays)   ?></li>
        </ul>
    <?php
    } else {
        echo "<p>Impossible de te géolocaliser pour le moment.</p>";
        echo "<pre>Réponse brute de l'API : " . htmlspecialchars($contenu_brut) . "</pre>";
    }
    ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>