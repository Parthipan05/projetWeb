<?php

/**
 * @file stats.php
 * @brief Page de statistiques de StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Statistiques";
$description = "Statistiques des consultations sur StationFinder";

require_once("./includes/functions.inc.php");

$fichier_csv = './data/consultations.csv';
$toutes_stats = lire_statistiques($fichier_csv);
$stats = array_slice($toutes_stats, 0, 5);
$total = array_sum($toutes_stats);
$max = !empty($stats) ? max($stats) : 1;

$toutes_stats_villes = lire_statistiques_villes($fichier_csv);
$stats_villes = array_slice($toutes_stats_villes, 0, 5);
$max_villes = !empty($stats_villes) ? max($stats_villes) : 1;

$moyennes = calculer_moyennes_nationales();

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

<section>
	<h2><?= $tr['tendances'] ?></h2>
	<p><?= $tr['tendances_texte'] ?></p>

	<?php
	// Données statiques historiques (sources : prix-carburants.gouv.fr)
	$historique = [
		'Fév 2026' => ['sp95' => 1.72, 'gazole' => 1.72, 'sp98' => 1.85],
		'Mar 2026' => ['sp95' => 1.90, 'gazole' => 2.19, 'sp98' => 2.05],
		'Avr 2026' => ['sp95' => 2.00, 'gazole' => 2.17, 'sp98' => 2.10],
	];
	?>

	<div style="overflow-x: auto;">
		<table class="tableau-tendances">
			<thead>
				<tr>
					<th>Carburant</th>
					<?php foreach ($historique as $mois => $prix) { ?>
						<th><?= $mois ?></th>
					<?php } ?>
					<th>Mai 2026</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$carburants = [
					'SP95-E10' => 'sp95',
					'Gazole'   => 'gazole',
					'SP98'     => 'sp98',
				];
				foreach ($carburants as $nom => $cle) {
					echo "<tr>";
					echo "<td><strong>" . $nom . "</strong></td>";
					foreach ($historique as $mois => $prix) {
						if ($prix[$cle] < 1.80) {
							$classe = 'prix-bas';
						} elseif ($prix[$cle] > 2.00) {
							$classe = 'prix-eleve';
						} else {
							$classe = 'prix-moyen';
						}
						echo "<td class='cellule-prix " . $classe . "'>";
						echo number_format($prix[$cle], 2, ',', '') . " €";
						echo "</td>";
					}
					if ($moyennes[$cle] !== null) {
						if ($moyennes[$cle] < 1.80) {
							$classe = 'prix-bas';
						} elseif ($moyennes[$cle] > 2.00) {
							$classe = 'prix-eleve';
						} else {
							$classe = 'prix-moyen';
						}
						echo "<td class='cellule-prix prix-actuel " . $classe . "'>";
						echo number_format($moyennes[$cle], 2, ',', '') . " €";
						echo "</td>";
					} else {
						echo "<td class='cellule-prix'>N/D</td>";
					}

					echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="legende-tendances">
		<span class="legende-item prix-bas">&#9632; Prix bas</span>
		<span class="legende-item prix-moyen">&#9632; Prix moyen</span>
		<span class="legende-item prix-eleve">&#9632; Prix élevé</span>
	</div>
	<p class="texte-discret texte-centre"><?= $tr['source_prix'] ?></p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>