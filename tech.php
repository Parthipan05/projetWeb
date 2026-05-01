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

$titre       = "Page Tech";
$description = "Page technique - Film Ghibli et géolocalisation IP";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['tech_titre'] ?></h1>

<section>
    <h2><?= $tr['ghibli_titre'] ?></h2>

    <?php
    // 1. Récupération du flux JSON depuis l'API Ghibli
    $json = file_get_contents("https://ghibliapi.vercel.app/films");

    // 2. Vérification que l'API répond correctement
    if ($json === false) {
        echo "<p>" . $tr['ghibli_erreur'] . "</p>";
    } else {
        // 3. Décodage du JSON en tableau PHP associatif
        $films = json_decode($json, true);

        // 4. Sélection aléatoire d'un film
        $index_aleatoire = array_rand($films);

        // 5. Isolation du film sélectionné
        $film = $films[$index_aleatoire];
    ?>
        <h3><?= htmlspecialchars($film['title']) ?></h3>
        <p lang="ja"><?= htmlspecialchars($film['original_title']) ?></p>
        <p><strong><?= $tr['annee_sortie'] ?> :</strong> <?= htmlspecialchars($film['release_date']) ?></p>
        <p><strong><?= $tr['description'] ?> :</strong> <?= htmlspecialchars($film['description']) ?></p>

        <figure>
            <img src="<?= htmlspecialchars($film['image']) ?>"
                alt="<?= $tr['affiche'] ?> — <?= htmlspecialchars($film['title']) ?>">
            <figcaption><?= $tr['affiche'] ?> — <?= htmlspecialchars($film['title']) ?></figcaption>
        </figure>

        <figure>
            <img src="<?= htmlspecialchars($film['movie_banner']) ?>"
                alt="<?= $tr['banniere'] ?> — <?= htmlspecialchars($film['title']) ?>">
            <figcaption><?= $tr['banniere'] ?> — <?= htmlspecialchars($film['title']) ?></figcaption>
        </figure>

    <?php } ?>
</section>

<hr>

<section>
    <h2><?= $tr['ip_titre'] ?></h2>

    <?php
    // IP de test (IP de la fac)
    $ip_visiteur = '193.54.115.18';

    // Clé API ip2location
    $cle_api = "DAFEE48938FFF47E6537D03212A58A0D";

    // URL configurée en XML pour satisfaire l'exigence du sujet (format XML)
    $url = "https://api.ip2location.io/?key=" . $cle_api . "&ip=" . $ip_visiteur . "&format=xml";

    $contenu_api = @file_get_contents($url);

    if ($contenu_api !== false && str_contains($contenu_api, '<?xml')) {
        $xml = simplexml_load_string($contenu_api);

        // Extraction des balises XML de la réponse
        $ville  = (string)$xml->city_name;
        $region = (string)$xml->region_name;
        $pays   = (string)$xml->country_name;

        echo "<h3>" . $tr['ip_resultat'] . "</h3>";
        echo "<ul>";
        echo "<li><strong>" . $tr['ip_ville']  . " :</strong> " . htmlspecialchars($ville  ?: $tr['ip_inconnu']) . "</li>";
        echo "<li><strong>" . $tr['ip_region'] . " :</strong> " . htmlspecialchars($region ?: $tr['ip_inconnu']) . "</li>";
        echo "<li><strong>" . $tr['ip_pays']   . " :</strong> " . htmlspecialchars($pays   ?: $tr['ip_inconnu']) . "</li>";
        echo "</ul>";
    } else {
        echo "<p>" . $tr['ip_erreur'] . "</p>";
    }
    ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>