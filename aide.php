<?php

/**
 * @file aide.php
 * @brief Page d'aide de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Aide";
$description = "Aide et guide d'utilisation de StationFinder";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>❓ Aide & Guide d'utilisation</h1>

<section>
    <h2>🗺️ Rechercher par région</h2>
    <p>
        Sur la page d'accueil, une carte interactive de France vous permet de
        sélectionner votre région en un seul clic. Survolez les régions pour
        voir leur nom apparaître, puis cliquez pour accéder à la sélection
        du département.
    </p>
    <ol>
        <li>Cliquez sur votre <strong>région</strong> sur la carte</li>
        <li>Choisissez votre <strong>département</strong> dans la liste déroulante</li>
        <li>Choisissez votre <strong>ville</strong> dans la liste déroulante</li>
        <li>Cliquez sur <strong>"Voir les stations ⛽"</strong></li>
    </ol>
</section>

<section>
    <h2>📍 Rechercher par géolocalisation</h2>
    <p>
        StationFinder peut détecter automatiquement votre position approximative
        à partir de votre adresse IP. Si votre position est détectée, un bouton
        <strong>"Voir les stations près de moi"</strong> apparaît directement
        sur la page d'accueil.
    </p>
    <p>
        ⚠️ La géolocalisation par adresse IP est une estimation et peut ne pas
        refléter votre position exacte.
    </p>
</section>

<section>
    <h2>⛽ Filtrer les carburants</h2>
    <p>
        Sur la page des résultats, vous pouvez personnaliser l'affichage
        en filtrant les carburants qui vous intéressent :
    </p>
    <ul>
        <li><strong>SP95</strong> — Sans plomb 95</li>
        <li><strong>SP98</strong> — Sans plomb 98</li>
        <li><strong>Gazole</strong> — Diesel</li>
        <li><strong>E10</strong> — Éthanol 10%</li>
        <li><strong>GPL</strong> — Gaz de pétrole liquéfié</li>
    </ul>
    <p>
        Vous pouvez également <strong>trier les stations par prix</strong>
        croissant ou décroissant pour trouver la moins chère rapidement.
    </p>
</section>

<section>
    <h2>🌙 Mode jour / Mode nuit</h2>
    <p>
        StationFinder propose deux chartes graphiques pour le confort de lecture.
        Cliquez sur <strong>🌙 Mode Nuit</strong> ou <strong>☀️ Mode Jour</strong>
        dans la barre en haut de chaque page. Votre préférence est mémorisée
        automatiquement lors de vos prochaines visites grâce à un cookie.
    </p>
</section>

<section>
    <h2>🕐 Dernière consultation</h2>
    <p>
        StationFinder mémorise automatiquement le dernier département que vous
        avez consulté. Cette information est affichée en haut de la page
        des résultats pour vous permettre de retrouver rapidement vos
        recherches précédentes.
    </p>
</section>

<section>
    <h2>❓ Questions fréquentes</h2>

    <h3>Les prix affichés sont-ils en temps réel ?</h3>
    <p>
        Oui. Les prix sont récupérés directement depuis l'API officielle
        du gouvernement français et reflètent les dernières mises à jour
        des stations-service.
    </p>

    <h3>Pourquoi certaines stations n'affichent pas tous les carburants ?</h3>
    <p>
        Certaines stations ne proposent pas tous les types de carburants
        ou n'ont pas encore mis à jour leurs tarifs dans la base de données
        gouvernementale.
    </p>

    <h3>Ma position détectée est incorrecte, que faire ?</h3>
    <p>
        La géolocalisation par IP est une estimation. Utilisez simplement
        la carte interactive pour sélectionner manuellement votre région,
        département et ville.
    </p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>