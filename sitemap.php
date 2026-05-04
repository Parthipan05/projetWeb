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
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['sitemap_titre'] ?></h1>

<section>
	<h2><?= $tr['sitemap_pages'] ?></h2>
	<ul>
		<li><a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_accueil'] ?></a></li>
		<li><a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_stats'] ?></a></li>
		<li><a href="apropos.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_apropos'] ?></a></li>
		<li><a href="aide.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_aide'] ?></a></li>
		<li><a href="sources.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_sources'] ?></a></li>
		<li><a href="confidentialite.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_confidential'] ?></a></li>
		<li><a href="tech.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_tech'] ?></a></li>
		<li><a href="sitemap.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>"><?= $tr['nav_sitemap'] ?></a></li>
	</ul>
</section>

<?php require_once("./includes/footer.inc.php"); ?>