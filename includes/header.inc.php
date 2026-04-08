<?php
require_once("./includes/util.inc.php");

if (isset($_GET['style']) && !empty($_GET['style'])) {
	$styleUrl = $_GET['style'];
	$css = ($styleUrl == 'alternatif') ? 'alternatif.css' : 'style.css';
} else {
	$styleUrl = 'standard';
	$css = 'style.css';
}

if (isset($_GET['lang']) && !empty($_GET['lang'])) {
	$lang = $_GET['lang'];
} else {
	$lang = 'fr';
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
	<meta charset="utf-8" />
	<title><?= $titre ?></title>
	<meta name="author" content="PIRABAKARAN Parthipan et HANANE Sanaa" />
	<link rel="stylesheet" href="style.css" />
</head>

<body>
	<header class="site-header">
		<nav class="menu-principal">
			<ul>
				<li><a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Accueil</a></li>
				<li><a href="tech.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">Tech</a></li>
			</ul>
		</nav>
	</header>

	<div class="barre-options">
		<?php if ($styleUrl == 'standard'): ?>
			<a href="?style=alternatif&amp;lang=<?= $lang ?>">🌙 Mode Nuit</a>
		<?php else: ?>
			<a href="?style=standard&amp;lang=<?= $lang ?>">☀️ Mode Jour</a>
		<?php endif; ?>
		&nbsp;|&nbsp;
		<?php if ($lang == 'fr'): ?>
			<a href="?style=<?= $styleUrl ?>&amp;lang=en">🇬🇧 English</a>
		<?php else: ?>
			<a href="?style=<?= $styleUrl ?>&amp;lang=fr">🇫🇷 Français</a>
		<?php endif; ?>
	</div>

	<div class="container">
		<main>