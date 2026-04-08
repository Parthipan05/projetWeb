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
    // 1. On récupère l'IP du visiteur
    $ip_visiteur = $_SERVER['REMOTE_ADDR'];

    // ASTUCE DE PRO : On force une vraie IP si on est en train de tester en local
    if ($ip_visiteur == '::1' || $ip_visiteur == '127.0.0.1') {
        $ip_visiteur = '193.54.115.235'; // IP d'exemple du sujet
    }

    // 2. On prépare l'URL de l'API avec ta clé secrète et l'IP du visiteur
    $cle_api = "TA_CLE_SECRETE_A_METTRE_ICI";
    $url = "https://api.whatismyip.com/ip-address-lookup.php?key=" . $cle_api . "&input=" . $ip_visiteur . "&output=xml";

    // 3. On demande à PHP de charger et lire le fichier XML
    // La fonction simplexml_load_file() est magique : elle transforme le XML en un objet PHP facile à lire
    // Le "@" devant permet de cacher les vilaines erreurs PHP si l'API est en panne ou si ta clé est fausse
    $donnees_geo = @simplexml_load_file($url);

    // 4. On vérifie qu'on a bien reçu une réponse avant d'afficher
    if ($donnees_geo !== false) {
        // En XML, on accède aux données avec la flèche "->" (contrairement au JSON où on utilisait des crochets)
        $ville = $donnees_geo->City;
        $region = $donnees_geo->Region;
        $pays = $donnees_geo->Country;
    ?>

        <p>Ton adresse IP (<?= htmlspecialchars($ip_visiteur) ?>) nous indique que tu es probablement près de :</p>
        <ul>
            <li><strong>Ville :</strong> <?= htmlspecialchars($ville) ?></li>
            <li><strong>Région :</strong> <?= htmlspecialchars($region) ?></li>
            <li><strong>Pays :</strong> <?= htmlspecialchars($pays) ?></li>
        </ul>

    <?php
    } else {
        // Message de secours si l'API ne répond pas
        echo "<p>Impossible de te géolocaliser pour le moment.</p>";
    }
    ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>