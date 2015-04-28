<?php
final class MainController extends Controller
{
	private $headers = array('Content-Type: text/html; charset=utf-8');
	private $messagesInformations = array();
	private $messagesAlertes = array();

	public function __destruct() {

		$_SESSION['messagesInformations'] = $this->session['messagesInformations'];
	}

	public function directoryAction()
	{

		/*******************************************
					Repertoire courant
		*******************************************/
		$dir = (isset($this->get['tree'])) ? $this->get['tree'] : __ROOT_DIR__ ;
		
		/*******************************************
				Creation d'un nouveau dossier
		*******************************************/
		if (isset($this->get['new_folder'])) {

			$pathFolder = $dir . '/' . $this->get['new_folder'];

			if (File::createFolder($pathFolder)) {
				$this->messagesInformations[] = 'Dossier : ' . $this->get['new_folder'] . ' crée'; 
			}
			else {
				$this->messagesAlertes[] = 'Impossible de créer le dossier : ' . $this->get['new_folder'];
			}
		}


		/*******************************************
					
		*******************************************/
		$filesDirectory = $this->getFilesDirectory($dir);

var_dump($this->session['messagesInformations']);
// die(var_dump($this->session['messagesInformations']));
		
		$result = array(
			'headers' => $this->headers,
			'content' => $this->twig->render('directory.html.twig', array(
					'actualDir' => basename($dir) . '/',
					'chemin' => realpath($dir) . '/',
					'filesDirectory' => $filesDirectory,
					'msgInfo' => $this->session['messagesInformations'],
					'msgAlert' => $this->messagesAlertes,
				)),
		);

		$this->session['messagesInformations'] = array();

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

				// if ($type == 'directory' && 
				// 	$file['name'] != '..') {
				// 	//echo 'fichier : ' . $file['name'] . '<br>';
				// 	//$file['children'] = $this->getFilesDirectory($dir . '/' . $file['name']);
				// }

			} else {
				unset($filesDirectory[$key]);
			}
		}

		 //die(var_dump($file));
		return $filesDirectory;
	}

	public function deleteAction()
	{
		if (isset($this->get['delete'])) {

			if (is_dir($this->get['delete'])) {

				if (rmdir($this->get['delete'])) {
					$this->session['messagesInformations'][] = "Dossier suprimé";
				}
				else {
					$this->messagesAlertes[] = "Impossible de supprimer le dossier";
				}

			}
			else {

				if (unlink($this->get['delete'])) {
					$this->session['messagesInformations'][] = "Fichier suprimé"; 
				}
				else {
					$this->messagesAlertes[] = "Impossible de supprimer le fichier";
				}
			}
			
		}

		//return $this->directoryAction();
	}

	public function moveAction()
	{
		/*******************************************
				Déplacement d'un fichier
		*******************************************/
		if (isset($this->post['destination'])) {
			
			$result = File::moveFile($this->post['oldPath'], $this->post['destination']);
			var_dump($result);
		}
	}
}
