<?php
final class MainController extends Controller
{
	private $headers = array('Content-Type: text/html; charset=utf-8');
	private $messagesInformations = array();
	private $messagesAlertes = array();

	public function __destruct() {

		$_SESSION['messagesInformations'] = $this->messagesInformations;
		$_SESSION['messagesAlertes'] = $this->messagesAlertes;
	}

	public function directoryAction()
	{
		/*******************************************
					Repertoire courant
		*******************************************/
		$dir = $this->actualPath;

		/*******************************************
					
		*******************************************/
		$this->session['messagesInformations'] = (!isset($this->session['messagesInformations'])) ? null : $this->session['messagesInformations'];

		$this->session['messagesAlertes'] = (!isset($this->session['messagesAlertes'])) ? null : $this->session['messagesAlertes'];

		$filesDirectory = $this->getFilesDirectory($dir);
		
		$result = array(
			'headers' => $this->headers,
			'content' => $this->twig->render('directory.html.twig', array(
					'actualDir' => basename($dir) . '/',
					'chemin' => realpath($dir) . '/',
					'pathTree' => preg_replace('/([\w\d\s_-])\//', '$1 > ', $dir),
					'parentPath' => dirname($dir),
					'filesDirectory' => $filesDirectory,
					'msgInfo' => $this->session['messagesInformations'],
					'msgAlert' => $this->session['messagesAlertes'],
				)),
		);

		//$this->session['messagesInformations'] = array();

		return $result;
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
				if ($file != '..') {
					$size = File::fileSize($dirFileAbsolute);
				}
				else {
					$size = 0;
				}

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

			} else {
				unset($filesDirectory[$key]);
			}
		}

		return $filesDirectory;
	}

	public function deleteAction()
	{
		if (isset($this->get['delete'])) {

			if (is_dir($this->get['delete'])) {

				$command = 'rm -R -v ' . $this->get['delete'];

				exec($command, $outputArray);

				if (count($outputArray) !== 0) {

					foreach ($outputArray as $output) {
					$this->messagesInformations[] = $output;
					}
				}
				else {
					$this->messagesAlertes[] = 'Impossible de supprimer ' . $this->get['delete'];
				}
			}
			else {

				if (unlink($this->get['delete'])) {
					$this->messagesInformations[] = 'Fichier ' . $this->get['delete'] . ' suprimé'; 
				}
				else {
					$this->messagesAlertes[] = 'Impossible de supprimer ' . $this->get['delete'];
				}
			}
		}
	}

	public function moveAction()
	{
		/*******************************************
				Déplacement d'un fichier
		*******************************************/
		if (isset($this->post['destination'])) {
			
			$result = File::moveFile($this->post['oldPath'], $this->post['destination']);
			// var_dump($result);
		}
	}

	public function createFolderAction()
	{
		/*******************************************
				Creation d'un nouveau dossier
		*******************************************/
		if (isset($this->get['new_folder'])) {

			$pathFolder = $this->actualPath . '/' . $this->get['new_folder'];

			if (File::createFolder($pathFolder)) {
				$this->messagesInformations[] = 'Dossier : ' . $this->get['new_folder'] . ' crée'; 
			}
			else {
				$this->messagesAlertes[] = 'Impossible de créer le dossier : ' . $this->get['new_folder'];
			}
		}

		return $this->directoryAction();
	}

	public function uploadAction()
	{
		$arrayType = [
			
		];

		foreach ($this->files as $file) {

			for ($i=0; $i < count($file['name']); $i++) { 
				
				if ($file['size'][$i] < pow(2048, 2)) {

					if (!in_array($file['type'][$i], $arrayType)) {
						$tmp_name = $file['tmp_name'][$i];
						$destination = $this->actualPath . '/' . $file['name'][$i];

						if (move_uploaded_file($tmp_name, $destination)) {
							$this->messagesInformations[] =  'Upload reussi de : ' . $file['name'][$i] . "\n" . 'vers : ' . $destination;
						}
						else {
							$this->messagesAlertes[] = 'Copie impossible Erreur 001 : ' . $destination;
						}
					}
					else {
						$this->messagesAlertes[] = 'Format incorrect';
					}

				}
				else {
					unlink($file['tmp_name'][$i]);
					$this->messagesAlertes[] = 'Fichier trop gros';
				}
			}	
		}

		return array(
			'headers' => array('Location: ?tree=' . $this->actualPath),
			'content' => '',
		);
	}
}
