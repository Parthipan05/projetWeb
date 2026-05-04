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
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['sources_titre'] ?></h1>

<section>
    <h2><?= $tr['sources_carburants'] ?></h2>
    <p><?= $tr['sources_carb_texte'] ?></p>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_source'] ?></th>
                <th><?= $tr['col_format'] ?></th>
                <th><?= $tr['col_role'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://data.economie.gouv.fr/explore/dataset/prix-des-carburants-en-france-flux-instantane-v2/" target="_blank">data.economie.gouv.fr</a></td>
                <td>JSON</td>
                <td><?= $tr['src_carb_reel'] ?></td>
            </tr>
            <tr>
                <td><a href="https://www.prix-carburants.gouv.fr/rubrique/opendata/" target="_blank">prix-carburants.gouv.fr</a></td>
                <td>XML / CSV</td>
                <td><?= $tr['src_carb_histo'] ?></td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2><?= $tr['sources_geoloc'] ?></h2>
    <p><?= $tr['sources_geo_texte'] ?></p>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_service'] ?></th>
                <th><?= $tr['col_format'] ?></th>
                <th><?= $tr['col_role'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://ipinfo.io" target="_blank">ipinfo.io</a></td>
                <td>JSON</td>
                <td><?= $tr['src_ip_accueil'] ?></td>
            </tr>
            <tr>
                <td><a href="https://www.ip2location.io" target="_blank">ip2location.io</a></td>
                <td>XML</td>
                <td><?= $tr['src_ip_xml'] ?></td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2><?= $tr['sources_ghibli'] ?></h2>
    <p><?= $tr['sources_ghib_texte'] ?></p>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_service'] ?></th>
                <th><?= $tr['col_format'] ?></th>
                <th><?= $tr['col_role'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://ghibliapi.vercel.app/" target="_blank">Ghibli API</a></td>
                <td>JSON</td>
                <td><?= $tr['src_ghibli'] ?></td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2><?= $tr['sources_geo_data'] ?></h2>
    <p><?= $tr['sources_geo_d_texte'] ?></p>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_source'] ?></th>
                <th><?= $tr['col_format'] ?></th>
                <th><?= $tr['col_role'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="https://www.data.gouv.fr" target="_blank">data.gouv.fr</a></td>
                <td>CSV</td>
                <td><?= $tr['src_csv'] ?></td>
            </tr>
            <tr>
                <td><a href="https://www.ign.fr" target="_blank">IGN</a></td>
                <td>Image</td>
                <td><?= $tr['src_ign'] ?></td>
            </tr>
        </tbody>
    </table>
</section>

<section>
    <h2><?= $tr['sources_tech'] ?></h2>
    <table>
        <thead>
            <tr>
                <th><?= $tr['col_techno'] ?? 'Technologie' ?></th>
                <th><?= $tr['col_version'] ?></th>
                <th><?= $tr['col_role'] ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>HTML</td>
                <td>5</td>
                <td><?= $tr['src_html'] ?></td>
            </tr>
            <tr>
                <td>CSS</td>
                <td>3</td>
                <td><?= $tr['src_css'] ?></td>
            </tr>
            <tr>
                <td>PHP</td>
                <td>8</td>
                <td><?= $tr['src_php'] ?></td>
            </tr>
            <tr>
                <td><?= $tr['src_hebergement'] ?></td>
                <td>—</td>
                <td><a href="https://www.alwaysdata.com" target="_blank">AlwaysData</a></td>
            </tr>
        </tbody>
    </table>
</section>

<?php require_once("./includes/footer.inc.php"); ?>