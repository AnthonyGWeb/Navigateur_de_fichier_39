<?php
abstract class File
{

	/*************************************
	  Formatage de la taille d'un fichier
	*************************************/
	static function formatSize($file)
	{
		$command = 'du -h ' . $file;
		$retourCommand = exec($command);

		$size = preg_replace('/\s\/+.*/', '', $retourCommand);

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
