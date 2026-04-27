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

// Cookie dernière consultation
// Doit être fait AVANT le header (avant tout affichage HTML)
if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$dep_cookie = htmlspecialchars($_GET['departement']);
	// On sauvegarde le département et la date de consultation
	setcookie(
		'derniere_consultation',
		$dep_cookie . '|' . date('d/m/Y H:i'),
		time() + (30 * 24 * 3600), // 30 jours
		'/stationfinder/'           // 4ème paramètre obligatoire
	);
}

require_once("./includes/functions.inc.php");

// Enregistrement de la consultation dans le CSV
if (!empty($_GET['departement']) && !empty($_GET['ville'])) {
	enregistrer_consultation(
		htmlspecialchars($_GET['departement']),
		htmlspecialchars($_GET['ville'])
	);
}

require_once("./includes/header.inc.php");

// Récupération et sécurisation des paramètres GET
$departement = "";
$region = "";
$ville = "";

if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$departement = htmlspecialchars($_GET['departement']);
}
if (isset($_GET['region']) && !empty($_GET['region'])) {
	$region = htmlspecialchars($_GET['region']);
}
if (isset($_GET['ville']) && !empty($_GET['ville'])) {
	$ville = htmlspecialchars($_GET['ville']);
}

// Vérification qu'un département ou une ville a bien été sélectionné
if (empty($departement) && empty($ville)) {
	echo "<p>Veuillez sélectionner un département.</p>";
	require_once("./includes/footer.inc.php");
	exit;
}
?>

<h1>⛽ Stations-service —
	<?php if (!empty($ville) && !empty($departement)) { ?>
		<?= htmlspecialchars($ville) ?> (<?= htmlspecialchars($departement) ?>)
	<?php } elseif (!empty($ville)) { ?>
		<?= htmlspecialchars($ville) ?>
	<?php } else { ?>
		Département <?= htmlspecialchars($departement) ?>
	<?php } ?>
