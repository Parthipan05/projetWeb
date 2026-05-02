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

// Redirection si département + ville choisis
if (!empty($_GET['departement']) && !empty($_GET['ville'])) {
	header("Location: resultats.php?departement=" . urlencode($_GET['departement'])
		. "&region=" . urlencode($_GET['region'] ?? '')
		. "&ville=" . urlencode($_GET['ville'])
		. "&style=" . urlencode($_GET['style'] ?? 'standard')
		. "&lang=" . urlencode($_GET['lang'] ?? 'fr'));
	exit;
}

require_once("./includes/header.inc.php"); // $lang est défini ici
require_once("./includes/traductions.inc.php"); // APRÈS header !
// Lecture des fichiers CSV régions et départements
$regions      = lire_regions("./data/v_region_2024.csv");
$departements = lire_departements("./data/v_departement_2024.csv");

// --- Géolocalisation automatique par IP (API ipinfo.io - format JSON) ---
$ip_visiteur     = $_SERVER['REMOTE_ADDR'] ?? '193.54.115.18';
$url_geo         = "https://ipinfo.io/" . $ip_visiteur . "/geo";
$lat_utilisateur = 0.0;
$lon_utilisateur = 0.0;
$ville_geolocalisee  = "";
$region_geolocalisee = "";
$dep_geolocalisee    = "";

$contenu_geo = @file_get_contents($url_geo);
if ($contenu_geo !== false) {
	$geo = json_decode($contenu_geo, true);
	if (!empty($geo['city']))   $ville_geolocalisee  = $geo['city'];
	if (!empty($geo['region'])) $region_geolocalisee = $geo['region'];
	// Ex: "95000" → les 2 premiers caractères = "95" (code département)
	if (!empty($geo['postal'])) $dep_geolocalisee = substr($geo['postal'], 0, 2);
	// Ex: "49.0389,2.0781" → on sépare latitude et longitude
	if (!empty($geo['loc'])) {
		$coords = explode(',', $geo['loc']);
		$lat_utilisateur = (float)$coords[0];
		$lon_utilisateur = (float)$coords[1];
	}
}

// --- Récupération de la région sélectionnée via GET ---
$region_selectionnee = "";
if (isset($_GET['region']) && !empty($_GET['region'])) {
	$region_selectionnee = htmlspecialchars($_GET['region']);
}

// --- Récupération du département sélectionné via GET ---
$departement_selectionne = "";
if (isset($_GET['departement']) && !empty($_GET['departement'])) {
	$departement_selectionne = htmlspecialchars($_GET['departement']);
}

// --- Chargement des villes si un département est sélectionné ---
$villes = [];
if (!empty($departement_selectionne)) {
	$villes = lire_villes_par_departement($departement_selectionne);
}
?>

<h1><?= $tr['bienvenue'] ?></h1>
<?php
$derniere = get_derniere_consultation();
if ($derniere !== null) {
	echo "<section>
        <p>" . $tr['derniere_consul'] . " 
        <a href='resultats.php?departement=" . urlencode($derniere['departement'])
		. "&ville=" . urlencode($derniere['ville'])
		. "&style=" . $styleUrl
		. "&lang=" . $lang . "' class='btn'>
            " . $derniere['departement']
		. (!empty($derniere['ville']) ? " — " . $derniere['ville'] : "") . "
        </a>
        <span class='texte-discret'>le " . $derniere['date'] . "</span>
        </p>
    </section>";
}
?>

<section>
	<h2><?= $tr['slogan'] ?></h2>
	<p><?= $tr['intro1'] ?></p>
	<p><?= $tr['intro2'] ?></p>
</section>

