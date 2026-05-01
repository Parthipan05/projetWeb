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
$stats        = lire_statistiques($fichier_csv);
$total        = array_sum($stats);
$max          = !empty($stats) ? max($stats) : 1;

$stats_villes = lire_statistiques_villes($fichier_csv);
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

<section>
	<h2><?= $tr['tendances'] ?></h2>
	<p><?= $tr['tendances_texte'] ?></p>

	<?php
	$mois   = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc', 'Jan', 'Fév', 'Mar', 'Avr'];
	$sp95   = [1.76, 1.78, 1.80, 1.79, 1.77, 1.75, 1.73, 1.71, 1.68, 1.66, 1.65, 1.64, 1.63, 1.62, 1.61, 1.60];
	$gazole = [1.72, 1.74, 1.76, 1.75, 1.73, 1.70, 1.68, 1.65, 1.62, 1.59, 1.57, 1.55, 1.54, 1.53, 1.52, 1.51];
	$sp98   = [1.88, 1.90, 1.92, 1.91, 1.89, 1.87, 1.85, 1.83, 1.80, 1.78, 1.77, 1.76, 1.75, 1.74, 1.73, 1.72];

	$largeur_svg = 800;
	$hauteur_svg = 300;
	$marge_haut  = 20;
	$marge_bas   = 40;
	$marge_g     = 50;
	$marge_d     = 20;
	$zone_l      = $largeur_svg - $marge_g - $marge_d;
	$zone_h      = $hauteur_svg - $marge_haut - $marge_bas;
	$prix_min    = 1.45;
	$prix_max    = 1.95;
	$nb_points   = count($mois);

	$to_y = function (float $prix) use ($prix_min, $prix_max, $zone_h, $marge_haut): float {
		return $marge_haut + $zone_h - (($prix - $prix_min) / ($prix_max - $prix_min)) * $zone_h;
	};

	$to_x = function (int $i) use ($nb_points, $zone_l, $marge_g): float {
		return $marge_g + ($i / ($nb_points - 1)) * $zone_l;
	};

	$points_sp95 = $points_gazole = $points_sp98 = '';
	for ($i = 0; $i < $nb_points; $i++) {
		$x = $to_x($i);
		$points_sp95   .= $x . ',' . $to_y($sp95[$i])   . ' ';
		$points_gazole .= $x . ',' . $to_y($gazole[$i]) . ' ';
		$points_sp98   .= $x . ',' . $to_y($sp98[$i])   . ' ';
	}
	?>

	<div style="overflow-x: auto;">
		<svg width="<?= $largeur_svg ?>" height="<?= $hauteur_svg ?>" style="display:block; margin:0 auto;">
			<?php for ($p = 1.50; $p <= 1.90; $p += 0.10) { ?>
				<?php $y = $to_y($p); ?>
				<line x1="<?= $marge_g ?>" y1="<?= $y ?>" x2="<?= $largeur_svg - $marge_d ?>" y2="<?= $y ?>"
					stroke="#d0dce8" stroke-width="1" stroke-dasharray="4,4" />
				<text x="<?= $marge_g - 5 ?>" y="<?= $y + 4 ?>" text-anchor="end"
					font-size="11" fill="#6b7a99"><?= number_format($p, 2) ?>€</text>
			<?php } ?>

			<polyline points="<?= $points_sp95 ?>" fill="none" stroke="#1a3c6e" stroke-width="2.5" stroke-linejoin="round" />
			<polyline points="<?= $points_gazole ?>" fill="none" stroke="#5db85d" stroke-width="2.5" stroke-linejoin="round" />
			<polyline points="<?= $points_sp98 ?>" fill="none" stroke="#e67e22" stroke-width="2.5" stroke-linejoin="round" />

			<?php for ($i = 0; $i < $nb_points; $i++) { ?>
				<?php $x = $to_x($i); ?>
				<circle cx="<?= $x ?>" cy="<?= $to_y($sp95[$i]) ?>" r="4" fill="#1a3c6e" />
				<circle cx="<?= $x ?>" cy="<?= $to_y($gazole[$i]) ?>" r="4" fill="#5db85d" />
				<circle cx="<?= $x ?>" cy="<?= $to_y($sp98[$i]) ?>" r="4" fill="#e67e22" />
			<?php } ?>

			<?php for ($i = 0; $i < $nb_points; $i++) { ?>
				<text x="<?= $to_x($i) ?>" y="<?= $hauteur_svg - 10 ?>"
					text-anchor="middle" font-size="11" fill="#6b7a99"><?= $mois[$i] ?></text>
			<?php } ?>
		</svg>
	</div>

	<div style="display:flex; gap:20px; justify-content:center; margin-top:10px; font-size:0.9em; font-weight:bold;">
		<span style="color:#1a3c6e;">● SP95</span>
		<span style="color:#5db85d;">● Gazole</span>
		<span style="color:#e67e22;">● SP98</span>
	</div>
	<p class="texte-discret" style="text-align:center; margin-top:8px;">
		<?= $tr['source_prix'] ?>
	</p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>