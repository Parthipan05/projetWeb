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

// Lecture des stats
$fichier_csv = './data/consultations.csv';
$stats = lire_statistiques($fichier_csv);
$total = array_sum($stats);
$max = !empty($stats) ? max($stats) : 1;

$stats_villes = lire_statistiques_villes($fichier_csv);
$total_villes = array_sum($stats_villes);
$max_villes = !empty($stats_villes) ? max($stats_villes) : 1;

require_once("./includes/header.inc.php");
?>

<h1>Statistiques</h1>

<section>
	<h2>Consultations totales</h2>
	<p>Le site a enregistré <strong><?= $total ?></strong> consultation(s) au total.</p>
</section>

<section>
	<h2>Départements les plus consultés</h2>

	<?php if (empty($stats)) { ?>
		<p>Aucune consultation enregistrée pour le moment.</p>
	<?php } else { ?>
		<p>Voici les <strong><?= count($stats) ?></strong> département(s) consultés sur le site :</p>

		<div class="histogramme">
			<?php foreach ($stats as $dep => $nb) { ?>
				<?php $largeur = round(($nb / $max) * 100); ?>
				<div class="barre-container">
					<span class="barre-label">Dép. <?= htmlspecialchars(str_replace('dep_', '', (string)$dep)) ?></span>
					<div class="barre" style="width: <?= $largeur ?>%;">
						<span class="barre-valeur"><?= $nb ?> visite(s)</span>
					</div>
				</div>
			<?php } ?>
		</div>

	<?php } ?>
</section>

<section>
    <h2>Villes les plus consultées</h2>

    <?php if (empty($stats_villes)) { ?>
        <p>Aucune ville enregistrée pour le moment.</p>
    <?php } else { ?>
        <p>Voici les <strong><?= count($stats_villes) ?></strong> ville(s) consultée(s) sur le site :</p>

        <div class="histogramme">
            <?php foreach ($stats_villes as $ville => $nb) { ?>
                <?php $largeur = round(($nb / $max_villes) * 100); ?>
                <div class="barre-container">
                    <span class="barre-label"><?= htmlspecialchars($ville) ?></span>
                    <div class="barre" style="width: <?= $largeur ?>%;">
                        <span class="barre-valeur"><?= $nb ?> visite(s)</span>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } ?>
</section>

<section>
    <h2>📈 Tendances des prix nationaux (2024-2025)</h2>
    <p>Évolution des prix moyens nationaux des carburants sur l'année écoulée.</p>

    <?php
    $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc', 'Jan', 'Fév', 'Mar', 'Avr'];
    $sp95   = [1.76, 1.78, 1.80, 1.79, 1.77, 1.75, 1.73, 1.71, 1.68, 1.66, 1.65, 1.64, 1.63, 1.62, 1.61, 1.60];
    $gazole = [1.72, 1.74, 1.76, 1.75, 1.73, 1.70, 1.68, 1.65, 1.62, 1.59, 1.57, 1.55, 1.54, 1.53, 1.52, 1.51];
    $sp98   = [1.88, 1.90, 1.92, 1.91, 1.89, 1.87, 1.85, 1.83, 1.80, 1.78, 1.77, 1.76, 1.75, 1.74, 1.73, 1.72];

    // Dimensions du graphique SVG
    $largeur    = 800;
    $hauteur    = 300;
    $marge_haut = 20;
    $marge_bas  = 40;
    $marge_g    = 50;
    $marge_d    = 20;

    // Zone de dessin
    $zone_l = $largeur - $marge_g - $marge_d;
    $zone_h = $hauteur - $marge_haut - $marge_bas;

    // Prix min et max pour l'échelle
    $prix_min = 1.45;
    $prix_max = 1.95;
    $nb_points = count($mois);

    // Fonction pour convertir un prix en coordonnée Y
    $to_y = function(float $prix) use ($prix_min, $prix_max, $zone_h, $marge_haut): float {
        return $marge_haut + $zone_h - (($prix - $prix_min) / ($prix_max - $prix_min)) * $zone_h;
    };

    // Fonction pour convertir un index en coordonnée X
    $to_x = function(int $i) use ($nb_points, $zone_l, $marge_g): float {
        return $marge_g + ($i / ($nb_points - 1)) * $zone_l;
    };

    // Construction des polylines
    $points_sp95 = $points_gazole = $points_sp98 = '';
    for ($i = 0; $i < $nb_points; $i++) {
        $x = $to_x($i);
        $points_sp95   .= $x . ',' . $to_y($sp95[$i])   . ' ';
        $points_gazole .= $x . ',' . $to_y($gazole[$i]) . ' ';
        $points_sp98   .= $x . ',' . $to_y($sp98[$i])   . ' ';
    }
    ?>

    <div style="overflow-x: auto;">
        <svg width="<?= $largeur ?>" height="<?= $hauteur ?>" style="display:block; margin:0 auto;">

            <!-- Lignes horizontales de référence -->
            <?php for ($p = 1.50; $p <= 1.90; $p += 0.10) { ?>
                <?php $y = $to_y($p); ?>
                <line x1="<?= $marge_g ?>" y1="<?= $y ?>" x2="<?= $largeur - $marge_d ?>" y2="<?= $y ?>"
                      stroke="#d0dce8" stroke-width="1" stroke-dasharray="4,4" />
                <text x="<?= $marge_g - 5 ?>" y="<?= $y + 4 ?>" text-anchor="end"
                      font-size="11" fill="#6b7a99"><?= number_format($p, 2) ?>€</text>
            <?php } ?>

            <!-- Courbe SP95 -->
            <polyline points="<?= $points_sp95 ?>"
                      fill="none" stroke="#1a3c6e" stroke-width="2.5" stroke-linejoin="round" />

            <!-- Courbe Gazole -->
            <polyline points="<?= $points_gazole ?>"
                      fill="none" stroke="#5db85d" stroke-width="2.5" stroke-linejoin="round" />

            <!-- Courbe SP98 -->
            <polyline points="<?= $points_sp98 ?>"
                      fill="none" stroke="#e67e22" stroke-width="2.5" stroke-linejoin="round" />

            <!-- Points sur les courbes -->
            <?php for ($i = 0; $i < $nb_points; $i++) { ?>
                <?php $x = $to_x($i); ?>
                <circle cx="<?= $x ?>" cy="<?= $to_y($sp95[$i]) ?>"   r="4" fill="#1a3c6e" />
                <circle cx="<?= $x ?>" cy="<?= $to_y($gazole[$i]) ?>" r="4" fill="#5db85d" />
                <circle cx="<?= $x ?>" cy="<?= $to_y($sp98[$i]) ?>"   r="4" fill="#e67e22" />
            <?php } ?>

            <!-- Labels mois sur l'axe X -->
            <?php for ($i = 0; $i < $nb_points; $i++) { ?>
                <text x="<?= $to_x($i) ?>" y="<?= $hauteur - 10 ?>"
                      text-anchor="middle" font-size="11" fill="#6b7a99">
                    <?= $mois[$i] ?>
                </text>
            <?php } ?>

        </svg>
    </div>

    <!-- Légende -->
    <div style="display:flex; gap:20px; justify-content:center; margin-top:10px; font-size:0.9em; font-weight:bold;">
        <span style="color:#1a3c6e;">● SP95</span>
        <span style="color:#5db85d;">● Gazole</span>
        <span style="color:#e67e22;">● SP98</span>
    </div>
    <p class="texte-discret" style="text-align:center; margin-top:8px;">
        Source : données moyennes nationales approximatives — prix-carburants.gouv.fr
    </p>
</section>

<?php require_once("./includes/footer.inc.php"); ?>