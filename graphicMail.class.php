<?php
class graphicMail
{
    private $username;
    private $password;
    private $APIURL;
    private $userData;

    public function __construct($username, $password) 
    {
        $this->username = (string) $username;
        $this->password = (string) $password;
        $this->APIURL = "https://www.graphicmail.co.uk/api.aspx?Username={$this->username}&Password={$this->password}";
    	$this->userData = array();
    }

    private function sendRequestGraphicMail($mailQuery)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $mailQuery); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch); 
        curl_close($ch); 
        return $output; 
    }

    /*
		Function will return the ID of the newly created Mail List or 0 if the creation failed
    */
    public function newMailList($myMailName) 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=post_create_mailinglist&NewMailinglist={$myMailName}&ReturnMailingListID=true&SID=6";
    	$graphicMailReturned = $this->sendRequestGraphicMail($graphicMailGet);
    	$graphicMailExploded = explode('|', $graphicMailReturned);

    	// Check to see if successful
    	if ($graphicMailExploded[0] == 0) 
    	{
    		return 0;
    	}
    	else
    	{
    		return $graphicMailExploded[1];
    	}
    }

    /*
		Function will return all Mail Lists in an array with the Mail List Name as the key
    */
    public function getMailLists() 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=get_mailinglists&SID=6";
    	$graphicMailReturned = $this->sendRequestGraphicMail($graphicMailGet);

    	$myLists = simplexml_load_string($graphicMailReturned);
    	$totalLists = $myLists->count();
    	$listArray = array();

    	$i = 0;
    	while($i !== $totalLists)
    	{
    		$listArray[(string) $myLists->mailinglist[$i]->description] = (int) $myLists->mailinglist[$i]->mailinglistid;
    		$i++;
    	}
	    return $listArray; 
    }

    /*
		Function will subscribe the email address to the passed Mail List
    */
    public function subMailList($emailAddress, $mailList) 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=post_subscribe&Email={$emailAddress}&MailinglistID={$mailList}&SID=6";
    	$graphicMailReturned = $this->sendRequestGraphicMail($graphicMailGet);
    	$graphicMailExploded = explode('|', $graphicMailReturned);

    	// Check to see if successful
    	if ($graphicMailExploded[0] == 0) 
    	{
    		// Error
    		return 0;
    	}
    	elseif($graphicMailExploded[0] == 1)
    	{
    		// Added Sucessfully
    		return 1;
    	}
    	else
    	{
    		// Email Already Subscribed
    		return 2;
    	}
    }

    /*
		Get a list of all datasets
    */
    public function getDataSets() 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=get_datasets&SID=6";
    	$graphicMailReturned = $this->sendRequestGraphicMail($graphicMailGet);

    	$myLists = simplexml_load_string($graphicMailReturned);
    	$totalLists = $myLists->count();
    	$listArray = array();

    	$i = 0;
    	while($i !== $totalLists)
    	{
    		$listArray[(string) $myLists->dataset[$i]->name] = (int) $myLists->dataset[$i]->datasetid;
    		$i++;
    	}
	    return $listArray; 
    }


    /*
		Set a users data ready to update
    */
    public function setUserData($type, $value)
    {
    	switch ($type) 
    	{
    		case 'mobile':
    			$this->userData['mobile'] = $value;
    			break;
    		case 'fname':
    			$this->userData['fname'] = $value;
    			break;
    		case 'sname':
    			$this->userData['sname'] = $value;
    			break;
    		case 'title':
    			$this->userData['title'] = $value;
    			break;
    		case 'company':
    			$this->userData['company'] = $value;
    			break;
    		case 'jobtitle':
    			$this->userData['jTitle'] = $value;
    			break;
    		case 'worktel':
    			$this->userData['telWork'] = $value;
    			break;
    		case 'workfax':
    			$this->userData['faxWork'] = $value;
    			break;
    		case 'hometel':
    			$this->userData['telHome'] = $value;
    			break;
    		case 'addr1':
    			$this->userData['addressOne'] = $value;
    			break;
    		case 'addr2':
    			$this->userData['addressTwo'] = $value;
    			break;
    		case 'city':
    			$this->userData['city'] = $value;
    			break;
    		case 'county':
    			$this->userData['county'] = $value;
    			break;
    		case 'postcode':
    			$this->userData['postcode'] = $value;
    			break;
    		case 'country':
    			$this->userData['country'] = $value;
    			break;
    		case 'dob':
    			$this->userData['birthday'] = $value;
    			break;
    		case 'gender':
    			$this->userData['gender'] = $value;
    			break;
    		case 'website':
    			$this->userData['website'] = $value;
    			break;
    		case 'imtype':
    			$this->userData['imtype'] = $value;
    			break;
    		case 'imaddress':
    			$this->userData['imaddress'] = $value;
    			break;
    		case 'notes':
    			$this->userData['notes'] = $value;
    			break;
    		default:
    			break;
    	}
    }

    /*
		Update dataset with users data
    */
    public function insertToDataSet($emailAddress,$dataSetId) 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=post_insertdata&Email={$emailAddress}&DatasetID={$dataSetId}";
    	if(isset($this->userData['mobile']))
    		$graphicMailGet .= "&MobileNumber={$this->userData['mobile']}";    	
    	if(isset($this->userData['fname']))
    		$graphicMailGet .= "&Col1={$this->userData['fname']}";    	
    	if(isset($this->userData['sname']))
    		$graphicMailGet .= "&Col2={$this->userData['sname']}";    	
    	if(isset($this->userData['title']))
    		$graphicMailGet .= "&Col3={$this->userData['title']}";    	
    	if(isset($this->userData['company']))
    		$graphicMailGet .= "&Col4={$this->userData['company']}";
    	if(isset($this->userData['jTitle']))
    		$graphicMailGet .= "&Col5={$this->userData['jTitle']}";
    	if(isset($this->userData['telWork']))
    		$graphicMailGet .= "&Col6={$this->userData['telWork']}";
    	if(isset($this->userData['faxWork']))
    		$graphicMailGet .= "&Col7={$this->userData['faxWork']}";
    	if(isset($this->userData['telHome']))
    		$graphicMailGet .= "&Col8={$this->userData['telHome']}";
    	if(isset($this->userData['addressOne']))
    		$graphicMailGet .= "&Col9={$this->userData['addressOne']}";
    	if(isset($this->userData['addressTwo']))
    		$graphicMailGet .= "&Col10={$this->userData['addressTwo']}";
    	if(isset($this->userData['city']))
    		$graphicMailGet .= "&Col11={$this->userData['city']}";
    	if(isset($this->userData['county']))
    		$graphicMailGet .= "&Col12={$this->userData['county']}";
    	if(isset($this->userData['postcode']))
    		$graphicMailGet .= "&Col13={$this->userData['postcode']}";
    	if(isset($this->userData['country']))
    		$graphicMailGet .= "&Col14={$this->userData['country']}";
    	if(isset($this->userData['birthday']))
    		$graphicMailGet .= "&Col15={$this->userData['birthday']}";
    	if(isset($this->userData['gender']))
    		$graphicMailGet .= "&Col16={$this->userData['gender']}";
    	if(isset($this->userData['website']))
    		$graphicMailGet .= "&Col17={$this->userData['website']}";
    	if(isset($this->userData['imtype']))
    		$graphicMailGet .= "&Col18={$this->userData['imtype']}";
    	if(isset($this->userData['imaddress']))
    		$graphicMailGet .= "&Col19={$this->userData['imaddress']}";
    	if(isset($this->userData['notes']))
    		$graphicMailGet .= "&Col20={$this->userData['notes']}";
    	$graphicMailGet = str_replace(' ', '%20', $graphicMailGet);

    	$graphicMailReturned = $this->sendRequestGraphicMail($graphicMailGet);
    	$graphicMailExploded = explode('|', $graphicMailReturned);

    	// Check to see if successful
    	if ($graphicMailExploded[0] == 0) 
    	{
    		// Error
    		return 0;
    	}
    	elseif($graphicMailExploded[0] == 1)
    	{
    		// Inserted Sucessfully
    		return 1;
    	}
    	else
    	{
    		// Updated Sucessfully
    		return 2;
    	}
    	unset($this->userData);
    }
}
?>