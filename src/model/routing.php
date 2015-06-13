<?php
final class Routing
{
	/**
	 * Routing
	 *
	 * @param array $get // Calling route.
	 *
	 * @return string
	 *
	 */
	public function getRoute($get)
	{		
		// var_dump($get);
		if (isset($get['moveFile'])) {
			return 'move';
		}

		if (isset($get['action'])) {
			if ($get['action'] == 'upload') {
				return 'upload';
			}
		}

		if (isset($get['delete'])) {
			return 'delete';
		}

		if (isset($get['new_folder'])) {
			return 'createFolder';
		}

		if (isset($get['file'])) {
			return 'file';
		}

		return 'directory';
	}
}