<section>
	<h2><?= $tr['comment_marche'] ?></h2>
	<p><?= $tr['deux_facons'] ?></p>

	<p><strong><?= $tr['par_carte'] ?></strong></p>
	<ol>
		<li><?= $tr['etape1'] ?></li>
		<li><?= $tr['etape2'] ?></li>
		<li><?= $tr['etape3'] ?></li>
	</ol>

	<p><strong><?= $tr['par_geoloc'] ?></strong></p>
	<?php if (!empty($ville_geolocalisee) && !empty($region_geolocalisee)) { ?>
		<p><?= $tr['vous_etes'] ?> <strong><?= htmlspecialchars($ville_geolocalisee) ?></strong>
			(<?= htmlspecialchars($region_geolocalisee) ?>).</p>
		<a href="resultats.php?departement=<?= urlencode($dep_geolocalisee) ?>&lat=<?= $lat_utilisateur ?>&lon=<?= $lon_utilisateur ?>&style=<?= $styleUrl ?>&lang=<?= $lang ?>" class="btn">
			<?= $tr['voir_stations'] ?>
		</a>
	<?php } ?>
</section>

<section>
	<h2><?= $tr['selectionnez'] ?></h2>
	<p><?= $tr['carte_texte'] ?></p>

	<figure>
		<img src="images/carte-regions.jpg"
			alt="<?= $tr['carte_alt'] ?>"
			usemap="#carte-france"
			width="750"
			style="cursor:pointer; display:block; margin:0 auto;" />
		<map name="carte-france">
			<area target="" alt="Auvergne-Rhône-Alpes" title="Auvergne-Rhône-Alpes" href="index.php?region=84&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="385,363,386,375,394,379,401,401,389,414,395,434,395,449,388,448,372,479,377,509,391,508,407,487,420,506,431,489,443,484,452,497,463,493,485,536,503,533,516,536,531,539,535,524,543,530,546,536,564,547,576,538,564,528,563,518,584,497,612,487,606,474,607,467,621,471,628,467,654,455,656,438,641,420,638,408,647,400,635,381,633,367,606,368,605,377,596,385,588,386,596,368,592,365,582,378,573,377,569,370,563,377,545,360,529,359,521,386,513,379,503,378,496,389,473,384,477,364,464,358,458,344,448,349,432,344,422,338,413,342,402,348,401,356,392,359" shape="poly">
			<area target="" alt="Bourgogne-Franche-Comté" title="Bourgogne-Franche-Comté" href="index.php?region=27&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="437,343,425,335,416,292,417,258,430,242,422,225,425,213,444,209,458,224,470,242,507,239,530,253,529,262,545,270,566,263,588,237,612,243,641,264,646,270,635,282,643,285,614,318,612,331,597,344,592,360,580,373,569,368,563,374,542,357,531,358,520,381,502,377,496,388,476,383,479,363,467,359,456,341,447,348" shape="poly">
			<area target="" alt="Bretagne" title="Bretagne" href="index.php?region=53&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="179,183,185,193,203,193,201,212,206,233,190,248,180,246,151,254,129,271,107,265,85,254,38,229,23,235,7,212,32,201,4,187,11,168,36,167,74,159,100,158,117,185,139,176,160,174" shape="poly">
			<area target="" alt="Centre-Val de Loire" title="Centre-Val de Loire" href="index.php?region=24&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="419,227,428,240,417,256,413,277,422,318,422,335,385,361,328,367,309,350,293,318,275,321,264,305,272,271,292,262,310,227,305,212,316,200,308,182,333,175,344,165,349,189,365,206,374,215,387,214,392,230" shape="poly">
			<area target="" alt="Corse" title="Corse" href="index.php?region=94&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="678,579,748,719" shape="rect">
			<area target="" alt="Grand Est" title="Grand Est" href="index.php?region=44&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="700,151,678,191,669,232,672,265,660,277,648,272,638,255,617,239,592,233,568,257,549,267,531,254,511,237,470,239,447,209,451,188,446,176,458,157,461,146,458,134,476,130,483,104,487,81,506,78,517,65,518,83,521,95,537,101,551,116,568,111,578,117,599,117,620,143,629,139,641,144,656,138,670,148" shape="poly">
			<area target="" alt="Hauts-de-France" title="Hauts-de-France" href="index.php?region=32&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="360,1,401,-1,418,22,427,17,445,38,458,50,482,55,485,81,477,127,458,133,450,167,433,153,358,141,359,97,340,71,351,47,350,8" shape="poly">
			<area target="" alt="Île-de-France" title="Île-de-France" href="index.php?region=11&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="356,194,369,213,387,215,398,229,419,224,423,207,444,207,445,185,443,170,429,154,401,150,376,143,353,143,343,152,347,179" shape="poly">
			<area target="" alt="Normandie" title="Normandie" href="index.php?region=28&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="180,185,186,194,196,189,224,198,250,191,263,203,278,198,299,217,302,221,310,200,306,183,330,174,343,161,342,150,357,138,356,96,339,73,275,102,281,121,213,124,199,101,164,91,176,137" shape="poly">
			<area target="" alt="Nouvelle-Aquitaine" title="Nouvelle-Aquitaine" href="index.php?region=75&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="212,314,263,306,278,323,292,320,324,368,384,365,399,402,386,413,394,446,370,477,353,483,335,478,309,513,311,527,300,528,300,545,269,554,238,561,230,588,245,604,241,620,225,646,200,635,162,629,162,612,144,604,161,588,185,413,176,389,187,380,205,368,228,365" shape="poly">
			<area target="" alt="Occitanie" title="Occitanie" href="index.php?region=76&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="227,647,273,665,286,650,348,671,362,682,399,692,430,686,431,626,452,619,495,597,518,574,527,560,516,539,484,537,462,497,440,489,422,509,407,491,393,511,376,512,370,486,338,479,308,529,302,546,290,554,242,564,233,588,245,604,244,620" shape="poly">
			<area target="" alt="Pays de la Loire" title="Pays de la Loire" href="index.php?region=52&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="209,197,206,232,191,250,144,260,123,275,131,288,137,301,139,325,149,339,187,368,222,365,211,314,260,305,272,269,299,247,308,223,283,212,278,200,260,209,250,194,227,200" shape="poly">
			<area target="" alt="Provence-Alpes-Côte d'Azur" title="Provence-Alpes-Côte d'Azur" href="index.php?region=93&amp;style=<?= $styleUrl ?>&amp;lang=<?= $lang ?>#choix-departement" coords="521,543,531,560,497,601,608,634,683,570,691,543,656,536,641,515,652,495,635,481,629,468,608,473,617,488,569,519,576,536,569,548,543,538" shape="poly">
		</map>
		<figcaption><?= $tr['carte_legende'] ?></figcaption>
	</figure>
