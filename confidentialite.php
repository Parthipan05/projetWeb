<?php

/**
 * @file confidentialite.php
 * @brief Page de politique de confidentialité de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Confidentialité";
$description = "Politique de confidentialité et gestion des cookies de StationFinder";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>🔒 Politique de confidentialité</h1>

<section>
    <h2>📋 Introduction</h2>
    <p>
        StationFinder est un projet universitaire développé dans le cadre de l'UE
        Développement Web de L2 Informatique à CY Cergy Paris Université.
        Cette page explique quelles données sont collectées lors de votre
        utilisation du site et comment elles sont utilisées.
    </p>
</section>

<section>
    <h2>🍪 Cookies utilisés</h2>
    <p>
        StationFinder utilise des cookies pour améliorer votre expérience de navigation.
        Aucun cookie publicitaire ou de tracking n'est utilisé.
    </p>
    <table>
        <thead>
            <tr>
                <th>Cookie</th>
                <th>Contenu</th>
                <th>Durée</th>
                <th>Utilisation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>style</strong></td>
                <td>standard ou alternatif</td>
                <td>30 jours</td>
                <td>Mémorisation du mode jour ou nuit choisi</td>
            </tr>
            <tr>
                <td><strong>derniere_consultation</strong></td>
                <td>Département et date</td>
                <td>30 jours</td>
                <td>Mémorisation de votre dernière recherche</td>
            </tr>
        </tbody>
    </table>
    <p>
        Ces cookies sont strictement limités à l'espace de StationFinder
        et ne sont pas partagés avec des tiers.
    </p>
</section>

<section>
    <h2>📊 Données collectées côté serveur</h2>
    <p>
        À chaque consultation d'une ville, les informations suivantes sont
        enregistrées anonymement dans un fichier CSV sur notre serveur :
    </p>
    <ul>
        <li>La <strong>date et l'heure</strong> de la consultation</li>
        <li>Le <strong>département</strong> consulté</li>
        <li>La <strong>ville</strong> consultée</li>
        <li>L'<strong>adresse IP</strong> du visiteur</li>
    </ul>
    <p>
        Ces données sont utilisées uniquement à des fins statistiques
        pour améliorer le service et sont affichées de manière agrégée
        dans la rubrique <a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Statistiques</a>.
    </p>
</section>

<section>
    <h2>🌍 Services tiers</h2>
    <p>
        StationFinder utilise des services externes pour fonctionner.
        Chacun de ces services dispose de sa propre politique de confidentialité.
    </p>
    <ul>
        <li><strong>ipinfo.io</strong> — géolocalisation approximative par adresse IP</li>
        <li><strong>ip2location.io</strong> — géolocalisation par adresse IP</li>
        <li><strong>data.economie.gouv.fr</strong> — prix des carburants en open data</li>
        <li><strong>ghibliapi.vercel.app</strong> — API films du studio Ghibli</li>
    </ul>
</section>

<section>
    <h2>✉️ Contact</h2>
    <p>
        Pour toute question concernant la confidentialité de vos données,
        vous pouvez contacter les auteurs du projet via l'université
        <strong>CY Cergy Paris Université</strong>.
    </p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>