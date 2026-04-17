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
	fgetcsv($handle, 1000, ',');

	// On lit ligne par ligne
	while (($ligne = fgetcsv($handle, 1000, ',')) !== false) {
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
	fgetcsv($handle, 1000, ',');

	while (($ligne = fgetcsv($handle, 1000, ',')) !== false) {
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