</section>

<?php if (!empty($region_selectionnee) && isset($departements[$region_selectionnee])) { ?>
	<section id="choix-departement">
		<h2>
			<?= $tr['region_selectionnee'] ?> :
			<?= isset($regions[$region_selectionnee]) ? htmlspecialchars($regions[$region_selectionnee]) : $region_selectionnee ?>
		</h2>

		<form action="index.php#choix-departement" method="get">
			<input type="hidden" name="region" value="<?= $region_selectionnee ?>" />
			<input type="hidden" name="style" value="<?= $styleUrl ?>" />
			<input type="hidden" name="lang" value="<?= $lang ?>" />

			<fieldset>
				<legend><?= $tr['choisissez'] ?></legend>

				<label for="departement"><?= $tr['departement'] ?> :</label>
				<select name="departement" id="departement">
					<option value=""><?= $tr['select_dep'] ?></option>
					<?php foreach ($departements[$region_selectionnee] as $dep) { ?>
						<option value="<?= htmlspecialchars($dep['code']) ?>"
							<?= ($dep['code'] === $departement_selectionne) ? 'selected' : '' ?>>
							<?= htmlspecialchars($dep['code']) ?> — <?= htmlspecialchars($dep['nom']) ?>
						</option>
					<?php } ?>
				</select>

				<button type="submit" class="btn"><?= $tr['choisissez'] ?></button>

				<?php if (!empty($departement_selectionne) && !empty($villes)) { ?>
					<label for="ville"><?= $tr['ville'] ?> :</label>
					<select name="ville" id="ville" required>
						<option value=""><?= $tr['select_ville'] ?></option>
						<?php foreach ($villes as $nom_ville) { ?>
							<option value="<?= htmlspecialchars($nom_ville) ?>">
								<?= htmlspecialchars($nom_ville) ?>
							</option>
						<?php } ?>
					</select>
					<button type="submit" class="btn"><?= $tr['voir_btn'] ?></button>
				<?php } ?>

			</fieldset>
		</form>
	</section>
<?php } ?>

<?php require_once("./includes/footer.inc.php"); ?>