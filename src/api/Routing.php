<?php


class Routing
{
	const ROUTING_CONFIG =  '/config/routing.json';

	private $post;

	public function __construct($post)
	{
		$this->setPost($post);
	}

	private function setPost($post)
	{
		$this->post = $post;
	}

	public function getAction()
	{
		if (array_key_exists('action', $this->post)) {
			
			$jsonConfig = file_get_contents(ROOT_DIR . self::ROUTING_CONFIG);
			$routes = json_decode($jsonConfig, true);

			if (array_key_exists($this->post['action'], $routes)) {
				
				return array(
					'action' => $this->post['action'] . 'Action',
					'data' => $routes[$this->post['action']]['data'],
				);
			}
		}
		
		throw new Exception("No action for this request");
	}
}
