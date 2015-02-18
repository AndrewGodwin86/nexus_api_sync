<?php
include('./guzzle.phar');
include('./NexusApiSettings.conf.php');

use Guzzle\Http\Client;

class NexusAPIClient{
	private $apiClient;
	private $username;
	private $password;
	
	public function __construct ($args = null){
		//parent::__construct($args);		
		$this->client = new Client(KA_API_BASEURL.'/api/{version}', array(
		    'version' => KA_API_VERSION,
				'request.options' => array(
				        //'headers' => array('Accept' => 'text/json'),
				        //'query'   => array('testing' => '123'),
				        'auth'    => array(KA_API_CLIENT_ID, KA_API_CLIENT_KEY, 'Basic'),
				        //'proxy'   => 'tcp://localhost:80'
				    )
		));
	}
	
	public function GetEntiteesUpdatedSince($entityType,$lastModDate)
	{
		date_default_timezone_set('America/Los_Angeles');
		$datetime = strtotime($lastModDate);
		$formattedDate = date("Y-m-d H:i:s T", $datetime);
		//$request = $this->client->get($entityType.'?perPage=50&pageNum=1&minModDate='.$formattedDate);
		$request = $this->client->get($entityType.'?perPage=100');

		$response = $request->send();

		$data = $response->json();
		
		return $data['results'];		
	}

	public function GetEntityByID($entityType, $entityID)
	{
		$request = $this->client->get($entityType.'/'.$entityID);
		
		$response = $request->send();

		$data = $response->json();
		
		return $data['results'][0];
	}

	public function GetRelatedEntityByEntityID($relatedEntity, $entityType, $entityID)
	{
		$request = $this->client->get($entityType.'/'.$entityID.'/'.$relatedEntity);
		
		$response = $request->send();

		$data = $response->json();
		
		return $data['results'];		
	}

	public function GetProjectMediaByID($projectID)
	{
		$request = $this->client->get('projects/'.$projectID.'/media');
		
		$response = $request->send();

		$data = $response->json();
		
		return $data['results'];
	}

	public function GetProjectDescriptionsByID($projectID)
	{
		$request = $this->client->get('projects/'.$projectID.'/descriptions');
		
		$response = $request->send();

		$data = $response->json();
		
		return $data['results'];
	}

	public function GetProjectsBySector($sector)
	{
		$request = $this->client->get('projects?projectType='.$sector);
		
		$response = $request->send();

		$data = $response->json();
		
		return $data['results'];
	}

	public function GetPublicMediaURLByMediaID($mediaID, $size = 'orig')
	{
		try
		{

			$request = $this->client->get('media/'.$mediaID.'/url/'.$size);

			$response = $request->send();

			$data = $response->json();

			if (isset($data['results']) && count($data['results'])>0){
				return $data['results'][0];
			}
			else return null;
		}
		catch (Exception $e)
		{
			echo "<!--Exception getting media url: ".$e."//-->";
		}

	}

}