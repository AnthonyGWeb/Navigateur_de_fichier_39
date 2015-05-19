<?php
abstract class File
{
	/**
	 *
	 *	Define size file
	 *
	 * @param string $file // path file absolute
	 *
	 * @return string $size // Size file
	 */
	static function fileSize($file)
	{
		// Execute command 'du'
		$command = 'du -h \'' . $file . '\'';
		$retourCommand = exec($command);

		// Return only size and format
		$size = preg_replace('/\s\/+.*/', '', $retourCommand);

		return $size;
	}

	/**
	 *
	 * Return the type file
	 *
	 * @param string $file // path file absolute
	 *
	 * @return string $type // Type file
	 */
	static function fileType($file)
	{
		$type = null;

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$type = finfo_file($finfo, $file);

		finfo_close($finfo);

		return $type;
	}

	/**
	 *
	 * Create a new folder
	 *
	 * @param string $nameFolder // Name for the new folder
	 *
	 * @return boolean
	 */
	static function createFolder($nameFolder)
	{
		if (mkdir($nameFolder, 0744)) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * Move folder or file
	 *
	 * @param string $old // path old file
	 * @param string $new // path new file
	 *
	 * @return boolean
	 */
	static function moveFile($old, $new)
	{
		if (rename($old, $new)) {
			return true;
		}

		return false;
	}
}
