<?php

/**
 * @file confidentialite.php
 * @brief Page de politique de confidentialité de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre       = "Confidentialité";
$description = "Politique de confidentialité et gestion des cookies de StationFinder";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['conf_titre'] ?></h1>

<section>
    <h2><?= $tr['conf_intro'] ?></h2>
    <p><?= $tr['conf_intro_texte'] ?></p>
</section>

<section>
    <h2><?= $tr['conf_cookies'] ?></h2>
    <p><?= $tr['conf_cook_texte'] ?></p>
    <table>
        <thead>
            <tr>
                <th><?= $tr['conf_cookie_nom'] ?></th>
                <th><?= $tr['conf_cookie_cont'] ?></th>
                <th><?= $tr['conf_cookie_dur'] ?></th>
                <th><?= $tr['conf_cookie_use'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>style</strong></td>
                <td><?= $tr['conf_style_cont'] ?></td>
                <td><?= $tr['conf_style_dur'] ?></td>
                <td><?= $tr['conf_style_use'] ?></td>
            </tr>
            <tr>
                <td><strong>derniere_consultation</strong></td>
                <td><?= $tr['conf_last_cont'] ?></td>
                <td><?= $tr['conf_style_dur'] ?></td>
                <td><?= $tr['conf_last_use'] ?></td>
            </tr>
        </tbody>
    </table>
    <p><?= $tr['conf_limit'] ?></p>
</section>

<section>
    <h2><?= $tr['conf_donnees'] ?></h2>
    <p><?= $tr['conf_don_texte'] ?></p>
    <ul>
        <li><?= $tr['conf_don1'] ?></li>
        <li><?= $tr['conf_don2'] ?></li>
        <li><?= $tr['conf_don3'] ?></li>
        <li><?= $tr['conf_don4'] ?></li>
    </ul>
    <p>
        <?= $tr['conf_don_fin'] ?>
        <a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
            <?= $tr['nav_stats'] ?>
        </a>.
    </p>
</section>

<section>
    <h2><?= $tr['conf_tiers'] ?></h2>
    <p><?= $tr['conf_tiers_texte'] ?></p>
    <ul>
        <li><strong>ipinfo.io</strong></li>
        <li><strong>ip2location.io</strong></li>
        <li><strong>data.economie.gouv.fr</strong></li>
        <li><strong>ghibliapi.vercel.app</strong></li>
    </ul>
</section>

<section>
    <h2><?= $tr['conf_contact'] ?></h2>
    <p><?= $tr['conf_cont_texte'] ?></p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>