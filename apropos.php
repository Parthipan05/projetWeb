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
?>

<h1>À propos de StationFinder</h1>

<section>
    <h2>🚀 Le projet</h2>
    <p>
        <strong>StationFinder</strong> est un site web développé dans le cadre de l'UE
        <strong>Développement Web</strong> de la licence L2 Informatique (semestre 4)
        à <strong>CY Cergy Paris Université</strong>.
    </p>
    <p>
        Face à la hausse des prix des carburants, nous avons conçu un outil permettant
        aux automobilistes de trouver rapidement et facilement les stations-service
        les moins chères près de chez eux, en temps réel.
    </p>
    <p>
        Les données sont issues directement des sources officielles du gouvernement
        français et sont mises à jour en continu.
    </p>
</section>

<section>
    <h2>👥 L'équipe</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Formation</th>
                <th>Université</th>
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
    <h2>🛠️ Technologies utilisées</h2>
    <table>
        <thead>
            <tr>
                <th>Technologie</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>HTML5</strong></td>
                <td>Structure et sémantique des pages</td>
            </tr>
            <tr>
                <td><strong>CSS3</strong></td>
                <td>Mise en forme, responsive design, mode jour/nuit</td>
            </tr>
            <tr>
                <td><strong>PHP 8</strong></td>
                <td>Traitement côté serveur, appels API, gestion des cookies</td>
            </tr>
            <tr>
                <td><strong>API Gouvernementale</strong></td>
                <td>Prix des carburants en temps réel (data.economie.gouv.fr)</td>
            </tr>
            <tr>
                <td><strong>ipinfo.io</strong></td>
                <td>Géolocalisation approximative par adresse IP (JSON)</td>
            </tr>
            <tr>
                <td><strong>ip2location.io</strong></td>
                <td>Géolocalisation par adresse IP (XML)</td>
            </tr>
            <tr>
                <td><strong>Ghibli API</strong></td>
                <td>Démonstration de la maîtrise des API REST JSON</td>
            </tr>
        </tbody>
    </table>
</section>

<?php require_once("./includes/footer.inc.php"); ?>