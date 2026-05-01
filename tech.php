<?php

/**
 * @file tech.php
 * @brief Page technique de StationFinder.
 * Démontre l'utilisation des APIs JSON (Ghibli) et XML (géolocalisation IP).
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Page Tech";
$description = "Page technique - Film Ghibli et géolocalisation IP";
require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>Page technique</h1>

<section>
    <h2>Film Ghibli aléatoire</h2>

    <?php
    // 1. On récupère les données brutes (flux JSON) depuis l'API Ghibli
    $json = file_get_contents("https://ghibliapi.vercel.app/films");

    // 2. Vérification que l'API répond correctement
    if ($json === false) {
        echo "<p>Impossible de contacter l'API Ghibli pour le moment.</p>";
    } else {
        // 3. On traduit le JSON en tableau PHP associatif
        $films = json_decode($json, true);

        // 4. array_rand() sélectionne un index au hasard
        $index_aleatoire = array_rand($films);

        // 5. On isole le film sélectionné
        $film = $films[$index_aleatoire];
    ?>

        <h3><?= htmlspecialchars($film['title']) ?></h3>
        <p lang="ja"><?= htmlspecialchars($film['original_title']) ?></p>
        <p><strong>Année de sortie :</strong> <?= htmlspecialchars($film['release_date']) ?></p>
        <p><strong>Description :</strong> <?= htmlspecialchars($film['description']) ?></p>

        <figure>
            <img src="<?= htmlspecialchars($film['image']) ?>"
                alt="Affiche du film <?= htmlspecialchars($film['title']) ?>">
            <figcaption>Affiche — <?= htmlspecialchars($film['title']) ?></figcaption>
        </figure>

        <figure>
            <img src="<?= htmlspecialchars($film['movie_banner']) ?>"
                alt="Bannière du film <?= htmlspecialchars($film['title']) ?>">
            <figcaption>Bannière — <?= htmlspecialchars($film['title']) ?></figcaption>
        </figure>

    <?php } ?>
</section>

<hr>

<section>
    <h2>Localisation par IP</h2>

    <?php
    // IP de test (IP de la fac)
    $ip_visiteur = '193.54.115.18';

    // Clé API ip2location
    $cle_api = "DAFEE48938FFF47E6537D03212A58A0D";

    // URL configurée en XML
    $url = "https://api.ip2location.io/?key=" . $cle_api . "&ip=" . $ip_visiteur . "&format=xml";

    $contenu_api = @file_get_contents($url);

    if ($contenu_api !== false && str_contains($contenu_api, '<?xml')) {
        $xml = simplexml_load_string($contenu_api);

        // Extraction des balises XML
        $ville  = (string)$xml->city_name;
        $region = (string)$xml->region_name;
        $pays   = (string)$xml->country_name;

        echo "<h3>Résultat</h3>";
        echo "<ul>";
        echo "<li><strong>Ville :</strong> "   . htmlspecialchars($ville  ?: "Inconnu") . "</li>";
        echo "<li><strong>Région :</strong> "  . htmlspecialchars($region ?: "Inconnu") . "</li>";
        echo "<li><strong>Pays :</strong> "    . htmlspecialchars($pays   ?: "Inconnu") . "</li>";
        echo "</ul>";
    } else {
        echo "<p>Impossible de contacter l'API de géolocalisation.</p>";
    }
    ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>