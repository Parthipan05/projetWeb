<?php

/**
 * @file stats.php
 * @brief Page de statistiques de StationFinder.
 * Affiche les départements et villes les plus consultés,
 * ainsi que les tendances des prix nationaux via un graphique SVG.
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre       = "Statistiques";
$description = "Statistiques des consultations sur StationFinder";

require_once("./includes/functions.inc.php");

// --- Lecture des statistiques depuis le CSV ---
$fichier_csv  = './data/consultations.csv';
$toutes_stats = lire_statistiques($fichier_csv);
$stats        = array_slice($toutes_stats, 0, 5);
$total        = array_sum($toutes_stats);
$max          = !empty($stats) ? max($stats) : 1;

$stats_villes = array_slice(lire_statistiques_villes($fichier_csv), 0, 5);
$max_villes   = !empty($stats_villes) ? max($stats_villes) : 1;

require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
?>

<h1><?= $tr['stats_titre'] ?></h1>

<section>
	<h2><?= $tr['activite'] ?></h2>
	<p><?= $tr['pages_vues'] ?> : <strong><?= $hits ?></strong></p>
	<p><?= $tr['recherches'] ?> : <strong><?= $total ?></strong></p>
</section>

<section>
	<h2><?= $tr['deps_consultes'] ?></h2>
	<?php if (empty($stats)) { ?>
		<p><?= $tr['aucune_consul'] ?></p>
	<?php } else { ?>
		<p><?= $tr['voici_deps'] ?> : <strong><?= count($stats) ?></strong></p>
		<div class="histogramme">
			<?php foreach ($stats as $dep => $nb) { ?>
				<?php $largeur = round(($nb / $max) * 100); ?>
				<div class="barre-container">
					<span class="barre-label"><?= $tr['dep_label'] ?> <?= htmlspecialchars(str_replace('dep_', '', (string)$dep)) ?></span>
					<div class="barre" style="width: <?= $largeur ?>%;">
						<span class="barre-valeur"><?= $nb ?> <?= $tr['visite'] ?></span>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</section>

<section>
	<h2><?= $tr['villes_consultees'] ?></h2>
	<?php if (empty($stats_villes)) { ?>
		<p><?= $tr['aucune_ville'] ?></p>
	<?php } else { ?>
		<p><?= $tr['voici_villes'] ?> : <strong><?= count($stats_villes) ?></strong></p>
		<div class="histogramme">
			<?php foreach ($stats_villes as $ville => $nb) { ?>
				<?php $largeur = round(($nb / $max_villes) * 100); ?>
				<div class="barre-container">
					<span class="barre-label"><?= htmlspecialchars($ville) ?></span>
					<div class="barre" style="width: <?= $largeur ?>%;">
						<span class="barre-valeur"><?= $nb ?> <?= $tr['visite'] ?></span>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>