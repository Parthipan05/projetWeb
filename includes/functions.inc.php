<?php

/**
 * @file functions.inc.php
 * @brief Fonctions utilitaires du site StationFinder.
 * @author PIRABAKARAN Parthipan & HANANE Sanaa
 * @date 2026
 */

declare(strict_types=1);

/**
 * @brief Lit le fichier CSV des régions et retourne un tableau associatif.
 *
 * @param string $fichier Chemin vers le fichier CSV des régions.
 * @return array Tableau associatif [code_region => nom_region]
 */
function lire_regions(string $fichier): array
{
	$regions = [];

	// On ouvre le fichier CSV
	$handle = fopen($fichier, 'r');
	if ($handle === false) {
		return $regions;
	}

	// On ignore la première ligne (en-tête des colonnes)
	fgetcsv($handle, 1000, ',', '"', '\\');
	// On lit ligne par ligne
	while (($ligne = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
		// $ligne[0] = REG (code région)
		// $ligne[5] = NCCENR (nom avec accents)
		$code = $ligne[0];
		$nom  = $ligne[5];

		// On garde uniquement la France métropolitaine (codes >= 11)
		if ((int)$code >= 11) {
			$regions[$code] = $nom;
		}
	}

	fclose($handle);
	return $regions;
}

/**
 * @brief Lit le fichier CSV des départements et retourne un tableau associatif.
 *
 * @param string $fichier Chemin vers le fichier CSV des départements.
 * @return array Tableau associatif [code_region => [['code' => ..., 'nom' => ...], ...]]
 */
function lire_departements(string $fichier): array
{
	$departements = [];

	$handle = fopen($fichier, 'r');
	if ($handle === false) {
		return $departements;
	}

	// On ignore l'en-tête
	fgetcsv($handle, 1000, ',', '"', '\\');

	while (($ligne = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
		// $ligne[0] = DEP (code département)
		// $ligne[1] = REG (code région)
		// $ligne[5] = NCCENR (nom avec accents)
		$code_dep    = $ligne[0];
		$code_region = $ligne[1];
		$nom_dep     = $ligne[5];

		// On regroupe les départements par région
		$departements[$code_region][] = [
			'code' => $code_dep,
			'nom'  => $nom_dep
		];
	}

	fclose($handle);
	return $departements;
}
/**
 * @brief Lit le fichier CSV et retourne les statistiques de consultation.
 *
 * @param string $fichier Chemin vers le fichier CSV.
 * @return array Tableau associatif [departement => nombre_consultations]
 */
function lire_statistiques(string $fichier): array
{
	$stats = [];

	if (!file_exists($fichier)) {
		return $stats;
	}

	$handle = fopen($fichier, 'r');
	if ($handle === false) {
		return $stats;
	}

	while (($ligne = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
		if (isset($ligne[1]) && !empty(trim($ligne[1]))) {
			$dep = 'dep_' . trim($ligne[1]);
			if (isset($stats[$dep])) {
				$stats[$dep]++;
			} else {
				$stats[$dep] = 1;
			}
		}
	}

	fclose($handle);
	arsort($stats);

	return $stats;
}

/**
 * @brief Lit le fichier CSV des communes et retourne la liste des villes
 *        d'un département donné, triée alphabétiquement.
 *
 * @param string $departement Code du département (ex: "95", "2A").
 * @return array Tableau trié des noms de communes.
 */
/**
 * @brief Récupère la liste des villes d'un département depuis l'API carburants.
 *
 * @param string $departement Code du département.
 * @return array Liste triée des villes.
 */
function lire_villes_par_departement(string $departement): array
{
	$url = "https://data.economie.gouv.fr/api/explore/v2.1/catalog/datasets/prix-des-carburants-en-france-flux-instantane-v2/records?"
		. "where=code_departement%3D%22" . rawurlencode($departement) . "%22"
		. "&select=ville"
		. "&limit=100"
		. "&timezone=Europe%2FParis";

	$json = file_get_contents($url);
	$data = json_decode($json, true);

	if ($data === null || !isset($data['results'])) {
		return [];
	}

	$villes = [];
	foreach ($data['results'] as $station) {
		if (!empty($station['ville'])) {
			$villes[] = $station['ville'];
		}
	}

	// Supprimer les doublons et trier
	$villes = array_unique($villes);
	sort($villes);

	return array_values($villes);
}

/**
 * @brief Enregistre une consultation dans le fichier CSV côté serveur.
 *        Chaque ligne : horodatage, département, ville, IP du visiteur.
 *
 * @param string $departement Code du département consulté (ex: "95").
 * @param string $ville       Nom de la ville consultée.
 * @return bool true si l'écriture a réussi, false sinon.
 */
function enregistrer_consultation(string $departement, string $ville): bool
{
	// On récupère l'IP du visiteur (inconnue si non disponible)
	$ip = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';

	// On formate l'horodatage au format lisible
	$horodatage = date('Y-m-d H:i:s');

	// On construit la ligne CSV avec les 4 colonnes entre guillemets
	// pour gérer les noms de villes avec des virgules (ex: "Bourg-en-Bresse")
	$ligne_csv = '"' . $horodatage . '"'
		. ',' . $departement
		. ',"' . addslashes($ville) . '"'
		. ',' . $ip
		. PHP_EOL;

	// file_put_contents avec FILE_APPEND : ajoute à la fin sans écraser
	$resultat = file_put_contents(
		'./data/consultations.csv',
		$ligne_csv,
		FILE_APPEND
	);

	// file_put_contents retourne false si l'écriture a échoué
	return ($resultat !== false);
}
