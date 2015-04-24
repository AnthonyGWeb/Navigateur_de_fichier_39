<?php
final class MainController extends Controller
{
	private $headers = array('Content-Type: text/html; charset=utf-8');

	public function directoryAction()
	{

		/*******************************************
					Repertoire courant
		*******************************************/
		$dir = (isset($this->get['tree'])) ? $this->get['tree'] : __ROOT_DIR__ ;

		/*******************************************
					
		*******************************************/
		$filesDirectory = $this->getFilesDirectory($dir);

		return array(
			'headers' => $this->headers,
			'content' => $this->twig->render('directory.html.twig', array(
					'actualDir' => basename($dir) . '/',
					'chemin' => realpath($dir) . '/',
					'filesDirectory' => $filesDirectory,
				)),
		);
	}

	public function fileAction()
	{
		if (file_exists($this->get['file'])) {
			$type = File::fileType($this->get['file']);

			return array(
				'headers' => array('Content-Type:' . $type),
				'content' => file_get_contents($this->get['file']),
			);
		}

		return array(
			'headers' => array('HTTP/1.1 404 Not Found'),
			'content' => $this->twig->render('error404.html.twig', array()),
		);

	}

	private function getFilesDirectory($dir)
	{
		// List de tous les fichiers du repertoire
		$filesDirectory = scandir($dir);

		// Boucle sur chaque fichier
		foreach ($filesDirectory as $key => &$file) {
			
			if ($file != '.') {

				$dirFileAbsolute = $dir . '/' . $file;
			
				/*************************************
				  Definition de la taille du fichier
				*************************************/
				$size = File::formatSize($dirFileAbsolute);

				/*************************************
					Definition du type de fichier
				*************************************/
				$type = File::fileType($dirFileAbsolute);

				/*************************************
					Definition de la date du fichier
				*************************************/
				$dateObject = new DateTime();
				$dateObject->setTimestamp(filemtime($dirFileAbsolute));
				$date = $dateObject->format('d-m-Y à H:i');

				/*************************************
						Creation du tableau
				*************************************/

				$file = array(
					'dir' => $dirFileAbsolute,
					'name' => $file,
					'size' => $size,
					'time' => $date,
					'type' => $type,
					'children' => false,
				);

				if ($type == 'directory' && 
					$file['name'] != '..') {
					//echo 'fichier : ' . $file['name'] . '<br>';
					//$file['children'] = $this->getFilesDirectory($dir . '/' . $file['name']);


				}

			} else {
				unset($filesDirectory[$key]);
			}
		}

		 //die(var_dump($file));
		return $filesDirectory;
	}
}
