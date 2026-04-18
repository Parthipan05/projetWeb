<?php

/**
 * @file sitemap.php
 * @brief Plan du site StationFinder.
 * Liste toutes les pages disponibles sur le site.
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Plan du site";
$description = "Plan du site StationFinder - toutes les pages disponibles";

require_once("./includes/functions.inc.php");
require_once("./includes/header.inc.php");
?>

<h1>🗺️ Plan du site</h1>

<section>
	<h2>Pages principales</h2>
	<ul>
		<li>
			<a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
				🏠 Accueil
			</a>
			<p class="texte-discret">Carte interactive des régions de France pour rechercher les stations-service.</p>
		</li>
		<li>
			<a href="resultats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
				⛽ Résultats
			</a>
			<p class="texte-discret">Affichage des stations-service et prix des carburants par département.</p>
		</li>
		<li>
			<a href="stats.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
				📊 Statistiques
			</a>
			<p class="texte-discret">Histogramme des départements les plus consultés sur le site.</p>
		</li>
		<li>
			<a href="tech.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
				🔧 Page Tech
			</a>
			<p class="texte-discret">Démonstration des APIs JSON (Ghibli) et XML (géolocalisation IP).</p>
		</li>
		<li>
			<a href="sitemap.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>">
				🗺️ Plan du site
			</a>
			<p class="texte-discret">Cette page — liste de toutes les pages du site.</p>
		</li>
	</ul>
</section>

<section>
	<h2>Fonctionnalités</h2>
	<ul>
		<li>🗺️ Carte interactive des régions cliquable</li>
		<li>📋 Sélection du département via liste déroulante</li>
		<li>⛽ Prix des carburants en temps réel (API gouvernementale)</li>
		<li>🍃 Film Ghibli aléatoire (API JSON)</li>
		<li>📍 Géolocalisation IP (API XML)</li>
		<li>💾 Historique des consultations (CSV côté serveur)</li>
		<li>🍪 Mémorisation des préférences (cookies)</li>
		<li>📊 Statistiques des consultations</li>
		<li>🌙 Mode jour / nuit</li>
		<li>🇫🇷 Site en français et anglais</li>
	</ul>
</section>

<?php require_once("./includes/footer.inc.php"); ?>