<?php
abstract class File
{

	/*************************************
	  Formatage de la taille d'un fichier
	*************************************/
	static function formatSize($file)
	{
		$size = 'N-A';

		if (filesize($file) > 0) {
			$size = (int) filesize($file) . ' o';
		}

		if (filesize($file) > 999) {
			$size = (int) (filesize($file) / 1000) . ' Ko';
		}

		if (filesize($file) > 999999) {
			$size = (int) (filesize($file) / 1000000) . ' Mo';
		}

		if (filesize($file) > 999999999) {
			$size = (int) (filesize($file) / 1000000000) . ' Go';
		}

		return $size;
	}

	/*************************************
	  Formatage de la taille en octet d'un dossier
	*************************************/
	static function formatSizeFolder($int)
	{
		$size = 'N-A';

		if ($int > 0) {
			$size = (int) $int . ' o';
		}

		if ($int > 999) {
			$size = (int) ($int / 1000) . ' Ko';
		}

		if ($int > 999999) {
			$size = (int) ($int / 1000000) . ' Mo';
		}

		if ($int > 999999999) {
			$size = (int) ($int / 1000000000) . ' Go';
		}

		return $size;
	}

	/*************************************
	  Calcul de la taille d'un fichier
	*************************************/
	static function fileSize($file)
	{		
		$size = (int) filesize($file);
		
		return $size;
	}

	/*************************************
		Retourne le type d'un fichier
	*************************************/
	static function fileType($file)
	{
		$type = null;

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $file);

		finfo_close($finfo);

		return $type;
	}

	/*************************************
			CREATION D UN FICHIER
	*************************************/
	static function createFolder($nameFolder)
	{
		if (mkdir($nameFolder, 0744)) {
			return true;
		}

		return false;
	}

	/*************************************
		Deplacement D UN FICHIER ou dossier
	*************************************/
	static function moveFile($old, $new)
	{
		if (rename($old, $new)) {
			return true;

		}

		return false;
	}
}
