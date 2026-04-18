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

// --- Lecture des stats ---
$fichier_csv = './data/consultations.csv';
$stats       = lire_statistiques($fichier_csv);
$total       = array_sum($stats);
$max         = !empty($stats) ? max($stats) : 1;

require_once("./includes/header.inc.php");
?>

<style>
	.histogramme {
		margin-top: 20px;
	}

	.barre-container {
		display: flex;
		align-items: center;
		gap: 15px;
		margin-bottom: 10px;
	}

	.barre-label {
		min-width: 80px;
		font-size: 0.9em;
		color: #a0a0b0;
		text-align: right;
	}

	.barre {
		background-color: #e94560;
		border-radius: 4px;
		padding: 6px 10px;
		min-width: 40px;
	}

	.barre-valeur {
		color: #fff;
		font-size: 0.85em;
		white-space: nowrap;
	}
</style>

<h1>📊 Statistiques</h1>

<section>
	<h2>Consultations totales</h2>
	<p>Le site a enregistré <strong><?= $total ?></strong> consultation(s) au total.</p>
</section>

<section>
	<h2>Départements les plus consultés</h2>

	<?php if (empty($stats)): ?>
		<p>Aucune consultation enregistrée pour le moment.</p>
	<?php else: ?>
		<p>Voici les <strong><?= count($stats) ?></strong> département(s) consultés sur le site :</p>

		<div class="histogramme">
			<?php foreach ($stats as $dep => $nb): ?>
				<?php $largeur = round(($nb / $max) * 100); ?>
				<div class="barre-container">
					<span class="barre-label">Dép. <?= htmlspecialchars(str_replace('dep_', '', (string)$dep)) ?></span>
					<div class="barre" style="width: <?= $largeur ?>%;">
						<span class="barre-valeur"><?= $nb ?> visite(s)</span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>
</section>

<?php require_once("./includes/footer.inc.php"); ?>