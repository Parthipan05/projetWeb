<?php

/**
 * @file apropos.php
 * @brief Page de présentation du projet StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "À propos";
$description = "À propos de StationFinder - Projet universitaire L2 Informatique";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['apropos_titre'] ?></h1>

<section>
    <h2><?= $tr['apropos_projet'] ?></h2>
    <p><?= $tr['apropos_texte1'] ?></p>
    <p><?= $tr['apropos_texte2'] ?></p>
    <p><?= $tr['apropos_texte3'] ?></p>
</section>

<section>
    <h2><?= $tr['apropos_equipe'] ?></h2>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_nom'] ?></th>
                <th><?= $tr['col_formation'] ?></th>
                <th><?= $tr['col_universite'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PIRABAKARAN Parthipan</td>
                <td>L2 Informatique — S4</td>
                <td>CY Cergy Paris Université</td>
            </tr>
            <tr>
                <td>HANANE Sanaa</td>
                <td>L2 Informatique — S4</td>
                <td>CY Cergy Paris Université</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2><?= $tr['apropos_tech'] ?></h2>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_techno'] ?></th>
                <th><?= $tr['col_utilisation'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>HTML5</strong></td>
                <td><?= $tr['tech_html'] ?></td>
            </tr>
            <tr>
                <td><strong>CSS3</strong></td>
                <td><?= $tr['tech_css'] ?></td>
            </tr>
            <tr>
                <td><strong>PHP 8</strong></td>
                <td><?= $tr['tech_php'] ?></td>
            </tr>
            <tr>
                <td><strong>API Gouvernementale</strong></td>
                <td><?= $tr['tech_api_gouv'] ?></td>
            </tr>
            <tr>
                <td><strong>ipinfo.io</strong></td>
                <td><?= $tr['tech_ipinfo'] ?></td>
            </tr>
            <tr>
                <td><strong>ip2location.io</strong></td>
                <td><?= $tr['tech_ip2loc'] ?></td>
            </tr>
            <tr>
                <td><strong>Ghibli API</strong></td>
                <td><?= $tr['tech_ghibli'] ?></td>
            </tr>
        </tbody>
    </table>
</section>

<?php require_once("./includes/footer.inc.php"); ?>