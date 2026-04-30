<?php

/**
 * @file sources.php
 * @brief Page des sources et crédits de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Sources";
$description = "Sources des données et crédits de StationFinder";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>📚 Sources & Crédits</h1>

<section>
    <h2>⛽ Données carburants</h2>
    <p>
        Les prix des carburants sont issus de l'API officielle du gouvernement français,
        mise à disposition en open data et mise à jour en temps réel.
    </p>
    <table>
        <thead>
            <tr>
                <th>Source</th>
                <th>Format</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://data.economie.gouv.fr/explore/dataset/prix-des-carburants-en-france-flux-instantane-v2/" target="_blank">data.economie.gouv.fr</a></td>
                <td>JSON</td>
                <td>Prix des carburants en temps réel</td>
            </tr>
            <tr>
                <td><a href="https://www.prix-carburants.gouv.fr/rubrique/opendata/" target="_blank">prix-carburants.gouv.fr</a></td>
                <td>XML / CSV</td>
                <td>Données historiques des prix</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2>📍 Géolocalisation</h2>
    <p>
        La géolocalisation approximative des visiteurs est réalisée à partir
        de leur adresse IP via deux services externes.
    </p>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Format</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://ipinfo.io" target="_blank">ipinfo.io</a></td>
                <td>JSON</td>
                <td>Détection de la position sur la page d'accueil</td>
            </tr>
            <tr>
                <td><a href="https://www.ip2location.io" target="_blank">ip2location.io</a></td>
                <td>XML</td>
                <td>Démonstration de la maîtrise des flux XML (page Tech)</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2>🎬 API Ghibli</h2>
    <p>
        La page technique utilise l'API du studio japonais Ghibli pour
        démontrer la maîtrise des échanges JSON avec une API REST externe.
    </p>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Format</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://ghibliapi.vercel.app/" target="_blank">Ghibli API</a></td>
                <td>JSON</td>
                <td>Affichage d'un film aléatoire sur la page Tech</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2>🗺️ Données géographiques</h2>
    <p>
        Les données des régions, départements et communes de France
        sont issues des sources officielles de l'État français.
    </p>
    <table>
        <thead>
            <tr>
                <th>Source</th>
                <th>Format</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://www.data.gouv.fr" target="_blank">data.gouv.fr</a></td>
                <td>CSV</td>
                <td>Liste des régions et départements de France</td>
            </tr>
            <tr>
                <td><a href="http://education.ign.fr" target="_blank">IGN</a></td>
                <td>Image</td>
                <td>Carte administrative des régions de France</td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2>🛠️ Technologies</h2>
    <table>
        <thead>
            <tr>
                <th>Technologie</th>
                <th>Version</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>HTML</td>
                <td>5</td>
                <td>Structure et sémantique des pages</td>
            </tr>
            <tr>
                <td>CSS</td>
                <td>3</td>
                <td>Mise en forme et charte graphique</td>
            </tr>
            <tr>
                <td>PHP</td>
                <td>8</td>
                <td>Traitement serveur et appels API</td>
            </tr>
            <tr>
                <td>Hébergement</td>
                <td>—</td>
                <td><a href="https://www.alwaysdata.com" target="_blank">AlwaysData</a></td>
            </tr>
        </tbody>
    </table>
</section>

<?php require_once("./includes/footer.inc.php"); ?>