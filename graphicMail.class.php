<?php
class graphicMail
{
    private $username;
    private $password;
    private $APIURL;

    public function __construct($username, $password) 
    {
        $this->username = (string) $username;
        $this->password = (string) $password;
        $this->APIURL = "https://www.graphicmail.co.uk/api.aspx?Username={$this->username}&Password={$this->password}";
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

    public function insertToDataSet($emailAddress, $dataSetId, $firstName = false, $surName = false, $theTitle = false, $mobileNumber = false) 
    {
    	$graphicMailGet = "{$this->APIURL}&Function=post_insertdata&Email={$emailAddress}&DatasetID={$dataSetId}&MobileNumber={$mobileNumber}&Col1={$firstName}&Col2={$surName}&Col3={$theTitle}&SID=6";
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
    }
}
?>