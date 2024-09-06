<?php
use MX\MX_Controller;
class restore extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->user->userArea();
		$this->load->config('config');
	}

	public function index()
	{
		$this->template->setTitle("Restore Character Deleted");

		$content_data = array(

			"url" => $this->template->page_url,
			"dp" => $this->user->getDp(),
			"config" => $this->config,
			"this" => $this,
            "realms" => $this->realms->getRealms(),
		);
		
		$page_content = $this->template->loadPage("restore.tpl", $content_data);
		
		//Load the page
		$page_data = array(
			"module" => "default", 
			"headline" => "Restore Character Deleted ",
			"content" => $page_content
		);
		
		$page = $this->template->loadPage("page.tpl", $page_data);
		
		$this->template->view($page, "modules/restore/css/restore.css", "modules/restore/js/restore.js");
	}
	
	public function submit()
	{

		$characterGuid = $this->input->post('guid'); 
		$realmId = $this->input->post('realm');
        $Price = $this->config->item("type_price");

        if($this->getOnlineAccount())
        {
            die("account is online");
        }
		if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole())
		{
				die(lang("relamdoesnotsupport","restore"));
		}
		
		if ( $characterGuid && $realmId)
		{
				$realmConnection = $this->realms->getRealm($realmId)->getCharacters();
				$realmConnection->connect();

				if (!$realmConnection->characterExists($characterGuid))
				{
						die(lang("noselectedcharacter","restore"));
				}

                $CharacterName = $this->getNameCharacter($realmId,$characterGuid);

            if($this->getCheckNameForRestore($realmId,$CharacterName))
            {
                die(lang("name_already_exists","restore"));

            }

                if($Price == 0) /// Free
                {
                    $this->realms->getRealm($realmId)->getEmulator()->sendCommand('.character deleted restore '.$CharacterName);
                     die("1");
                }
				if ($this->user->getDp() >= $Price) // Only DP
				{

                    $this->realms->getRealm($realmId)->getEmulator()->sendCommand('.character deleted restore '.$CharacterName);

					if ($Price > 0)
					{
						$this->user->setDp($this->user->getDp() - $Price);
				      	die("1");
					}
				}
				else 
				{
					die(lang("notenough","restore"));
				}
		}
		else
		{
			die(lang("Theinputisinvalid","restore"));
		}
	}
    public function GetCountDeleteAccount($realmId = 1)
    {

        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT COUNT(*) AS total FROM characters WHERE deleteInfos_Account= ?", array($this->user->getId()));
        if ($query && $query->getNumRows() > 0)
        {
            $results = $query->getResultArray();

            return (int)$results[0]['total'];
        }
        return 0;
    }
    public function Getcharacterdeleted($realmId = 1)
    {

        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();

        $query = $character_database->getConnection()->query("SELECT guid,race,gender, class, level,deleteInfos_Account,deleteInfos_Name,deleteDate FROM characters WHERE deleteInfos_Account= ?", array($this->user->getId()));
        if($query->getNumRows() > 0)
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }

    public function getNameCharacter($realmId,$guid)
    {

        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE  guid = ? and deleteInfos_Account= ?", [$guid,$this->user->getId()]);
        if ($query && $query->getNumRows() > 0)
        {
            $results = $query->getResultArray();

            return $results[0]['deleteInfos_Name'];
        }
        return 0;
    }

    public function getOnlineAccount()
    {

        $this->connection = $this->external_account_model->getConnection();
        $query = $this->connection->query("SELECT * FROM account WHERE  id = ? AND online = 1",[$this->user->getId()]);
        if($query->getNumRows())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function getCheckNameForRestore($realmId,$name)
    {
        $character_database = $this->realms->getRealm($realmId)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE  name = ? ", [$name]);
        if ($query && $query->getNumRows() > 0)
            return true;
        else
        return false;
    }
     
 }