<?php

/**
 * @file header.inc.php
 * @brief En-tête commune à toutes les pages de StationFinder.
 * Gère les cookies (mode jour/nuit, dernière ville),
 * la langue et le style via les paramètres GET.
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

require_once("./includes/util.inc.php");

// --- Chemin du site pour les cookies (4ème paramètre obligatoire) ---
define('COOKIE_PATH', '/stationfinder/');
define('COOKIE_DUREE', time() + (30 * 24 * 3600)); // 30 jours

// GESTION DU COOKIE MODE JOUR/NUIT
if (isset($_GET['style']) && !empty($_GET['style'])) {
	$styleUrl = $_GET['style'];
	if ($styleUrl === 'standard' || $styleUrl === 'alternatif') {
		setcookie('style', $styleUrl, COOKIE_DUREE, COOKIE_PATH);
		$css = ($styleUrl === 'alternatif') ? 'alternatif.css' : 'style.css';
	} else {
		setcookie('style', '', time() - 3600, COOKIE_PATH);
		$styleUrl = 'standard';
		$css = 'style.css';
	}
} elseif (isset($_COOKIE['style']) && !empty($_COOKIE['style'])) {
	$styleUrl = $_COOKIE['style'];
	if ($styleUrl === 'standard' || $styleUrl === 'alternatif') {
		$css = ($styleUrl === 'alternatif') ? 'alternatif.css' : 'style.css';
	} else {
		setcookie('style', '', time() - 3600, COOKIE_PATH);
		$styleUrl = 'standard';
		$css = 'style.css';
	}
} else {
	$styleUrl = 'standard';
	$css = 'style.css';
}

// GESTION DE LA LANGUE
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
	$lang = $_GET['lang'];
} else {
	$lang = 'fr';
}

// COMPTEUR DE HITS
$hits = incrementer_hits('./data/hits.txt');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
	<meta charset="utf-8" />
	<meta name="author" content="PIRABAKARAN Parthipan et HANANE Sanaa" />
	<meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : 'StationFinder - Trouvez les prix des carburants près de chez vous' ?>" />
	<meta name="keywords" content="carburant, essence, diesel, station service, prix, France" />
	<title>StationFinder — <?= isset($titre) ? htmlspecialchars($titre) : 'Accueil' ?></title>
	<link rel="stylesheet" href="<?= $css ?>" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>

<body>
	<header class="site-header">
		<a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>" class="logo">
			<img src="images/logo.png" alt="StationFinder - Prix des carburants en temps réel" />
		</a>

		<nav class="menu-principal">
			<ul>

				<li><a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
						<?= ($lang === 'fr') ? 'Statistiques' : 'Statistics' ?>
					</a></li>
			</ul>
		</nav>
	</header>

	<div class="barre-options">
		<?php if ($styleUrl === 'standard'): ?>
			<a href="?style=alternatif&amp;lang=<?= $lang ?>">🌙 Mode Nuit</a>
		<?php else: ?>
			<a href="?style=standard&amp;lang=<?= $lang ?>">☀️ Mode Jour</a>
		<?php endif; ?>
		&nbsp;|&nbsp;
		<?php if ($lang === 'fr'): ?>
			<a href="?style=<?= $styleUrl ?>&amp;lang=en">🇬🇧 English</a>
		<?php else: ?>
			<a href="?style=<?= $styleUrl ?>&amp;lang=fr">🇫🇷 Français</a>
		<?php endif; ?>
	</div>

	<div class="container">
		<main>