</h1>
<?php
// Lecture du cookie dernière consultation
if (isset($_COOKIE['derniere_consultation']) && !empty($_COOKIE['derniere_consultation'])) {
	$cookie_data = explode('|', $_COOKIE['derniere_consultation']);
	if (count($cookie_data) === 2) {
		$dep_precedent = htmlspecialchars($cookie_data[0]);
		$date_precedent = htmlspecialchars($cookie_data[1]);
		echo "<p class='texte-discret'>
			🕐 Dernière consultation : département <strong>" . $dep_precedent . "</strong>
			le " . $date_precedent . "
		</p>";
	} else {
		// Valeur erronée → on supprime le cookie
		setcookie('derniere_consultation', '', time() - 3600, '/stationfinder/');
	}
}
?>
<section>
	<?php
	// Appel de l'API gouvernementale (format JSON)
	// On filtre par département, et par ville si elle est renseignée
	if (!empty($departement)) {
		$filtre = "code_departement%3D%22" . rawurlencode($departement) . "%22";
		if (!empty($ville)) {
			$ville_api = trim($ville);
			$filtre .= "%20AND%20suggest(ville%2C%22" . rawurlencode($ville_api) . "%22)";
		}
	} else {
		$ville_api = trim($ville);
		$filtre = "ville%3D%22" . rawurlencode($ville_api) . "%22";
	}

	$url_api = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?"
		. "where=" . $filtre
		. "&limit=50"
		. "&timezone=Europe%2FParis";

	$json_brut = file_get_contents($url_api);
	$donnees = json_decode($json_brut, true);

	// Vérification de la réponse
	if ($donnees === null || !isset($donnees['results'])) {
		echo "<p>Impossible de récupérer les données. Veuillez réessayer.</p>";
	} elseif (count($donnees['results']) === 0) {
		echo "<p>Aucune station trouvée dans ce département.</p>";
	} else {
		$stations = $donnees['results'];

		// --- Récupération des filtres carburants ---
		$carburants_choisis = $_GET['carburants'] ?? ['sp95', 'sp98', 'gazole', 'e10', 'gplc'];
		$tri = $_GET['tri'] ?? '';

		// --- Tri des stations par prix ---
		if ($tri === 'asc' || $tri === 'desc') {
			// On calcule le prix minimum de chaque station pour trier
			usort($stations, function ($a, $b) use ($tri, $carburants_choisis) {
				$prix_a = [];
				$prix_b = [];
				foreach ($carburants_choisis as $c) {
					if (!empty($a[$c . '_prix'])) $prix_a[] = $a[$c . '_prix'];
					if (!empty($b[$c . '_prix'])) $prix_b[] = $b[$c . '_prix'];
				}
				$min_a = !empty($prix_a) ? min($prix_a) : 9999;
				$min_b = !empty($prix_b) ? min($prix_b) : 9999;
				return ($tri === 'asc') ? $min_a <=> $min_b : $min_b <=> $min_a;
			});
		}
		if (!empty($ville)) {
			echo "<p>" . count($stations) . " station(s) trouvée(s) à <strong>" . $ville . "</strong>.</p>";
		} else {
			echo "<p>" . count($stations) . " station(s) trouvée(s) dans le département <strong>" . $departement . "</strong>.</p>";
		}
	?>

		<form action="resultats.php#resultats" method="get">
			<input type="hidden" name="departement" value="<?= htmlspecialchars($departement) ?>" />
			<input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>" />
			<input type="hidden" name="style" value="<?= $styleUrl ?>" />
			<input type="hidden" name="lang" value="<?= $lang ?>" />

			<fieldset>
				<legend>Filtrer les carburants</legend>
				<label><input type="checkbox" name="carburants[]" value="sp95" <?= (!isset($_GET['carburants']) || in_array('sp95', $_GET['carburants'])) ? 'checked' : '' ?>> SP95</label>
				<label><input type="checkbox" name="carburants[]" value="sp98" <?= (!isset($_GET['carburants']) || in_array('sp98', $_GET['carburants'])) ? 'checked' : '' ?>> SP98</label>
				<label><input type="checkbox" name="carburants[]" value="gazole" <?= (!isset($_GET['carburants']) || in_array('gazole', $_GET['carburants'])) ? 'checked' : '' ?>> Gazole</label>
				<label><input type="checkbox" name="carburants[]" value="e10" <?= (!isset($_GET['carburants']) || in_array('e10', $_GET['carburants'])) ? 'checked' : '' ?>> E10</label>
				<label><input type="checkbox" name="carburants[]" value="gplc" <?= (!isset($_GET['carburants']) || in_array('gplc', $_GET['carburants'])) ? 'checked' : '' ?>> GPL</label>

				<label>Trier par prix :
					<select name="tri">
						<option value="">-- Aucun tri --</option>
						<option value="asc" <?= (isset($_GET['tri']) && $_GET['tri'] === 'asc') ? 'selected' : '' ?>>Croissant</option>
						<option value="desc" <?= (isset($_GET['tri']) && $_GET['tri'] === 'desc') ? 'selected' : '' ?>>Décroissant</option>
					</select>
				</label>

				<button type="submit" class="btn">Appliquer</button>
			</fieldset>
		</form>

		<div id="resultats">

			<table>
				<thead>
					<tr>
						<th>Station</th>
						<th>Adresse</th>
						<th>Ville</th>
						<?php if (in_array('sp95', $carburants_choisis)) { ?><th>SP95</th><?php } ?>
						<?php if (in_array('sp98', $carburants_choisis)) { ?><th>SP98</th><?php } ?>
						<?php if (in_array('gazole', $carburants_choisis)) { ?><th>Gazole</th><?php } ?>
						<?php if (in_array('e10', $carburants_choisis)) { ?><th>E10</th><?php } ?>
						<?php if (in_array('gplc', $carburants_choisis)) { ?><th>GPL</th><?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($stations as $station) { ?>
						<tr>
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
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>

	<?php } ?>
</section>

<section>
	<a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>" class="btn">
		← Retour à la carte
	</a>
</section>

<?php require_once("./includes/footer.inc.php"); ?>