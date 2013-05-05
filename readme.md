Graphic Mail PHP Wrapper
----------------
----------------

[Graphic Mail](http://www.graphicmail.co.uk)

[API Docs](http://www.graphicmail.co.uk/api/documentation/)

**NB. This currently only handles creating mail lists, adding email subscribers and updating subscribers details in datasets.**

How To Use
-----------

Just include the graphicMail.class.php in your file.

1. Create a new instance of the class, making sure to pass over your Graphic Mail username and API Password in the constructor

        $mail = new graphicMail('username','api-password');

2. From here it is fairly simple to call the functions you would like to use E.G. to add a mailing list called test, you would simply call

        $mail->newMailList('test');

3.	The wrapper will always return 0 for an error, 1 for a successful insert and 2 for a successful update


Different Methods
--------------

Create a new mailing list

    $mail->newMailList('test');

Get an array of all current mailing lists with the list name as the key and ID as the value

    $mail->getMailLists();

Get an array of all current datasets with the dataset name as the key and the ID as the value

    $mail->getDataSets();

Subscribe an email address to a mailing list

    $mail->subMailList('email@demo.address', 'mailing-list-ID');

Set user data ready to update a dataset (Different update types later on)

    $mail->setUserData('type-of-data','value');

Update/insert to dataset with the data set using setUserData

    $mail->insertToDataSet('email@demo.address', 'dataset-id');


setUserData Types
------------

Below are the different types you can use as an argument to the setUserData method.

	mobile		-	Mobile Telephone Number
	fname		-	First Name
	sname		-	Surname
	title 		-	Title
	company		-	Company Name
	jobtitle	-	Job Title
	worktel		- 	Work Telephone Number
	workfax		-	Work Fax Number
	hometel		- 	Home Telephone Number
	addr1		-	Address Line One
	addr2		-	Address Line Two
	city		-	City
	county 		- 	County
	postcode	-	Postcode
	country 	-	Country
	dob			-	Date Of Birth (Must be in yyyy/mm/dd format)
	gender		-	Gender
	website		-	Website Address
	imtype		-	Instant Messenger Type
	imaddress	-	Instant Messenger Address
	notes		-	Any Notes