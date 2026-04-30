<?php

/**
 * @file index.php
 * @brief Page d'accueil de StationFinder.
 * Affiche la carte interactive des régions de France.
 * L'utilisateur clique sur une région pour sélectionner
 * son département puis sa ville.
 *
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

$titre = "Accueil";
$description = "StationFinder - Trouvez les prix des carburants près de chez vous";

require_once("./includes/functions.inc.php");
// Si département et ville sont choisis, on redirige vers resultats.php
if (!empty($_GET['departement']) && !empty($_GET['ville'])) {
	header("Location: resultats.php?departement=" . urlencode($_GET['departement'])
		. "&region=" . urlencode($_GET['region'] ?? '')
		. "&ville=" . urlencode($_GET['ville'])
		. "&style=" . urlencode($_GET['style'] ?? 'standard')
		. "&lang=" . urlencode($_GET['lang'] ?? 'fr'));
	exit;
}
require_once("./includes/header.inc.php");

// Lecture des fichiers CSV
$regions = lire_regions("./data/v_region_2024.csv");
$departements = lire_departements("./data/v_departement_2024.csv");

// Géolocalisation automatique par IP
$ville_geolocalisee = "";

// ipinfo.io JSON :
$ip_visiteur = $_SERVER['REMOTE_ADDR'] ?? '193.54.115.18';
$url_geo = "https://ipinfo.io/" . $ip_visiteur . "/geo";

$lat_utilisateur      = 0.0;
$lon_utilisateur      = 0.0;
$ville_geolocalisee   = "";
$region_geolocalisee  = "";
$dep_geolocalisee     = "";

$contenu_geo = @file_get_contents($url_geo);
if ($contenu_geo !== false) {
    $geo = json_decode($contenu_geo, true);
    if (!empty($geo['city']))   $ville_geolocalisee  = $geo['city'];
    if (!empty($geo['region'])) $region_geolocalisee = $geo['region'];
    // "95000" → les 2 premiers caractères = "95"
    if (!empty($geo['postal'])) $dep_geolocalisee = substr($geo['postal'], 0, 2);
    // "49.0389,2.0781" → on sépare par la virgule
    if (!empty($geo['loc'])) {
        $coords = explode(',', $geo['loc']);
        $lat_utilisateur = (float)$coords[0];
        $lon_utilisateur = (float)$coords[1];
    }
}

// Récupération de la région sélectionnée via GET
$region_selectionnee = "";
if (isset($_GET['region']) && !empty($_GET['region'])) {
	$region_selectionnee = htmlspecialchars($_GET['region']);
}

// Récupération du département sélectionné via GET
$departement_selectionne = "";
if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$departement_selectionne = htmlspecialchars($_GET['departement']);
}

// Si un département est sélectionné, on charge ses villes
$villes = [];
if (!empty($departement_selectionne)) {
	$villes = lire_villes_par_departement($departement_selectionne);
}
?>

<h1>⛽ Bienvenue sur StationFinder</h1>

<section>
    <h2>💰 Faites des économies sur le carburant</h2>
    <p>
        Avec la hausse des prix des carburants, chaque centime compte.
        <strong>StationFinder</strong> vous permet de trouver en temps réel
        les stations-service les moins chères près de chez vous, de votre
        lieu de travail ou de votre trajet habituel.
    </p>
    <p>
        Les données sont issues directement du gouvernement français et
        mises à jour en continu.
    </p>
</section>

<section>
    <h2>Comment ça marche ?</h2>
    <p>Deux façons de trouver les stations près de vous :</p>

    <p><strong>🗺️ Par la carte :</strong></p>
    <ol>
        <li>Cliquez sur votre <strong>région</strong> sur la carte interactive ci-dessous</li>
        <li>Sélectionnez votre <strong>département</strong> puis votre <strong>ville</strong></li>
        <li>Comparez les prix des carburants dans les stations à proximité</li>
    </ol>

    <p><strong>📍 Par géolocalisation :</strong></p>
    <?php if (!empty($ville_geolocalisee) && !empty($region_geolocalisee)) { ?>
        <p>Vous semblez être à <strong><?= htmlspecialchars($ville_geolocalisee) ?></strong>
        (<?= htmlspecialchars($region_geolocalisee) ?>).</p>
        <a href="resultats.php?departement=<?= urlencode($dep_geolocalisee) ?>&lat=<?= $lat_utilisateur ?>&lon=<?= $lon_utilisateur ?>&style=<?= $styleUrl ?>&lang=<?= $lang ?>" class="btn">
            📍 Voir les stations près de moi
        </a>
    <?php } ?>
</section>

<section>
	<h2>Sélectionnez votre région</h2>
	<p>Cliquez sur votre région sur la carte pour trouver les stations-service et les prix des carburants près de chez vous.</p>

	<!-- Carte interactive avec map et area (obligatoire selon le sujet) -->
	<figure>
		<img src="images/carte-regions.webp"
			alt="Carte des régions de France"
			usemap="#carte-france"
			width="750"
			style="cursor:pointer; display:block; margin:0 auto;" />
			<map name="carte-france">
				<area shape="poly" alt="Normandie" title="Normandie" href="index.php?region=28&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="349,149,361,159,361,169,363,190,364,206,361,220,354,224,350,242,333,246,323,252,327,270,320,279,321,291,301,279,296,273,282,275,275,270,266,262,239,268,222,263,208,265,203,257,203,244,202,218,194,203,186,176,208,181,213,177,223,203,234,199,257,205,274,208,293,198,287,185,291,174,309,165,331,160" />
				<area shape="poly" alt="Hauts-de-France" title="Hauts-de-France" href="index.php?region=32&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="350,146,359,156,365,171,366,182,367,208,375,214,388,214,408,218,434,224,450,239,461,228,463,216,461,206,480,200,480,183,491,165,489,156,487,135,482,129,466,129,458,119,452,116,436,97,424,97,410,91,404,72,361,81,359,122,360,138" />
				<area shape="poly" alt="Île-de-France" title="Île-de-France" href="index.php?region=11&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="353,232,359,252,371,264,378,282,389,278,397,278,403,289,419,290,431,274,447,274,454,260,450,242,436,227,431,221,415,225,403,219,387,216,376,216,367,213,363,217,358,222" />
				<area shape="poly" alt="Bretagne" title="Bretagne" href="index.php?region=53&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="199,256,186,253,186,264,174,257,160,255,148,262,137,253,124,240,104,241,98,248,83,251,66,253,47,260,45,273,65,273,55,282,71,290,45,300,57,305,68,313,81,308,91,315,114,320,111,339,124,357,141,332,147,337,156,337,170,338,176,329,188,325,208,316,218,319,225,304,225,287,221,265,207,264" />
				<area shape="poly" alt="Grand Est" title="Grand Est" href="index.php?region=44&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="494,155,507,154,517,140,517,162,534,175,549,188,568,189,577,194,596,194,606,207,618,215,681,230,652,324,649,342,635,342,627,323,611,310,584,306,570,310,561,322,550,328,536,329,526,321,520,308,507,302,489,304,475,304,465,290,453,276,453,260,450,243,461,227,465,216,464,206,481,203,483,179,489,165" />
				<area shape="poly" alt="Pays de la Loire" title="Pays de la Loire" href="index.php?region=52&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="231,267,250,267,268,261,277,273,292,271,311,290,319,291,317,304,316,313,306,325,299,327,292,330,283,362,255,369,236,373,250,417,229,425,212,421,196,415,186,400,178,389,178,375,176,363,163,352,159,344,170,342,181,327,206,321,217,321,227,298,224,284,225,275" />
				<area shape="poly" alt="Centre-Val de Loire" title="Centre-Val de Loire" href="index.php?region=24&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="287,368,302,380,311,378,320,391,342,418,388,415,408,405,417,392,428,389,424,349,424,338,419,323,430,310,432,293,418,293,402,294,401,285,394,283,385,281,378,280,371,270,364,262,360,250,352,239,342,245,327,251,327,265,322,281,320,292,319,315,304,329,292,334,289,348,285,356" />
				<area shape="poly" alt="Bourgogne-Franche-Comté" title="Bourgogne-Franche-Comté" href="index.php?region=27&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="428,292,433,310,427,321,421,325,434,395,456,400,468,408,478,419,474,434,490,434,501,428,510,429,516,436,527,410,537,417,549,427,567,427,578,417,599,381,622,357,629,342,625,320,595,308,577,311,548,332,531,329,514,306,486,307,462,296,453,283,443,276,432,279" />
				<area shape="poly" alt="Nouvelle-Aquitaine" title="Nouvelle-Aquitaine" href="index.php?region=75&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="242,376,274,370,284,366,291,374,311,381,325,404,340,419,357,418,383,416,403,430,406,452,399,458,405,487,395,492,381,516,365,518,351,519,331,547,325,559,316,580,273,592,266,612,279,628,269,650,262,664,242,659,214,650,196,631,205,619,221,546,223,485,220,463,212,447,227,434,229,424,251,421,244,396" />
				<area shape="poly" alt="Auvergne-Rhône-Alpes" title="Auvergne-Rhône-Alpes" href="index.php?region=84&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="393,418,423,394,449,396,470,407,474,426,488,436,500,431,513,433,528,415,543,417,557,424,569,426,579,422,610,417,621,446,629,485,623,506,588,511,595,526,551,557,560,576,535,568,512,570,486,567,470,531,445,521,430,536,415,527,394,540,381,527,391,500,402,485,401,459,408,440" />
				<area shape="poly" alt="Occitanie" title="Occitanie" href="index.php?region=76&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="269,667,280,637,271,612,281,587,310,584,325,572,335,543,353,530,366,517,382,528,388,544,396,541,402,538,415,524,424,540,445,525,467,531,476,560,506,568,517,580,508,606,487,618,466,623,439,643,436,689,408,698,353,682,317,668,294,677" />
				<area shape="rect" alt="Corse" title="Corse" href="index.php?region=94&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="653,765,769,622" />
				<area shape="poly" alt="Provence-Alpes-Côte d'Azur" title="Provence-Alpes-Côte d'Azur" href="index.php?region=93&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="579,422,599,381,622,357,629,390,649,420,661,438,649,458,629,485,623,506,595,526,560,576,551,557,588,511" />
			</map>	
			<figcaption>Carte administrative des régions de France — Source : IGN</figcaption>
	</figure>
</section>

<?php
if (!empty($region_selectionnee) && isset($departements[$region_selectionnee])) {
?>
	<section id="choix-departement">
		<h2>
			Région sélectionnée :
			<?= isset($regions[$region_selectionnee]) ? htmlspecialchars($regions[$region_selectionnee]) : $region_selectionnee ?>
		</h2>

		<form action="index.php#choix-departement" method="get">
			<input type="hidden" name="region" value="<?= $region_selectionnee ?>" />
			<input type="hidden" name="style" value="<?= $styleUrl ?>" />
			<input type="hidden" name="lang" value="<?= $lang ?>" />

			<fieldset>
				<legend>Choisissez votre département et votre ville</legend>

				<label for="departement">Département :</label>
				<select name="departement" id="departement" onchange="this.form.submit()">
					<option value="">-- Sélectionnez un département --</option>
					<?php foreach ($departements[$region_selectionnee] as $dep) { ?>
						<option value="<?= htmlspecialchars($dep['code']) ?>"
							<?= ($dep['code'] === $departement_selectionne) ? 'selected' : '' ?>>
							<?= htmlspecialchars($dep['code']) ?> — <?= htmlspecialchars($dep['nom']) ?>
						</option>
					<?php } ?>
				</select>

				<label for="ville">Ville :</label>
				<select name="ville" id="ville" <?= empty($departement_selectionne) ? 'disabled' : '' ?> required>
					<option value="">-- Sélectionnez une ville --</option>
					<?php foreach ($villes as $nom_ville) { ?>
						<option value="<?= htmlspecialchars($nom_ville) ?>">
							<?= htmlspecialchars($nom_ville) ?>
						</option>
					<?php } ?>
				</select>

				<button type="submit" class="btn" <?= empty($departement_selectionne) ? 'disabled' : '' ?>>
					Voir les stations ⛽
				</button>

			</fieldset>
		</form>
	</section>
<?php
}

require_once("./includes/footer.inc.php");
?>