<?php
final class Routing
{
	public function getRoute($get)
	{		
		// die(var_dump($get));
		if (isset($get['file'])) {
			return 'file';
		}

		return 'directory';
	}
}

