<?php

/**
 * @file resultats.php
 * @brief Page d'affichage des stations-service et prix des carburants.
 * Appelle l'API gouvernementale avec le code département.
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Résultats";
$description = "Stations-service et prix des carburants";

// --- Récupération des coordonnées GPS (mode géolocalisation) ---
$lat_utilisateur = 0.0;
$lon_utilisateur = 0.0;
$mode_geoloc     = false;

if (
	isset($_GET['lat']) && isset($_GET['lon'])
	&& !empty($_GET['lat']) && !empty($_GET['lon'])
) {
	$lat_utilisateur = (float)$_GET['lat'];
	$lon_utilisateur = (float)$_GET['lon'];
	if (
		$lat_utilisateur >= -90.0  && $lat_utilisateur <= 90.0
		&& $lon_utilisateur >= -180.0 && $lon_utilisateur <= 180.0
	) {
		$mode_geoloc = true;
	}
}

// --- Rayon choisi par l'utilisateur (en km), 5 km par défaut ---
$rayon_km = 5;
if (isset($_GET['rayon']) && !empty($_GET['rayon'])) {
	$rayon_km = (int)$_GET['rayon'];
	if (!in_array($rayon_km, [1, 5, 10, 20, 0])) {
		$rayon_km = 5;
	}
}

// --- Cookie dernière consultation (avant tout affichage HTML) ---
if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$dep_cookie   = htmlspecialchars($_GET['departement']);
	$ville_cookie = htmlspecialchars($_GET['ville'] ?? '');
	setcookie(
		'derniere_consultation',
		$dep_cookie . '|' . $ville_cookie . '|' . date('d/m/Y H:i'),
		time() + (30 * 24 * 3600),
		'/'
	);
}

require_once("./includes/functions.inc.php");

// --- Enregistrement de la consultation dans le CSV ---
if (!empty($_GET['departement'])) {
	enregistrer_consultation(
		htmlspecialchars($_GET['departement']),
		htmlspecialchars($_GET['ville'] ?? '')
	);
}

require_once("./includes/header.inc.php");
require_once("./includes/traductions.inc.php");
// --- Récupération et sécurisation des paramètres GET ---
$departement = "";
$region      = "";
$ville       = "";

if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$departement = htmlspecialchars($_GET['departement']);
}
if (isset($_GET['region']) && !empty($_GET['region'])) {
	$region = htmlspecialchars($_GET['region']);
}
if (isset($_GET['ville']) && !empty($_GET['ville'])) {
	$ville = htmlspecialchars($_GET['ville']);
}

// --- Vérification qu'un département ou une ville a bien été sélectionné ---
if (empty($departement) && empty($ville) && !$mode_geoloc) {
	echo "<p>" . $tr['select_dep'] . "</p>";
	require_once("./includes/footer.inc.php");
	exit;
}
?>

<h1><?= $tr['station'] ?>s —
	<?php if (!empty($ville) && !empty($departement)) { ?>
		<?= htmlspecialchars($ville) ?> (<?= htmlspecialchars($departement) ?>)
	<?php } elseif (!empty($ville)) { ?>
		<?= htmlspecialchars($ville) ?>
	<?php } else { ?>
		<?= $tr['departement'] ?> <?= htmlspecialchars($departement) ?>
	<?php } ?>
</h1>

<?php
// --- Lecture et affichage du cookie dernière consultation ---
$derniere = get_derniere_consultation();
if ($derniere !== null) {
	echo "<p class='texte-discret'>"
		. $tr['derniere_consul']
		. " <a href='resultats.php?departement=" . urlencode($derniere['departement'])
		. "&ville=" . urlencode($derniere['ville'])
		. "&style=" . $styleUrl
		. "&lang=" . $lang . "'>"
		. "<strong>" . $derniere['departement'] . "</strong>"
		. (!empty($derniere['ville']) ? " — <strong>" . $derniere['ville'] . "</strong>" : "")
		. "</a> le " . $derniere['date']
		. "</p>";
}
?>
<section>
	<?php
	// --- Appel de l'API gouvernementale (format JSON) ---
	if ($mode_geoloc && empty($departement)) {
		$filtre = "dist(geo_point_2d%2C%22" . $lat_utilisateur . "%2C" . $lon_utilisateur . "%22)%3C%3D20000";
	} elseif (!empty($ville) && !empty($departement)) {
		// Filtre par ville ET département
		$filtre = "code_departement%3D%22" . rawurlencode($departement) . "%22"
			. "%20AND%20ville%3D%22" . rawurlencode(trim($ville)) . "%22";
	} elseif (!empty($departement)) {
		// Filtre par département uniquement
		$filtre = "code_departement%3D%22" . rawurlencode($departement) . "%22";
	} else {
		$filtre = "ville%3D%22" . rawurlencode(trim($ville)) . "%22";
	}

	$url_api = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?"
		. "where=" . $filtre
		. "&limit=" . ($mode_geoloc ? 100 : 50)
		. "&timezone=Europe%2FParis";

	$fichier_cache = './data/cache_' . md5($url_api) . '.json';
	$json_brut = recuperer_avec_cache($url_api, $fichier_cache, 600);
	$donnees   = json_decode($json_brut, true);
	if ($donnees === null || !isset($donnees['results'])) {
		echo "<p>Impossible de récupérer les données. Veuillez réessayer.</p>";
		$stations = [];
	} elseif (count($donnees['results']) === 0 && !empty($ville)) {
		// Fallback : on relance l'API sur tout le département
		$filtre_dep = "code_departement%3D%22" . rawurlencode($departement) . "%22";
		$url_fallback = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?"
			. "where=" . $filtre_dep
			. "&limit=50"
			. "&timezone=Europe%2FParis";
		$json_fallback = file_get_contents($url_fallback);
		$donnees_fallback = json_decode($json_fallback, true);
		if ($donnees_fallback !== null && !empty($donnees_fallback['results'])) {
			$stations = $donnees_fallback['results'];
			$mode_fallback = true;
		} else {
			$stations = [];
			echo "<p>" . $tr['aucune_station'] . "</p>";
		}
	} elseif (count($donnees['results']) === 0) {
		$stations = [];
		echo "<p>" . $tr['aucune_station'] . "</p>";
	} else {
		$stations = $donnees['results'];
	}

	// --- Le traitement des stations se fait ICI pour tous les cas ---
	if (!empty($stations)) {
		// Carburants choisis par l'utilisateur, tous par défaut
		if (isset($_GET['carburants']) && !empty($_GET['carburants'])) {
			$carburants_choisis = $_GET['carburants'];
		} else {
			$carburants_choisis = ['sp95', 'sp98', 'gazole', 'e10', 'gplc'];
		}

		// Tri choisi par l'utilisateur, aucun par défaut
		if (isset($_GET['tri']) && !empty($_GET['tri'])) {
			$tri = $_GET['tri'];
		} else {
			$tri = '';
		}
		// --- Mode géolocalisation ---
		if ($mode_geoloc) {
			foreach ($stations as &$station) {
				$lat_station = isset($station['geom']['lat']) ? (float)$station['geom']['lat'] : 0.0;
				$lon_station = isset($station['geom']['lon']) ? (float)$station['geom']['lon'] : 0.0;
				if ($lat_station !== 0.0 && $lon_station !== 0.0) {
					$station['distance_km'] = calculer_distance(
						$lat_utilisateur,
						$lon_utilisateur,
						$lat_station,
						$lon_station
					);
				} else {
					$station['distance_km'] = 9999.0;
				}
			}
			unset($station);

			if ($rayon_km > 0) {
				$stations = array_filter($stations, function ($s) use ($rayon_km) {
					return $s['distance_km'] <= (float)$rayon_km;
				});
				$stations = array_values($stations);
			}

			// Tri par distance croissante (tri à bulles simple)
			$nb = count($stations);
			for ($i = 0; $i < $nb - 1; $i++) {
				for ($j = 0; $j < $nb - $i - 1; $j++) {
					if ($stations[$j]['distance_km'] > $stations[$j + 1]['distance_km']) {
						$temp = $stations[$j];
						$stations[$j] = $stations[$j + 1];
						$stations[$j + 1] = $temp;
					}
				}
			}

			// --- Mode normal : tri par prix ---
		} elseif ($tri === 'asc' || $tri === 'desc') {
			// Tri simple : on extrait le prix minimum de chaque station
			$prix_min = [];
			foreach ($stations as $i => $station) {
				$min = 9999;
				foreach ($carburants_choisis as $c) {
					if (!empty($station[$c . '_prix']) && (float)$station[$c . '_prix'] < $min) {
						$min = (float)$station[$c . '_prix'];
					}
				}
				$prix_min[$i] = $min;
			}
			// Tri du tableau des prix puis on réordonne les stations
			if ($tri === 'asc') {
				asort($prix_min);
			} else {
				arsort($prix_min);
			}
			$stations_triees = [];
			foreach ($prix_min as $i => $prix) {
				$stations_triees[] = $stations[$i];
			}
			$stations = $stations_triees;
		}

		// --- Filtre carburants : on garde seulement les stations qui ont au moins un prix ---
		$stations_filtrees = [];
		foreach ($stations as $station) {
			foreach ($carburants_choisis as $c) {
				if (!empty($station[$c . '_prix'])) {
					$stations_filtrees[] = $station;
					break; // on passe à la station suivante dès qu'on trouve un prix
				}
			}
		}
		// Si après filtre il ne reste rien, on garde toutes les stations
		if (!empty($stations_filtrees)) {
			$stations = $stations_filtrees;
		}
	?>
		<?php
		if (!empty($mode_fallback)) {
			echo "<p class='texte-discret'>" . $tr['pas_station_ville'] . " <strong>" . $departement . "</strong>.</p>";
		}
		if (!empty($mode_fallback)) {
			echo "<p><strong>" . count($stations) . "</strong> " . $tr['stations_trouvees'] . " — " . $tr['departement'] . " <strong>" . $departement . "</strong>.</p>";
		} else if (!empty($ville)) {
			echo "<p><strong>" . count($stations) . "</strong> " . $tr['stations_trouvees'] . " — <strong>" . htmlspecialchars($ville) . "</strong>.</p>";
		} else {
			echo "<p><strong>" . count($stations) . "</strong> " . $tr['stations_trouvees'] . " — " . $tr['departement'] . " <strong>" . $departement . "</strong>.</p>";
		}		?>
		<form action="resultats.php#resultats" method="get">
			<input type="hidden" name="departement" value="<?= htmlspecialchars($departement) ?>" />
			<input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>" />
			<input type="hidden" name="lat" value="<?= $lat_utilisateur ?>" />
			<input type="hidden" name="lon" value="<?= $lon_utilisateur ?>" />
			<input type="hidden" name="style" value="<?= $styleUrl ?>" />
			<input type="hidden" name="lang" value="<?= $lang ?>" />

			<fieldset>
				<legend><?= $tr['filtrer'] ?></legend>
				<label><input type="checkbox" name="carburants[]" value="sp95" <?= (!isset($_GET['carburants']) || in_array('sp95',   $_GET['carburants'])) ? 'checked' : '' ?>> SP95</label>
				<label><input type="checkbox" name="carburants[]" value="sp98" <?= (!isset($_GET['carburants']) || in_array('sp98',   $_GET['carburants'])) ? 'checked' : '' ?>> SP98</label>
				<label><input type="checkbox" name="carburants[]" value="gazole" <?= (!isset($_GET['carburants']) || in_array('gazole', $_GET['carburants'])) ? 'checked' : '' ?>> Gazole</label>
				<label><input type="checkbox" name="carburants[]" value="e10" <?= (!isset($_GET['carburants']) || in_array('e10',    $_GET['carburants'])) ? 'checked' : '' ?>> E10</label>
				<label><input type="checkbox" name="carburants[]" value="gplc" <?= (!isset($_GET['carburants']) || in_array('gplc',   $_GET['carburants'])) ? 'checked' : '' ?>> GPL</label>
				<label><input type="checkbox" name="services" <?= isset($_GET['services']) ? 'checked' : '' ?>> <?= $lang === 'fr' ? 'Afficher les services' : 'Show services' ?></label>
				<label><?= $tr['trier_prix'] ?> :
					<select name="tri">
						<option value=""><?= $tr['aucun_tri'] ?></option>
						<option value="asc" <?= (isset($_GET['tri']) && $_GET['tri'] === 'asc')  ? 'selected' : '' ?>><?= $tr['croissant'] ?></option>
						<option value="desc" <?= (isset($_GET['tri']) && $_GET['tri'] === 'desc') ? 'selected' : '' ?>><?= $tr['decroissant'] ?></option>
					</select>
				</label>

				<button type="submit" class="btn"><?= $tr['appliquer'] ?></button>
			</fieldset>
		</form>

		<div id="resultats">
			<?php if ($mode_geoloc) { ?>
				<form action="resultats.php#resultats" method="get">
					<input type="hidden" name="departement" value="<?= htmlspecialchars($departement) ?>" />
					<input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>" />
					<input type="hidden" name="lat" value="<?= $lat_utilisateur ?>" />
					<input type="hidden" name="lon" value="<?= $lon_utilisateur ?>" />
					<input type="hidden" name="style" value="<?= $styleUrl ?>" />
					<input type="hidden" name="lang" value="<?= $lang ?>" />
					<?php foreach ($carburants_choisis as $c) { ?>
						<input type="hidden" name="carburants[]" value="<?= htmlspecialchars($c) ?>" />
					<?php } ?>
					<fieldset>
						<legend><?= $tr['rayon'] ?></legend>
						<label for="rayon"><?= $tr['distance'] ?> :</label>
						<select name="rayon" id="rayon">
							<option value="1" <?= ($rayon_km === 1)  ? 'selected' : '' ?>>1 km</option>
							<option value="5" <?= ($rayon_km === 5)  ? 'selected' : '' ?>>5 km</option>
							<option value="10" <?= ($rayon_km === 10) ? 'selected' : '' ?>>10 km</option>
							<option value="20" <?= ($rayon_km === 20) ? 'selected' : '' ?>>20 km</option>
							<option value="0" <?= ($rayon_km === 0)  ? 'selected' : '' ?>><?= $tr['tout_dep'] ?></option>
						</select>
						<button type="submit" class="btn"><?= $tr['appliquer'] ?></button>
					</fieldset>
				</form>
			<?php } ?>

			<table>
				<thead>
					<tr>
						<th><?= $tr['station'] ?></th>
						<th><?= $tr['adresse'] ?></th>
						<th><?= $tr['ville'] ?></th>
						<?php if (in_array('sp95', $carburants_choisis)) { ?><th>SP95</th><?php } ?>
						<?php if (in_array('sp98', $carburants_choisis)) { ?><th>SP98</th><?php } ?>
						<?php if (in_array('gazole', $carburants_choisis)) { ?><th>Gazole</th><?php } ?>
						<?php if (in_array('e10', $carburants_choisis)) { ?><th>E10</th><?php } ?>
						<?php if (in_array('gplc', $carburants_choisis)) { ?><th>GPL</th><?php } ?>
						<?php if ($mode_geoloc) { ?><th><?= $tr['distance'] ?></th><?php } ?>
						<?php if (isset($_GET['services'])) { ?><th><?= $lang === 'fr' ? 'Services' : 'Services' ?></th><?php } ?>
						<?php if (!isset($_GET['services'])) { ?><th><?= $lang === 'fr' ? 'Détails' : 'Details' ?></th><?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($stations as $station) { ?>
						<tr id="station-<?= htmlspecialchars((string)($station['id'] ?? '')) ?>">
							<td><?= htmlspecialchars($station['ensigne'] ?? 'N/A') ?></td>
							<td><?= htmlspecialchars($station['adresse'] ?? 'N/A') ?></td>
							<td><?= htmlspecialchars($station['ville'] ?? 'N/A') ?></td>
							<?php if (in_array('sp95', $carburants_choisis)) { ?>
								<td><?= !empty($station['sp95_prix']) ? htmlspecialchars((string)$station['sp95_prix']) . ' €' : '-' ?></td>
							<?php } ?>
							<?php if (in_array('sp98', $carburants_choisis)) { ?>
								<td><?= !empty($station['sp98_prix']) ? htmlspecialchars((string)$station['sp98_prix']) . ' €' : '-' ?></td>
							<?php } ?>
							<?php if (in_array('gazole', $carburants_choisis)) { ?>
								<td><?= !empty($station['gazole_prix']) ? htmlspecialchars((string)$station['gazole_prix']) . ' €' : '-' ?></td>
							<?php } ?>
							<?php if (in_array('e10', $carburants_choisis)) { ?>
								<td><?= !empty($station['e10_prix']) ? htmlspecialchars((string)$station['e10_prix']) . ' €' : '-' ?></td>
							<?php } ?>
							<?php if (in_array('gplc', $carburants_choisis)) { ?>
								<td><?= !empty($station['gplc_prix']) ? htmlspecialchars((string)$station['gplc_prix']) . ' €' : '-' ?></td>
							<?php } ?>
							<?php if ($mode_geoloc) { ?>
								<td><?= ($station['distance_km'] < 9999.0) ? $station['distance_km'] . ' km' : 'N/A' ?></td>
							<?php } ?>
							<?php if (isset($_GET['services'])) { ?>
								<td>
									<?php if (!empty($station['services_service']) && is_array($station['services_service'])) { ?>
										<ul>
											<?php foreach ($station['services_service'] as $service) { ?>
												<li><?= htmlspecialchars(trim($service)) ?></li>
											<?php } ?>
										</ul>
									<?php } elseif (!empty($station['services_service'])) { ?>
										<ul>
											<?php foreach (explode('|', $station['services_service']) as $service) { ?>
												<li><?= htmlspecialchars(trim($service)) ?></li>
											<?php } ?>
										</ul>
									<?php } else { ?>
										<p>-</p>
									<?php } ?>
								</td>
							<?php } ?>
							<?php if (!isset($_GET['services'])) { ?>
								<td>
									<a href="resultats.php?departement=<?= urlencode($departement) ?>&ville=<?= urlencode($ville) ?>&style=<?= $styleUrl ?>&lang=<?= $lang ?>&voir_station=<?= htmlspecialchars((string)($station['id'] ?? '')) ?><?php foreach ($carburants_choisis as $c) {
																																																														echo '&carburants[]=' . urlencode($c);
																																																													} ?>#station-<?= htmlspecialchars((string)($station['id'] ?? '')) ?>" class="btn">
										<?= $lang === 'fr' ? 'Services' : 'Services' ?>
									</a>
								</td>
							<?php } ?>
						</tr>
						<?php
						$id_station = (string)($station['id'] ?? '');
						if (!empty($id_station) && isset($_GET['voir_station']) && $_GET['voir_station'] === $id_station) { ?>
							<tr>
								<td colspan="10">
									<?php if (!empty($station['services_service']) && is_array($station['services_service'])) { ?>
										<ul>
											<?php foreach ($station['services_service'] as $service) { ?>
												<li><?= htmlspecialchars(trim($service)) ?></li>
											<?php } ?>
										</ul>
									<?php } else { ?>
										<p><?= $lang === 'fr' ? 'Aucun service disponible' : 'No service available' ?></p>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>

	<?php } ?>
</section>

<section>
	<a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>" class="btn">
		<?= $tr['retour_carte'] ?>
	</a>
</section>

<?php require_once("./includes/footer.inc.php"); ?>