<?php
abstract class Controller
{
	protected $get;
	protected $post;
	protected $session;
	protected $server;
	protected $routing;
	protected $twig;
	protected $actualPath;

	public function __construct($post, $get, $session, $server)
	{
		$this->setGet($get)
			 ->setPost($post)
			 ->setSession($session)
			 ->setServer($server);

		$this->actualPath = (isset($this->get['tree'])) ? realpath($this->get['tree']) : __ROOT_DIR__ ;

		$this->routing = new Routing();
		$loader = new Twig_Loader_Filesystem(__ROOT_DIR__ . '/src/views/');
		$this->twig = new Twig_Environment($loader, array(
			'cache' => false,
			'debug' => true,
			'strict_variables' => true,
			'charset' => 'utf-8',
		));
	}

	/********************************************
					Getters
	********************************************/
	public function getPost()
	{
		return $this->post;
	}

	public function getGet()
	{
		return $this->get;
	}

	public function getSession()
	{
		return $this->session;
	}

	public function getServer()
	{
		return $this->server;
	}

	public function getRouting()
	{
		return $this->routing;
	}

	public function getTwig()
	{
		return $this->twig;
	}

	/********************************************
					Setters
	********************************************/
	public function setPost(array $data)
	{
		$this->post = $data;
		return $this;
	}
		
	public function setGet(array $data)
	{
		$this->get = $data;
		return $this;
	}

	public function setSession(array $data)
	{
		$this->session = $data;
		return $this;
	}
		
	public function setServer(array $data)
	{
		$this->server = $data;
		return $this;
	}

	/********************************************
					Methodes
	********************************************/
	public function handleRequest()
	{
		$route = $this->routing->getRoute($this->get);
		unset($this->routing);
		$response = $this->{$route . 'Action'}();
		
		foreach($response['headers'] as $header) {
			header($header);
		}
		
		echo $response['content'];
	}
}
