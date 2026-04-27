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

// Vérification qu'un département a bien été sélectionné
if (empty($departement)) {
	echo "<p>Veuillez sélectionner un département.</p>";
	require_once("./includes/footer.inc.php");
	exit;
}
?>

<h1>⛽ Stations-service — Département <?= $departement ?></h1>
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
	$filtre = "code_departement%3D%22" . urlencode($departement) . "%22";
	if (!empty($ville)) {
		$filtre .= "%20AND%20ville%3D%22" . rawurlencode($ville) . "%22";
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
		if (!empty($ville)) {
			echo "<p>" . count($stations) . " station(s) trouvée(s) à <strong>" . $ville . "</strong>.</p>";
		} else {
			echo "<p>" . count($stations) . " station(s) trouvée(s) dans le département <strong>" . $departement . "</strong>.</p>";
		}
	?>

		<table>
			<thead>
				<tr>
					<th>Station</th>
					<th>Adresse</th>
					<th>Ville</th>
					<th>SP95</th>
					<th>SP98</th>
					<th>Gazole</th>
					<th>E10</th>
					<th>GPL</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($stations as $station) { ?>
					<tr>
						<td><?= htmlspecialchars($station['nom'] ?? 'N/A') ?></td>
						<td><?= htmlspecialchars($station['adresse'] ?? 'N/A') ?></td>
						<td><?= htmlspecialchars($station['ville'] ?? 'N/A') ?></td>
						<td><?= !empty($station['sp95_prix']) ? htmlspecialchars((string)$station['sp95_prix']) . ' €' : '-' ?></td>
						<td><?= !empty($station['sp98_prix']) ? htmlspecialchars((string)$station['sp98_prix']) . ' €' : '-' ?></td>
						<td><?= !empty($station['gazole_prix']) ? htmlspecialchars((string)$station['gazole_prix']) . ' €' : '-' ?></td>
						<td><?= !empty($station['e10_prix']) ? htmlspecialchars((string)$station['e10_prix']) . ' €' : '-' ?></td>
						<td><?= !empty($station['gplc_prix']) ? htmlspecialchars((string)$station['gplc_prix']) . ' €' : '-' ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

	<?php } ?>
</section>

<section>
	<a href="index.php?style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>" class="btn">
		← Retour à la carte
	</a>
</section>

<?php require_once("./includes/footer.inc.php"); ?>