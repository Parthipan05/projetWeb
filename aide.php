<?php

/**
 * @file aide.php
 * @brief Page d'aide de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre       = "Aide";
$description = "Aide et guide d'utilisation de StationFinder";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['aide_titre'] ?></h1>

<section>
    <h2><?= $tr['aide_region'] ?></h2>
    <p><?= $tr['aide_region_texte'] ?></p>
    <ol>
        <li><?= $tr['aide_etape1'] ?></li>
        <li><?= $tr['aide_etape2'] ?></li>
        <li><?= $tr['aide_etape3'] ?></li>
        <li><?= $tr['aide_etape4'] ?></li>
    </ol>
</section>

<section>
    <h2><?= $tr['aide_geoloc'] ?></h2>
    <p><?= $tr['aide_geoloc_texte'] ?></p>
    <p><?= $tr['aide_geoloc_avert'] ?></p>
</section>

<section>
    <h2><?= $tr['aide_filtrer'] ?></h2>
    <p><?= $tr['aide_filtrer_texte'] ?></p>
    <ul>
        <li><strong>SP95</strong> — Sans plomb 95</li>
        <li><strong>SP98</strong> — Sans plomb 98</li>
        <li><strong>Gazole</strong> — Diesel</li>
        <li><strong>E10</strong> — Éthanol 10%</li>
        <li><strong>GPL</strong> — Gaz de pétrole liquéfié</li>
    </ul>
    <p><?= $tr['aide_trier'] ?></p>
</section>

<section>
    <h2><?= $tr['aide_mode'] ?></h2>
    <p><?= $tr['aide_mode_texte'] ?></p>
</section>

<section>
    <h2><?= $tr['aide_cookie'] ?></h2>
    <p><?= $tr['aide_cookie_texte'] ?></p>
</section>

<section>
    <h2><?= $tr['aide_faq'] ?></h2>

    <h3><?= $tr['aide_q1'] ?></h3>
    <p><?= $tr['aide_r1'] ?></p>

    <h3><?= $tr['aide_q2'] ?></h3>
    <p><?= $tr['aide_r2'] ?></p>

    <h3><?= $tr['aide_q3'] ?></h3>
    <p><?= $tr['aide_r3'] ?></p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>