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
    <h2>Localisation par IP</h2>

    <?php
    // IP de la fac imposée pour le test
    $ip_visiteur = '193.54.115.18';

    // CLÉ API
    $cle_api = "DAFEE48938FFF47E6537D03212A58A0D";

    // URL configurée en XML
    $url = "https://api.ip2location.io/?key=" . $cle_api . "&ip=" . $ip_visiteur . "&format=xml";

    $contenu_api = @file_get_contents($url);

    if ($contenu_api !== false && str_contains($contenu_api, '<?xml')) {
        $xml = simplexml_load_string($contenu_api);

        // Extraction des balises XML de IP2Location
        $ville  = (string)$xml->city_name;
        $region = (string)$xml->region_name;
        $pays   = (string)$xml->country_name;

        // Affichage propre pour le prof
        echo "<h3>Résulat</h3>";
        echo "<ul>";
        echo "<li><strong>Ville :</strong> " . ($ville ?: "Inconnu") . "</li>";
        echo "<li><strong>Région :</strong> " . ($region ?: "Inconnu") . "</li>";
        echo "<li><strong>Pays :</strong> " . ($pays ?: "Inconnu") . "</li>";
        echo "</ul>";
    } else {
        echo "<p>erreur</p>";
    }
    ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>