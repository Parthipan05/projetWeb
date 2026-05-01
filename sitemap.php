<?php

/**
 * @file sitemap.php
 * @brief Plan du site StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Plan du site";
$description = "Plan du site StationFinder - toutes les pages disponibles";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>Plan du site</h1>

<section>
	<h2>Pages</h2>
	<ul>
		<li><a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Accueil</a></li>
		<li><a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Statistiques</a></li>
		<li><a href="apropos.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">À propos</a></li>
		<li><a href="aide.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Aide</a></li>
		<li><a href="sources.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Sources</a></li>
		<li><a href="confidentialite.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Confidentialité</a></li>
		<li><a href="tech.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Page technique</a></li>
		<li><a href="sitemap.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Plan du site</a></li>
	</ul>
</section>

<?php require_once("./includes/footer.inc.php"); ?>