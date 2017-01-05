
<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Create Chocolate </title>
</head>
<body OnLoad="document.createstudent.firstname.focus();"> 

<?php   	
		if(isset($_POST["CreateSubmit"])) 
		{    	 
	 	 		 	 	
	   	validate_form();
	   	// Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	    
		} 
		else 
		{			    
			$messages = array();
	    show_form($messages);
	    // Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	      
  	} 
	?>
		
<?php
function show_form($messages) { 		
			
		
		// Assign post values if exist
		$id="";
		$lastname="";
		$firstname="";
		$street="";
		$city="";
		$state="";
		$zipcode="";
		$cardtype="";
		$cardnumber="";
		$expirationdate="";
		$products="";

		if (isset($_POST["id"]))
		  $id=$_POST["id"];
	  if (isset($_POST["lastname"]))
		  $lastname=$_POST["lastname"];	  
		if (isset($_POST["firstname"]))
		  $firstname=$_POST["firstname"];  
		if (isset($_POST["street"]))
		  $street=$_POST["street"];
	  if (isset($_POST["city"]))
		  $city=$_POST["city"];	  
		if (isset($_POST["state"]))
		  $state=$_POST["state"]; 
		if (isset($_POST["zipcode"]))
		  $zipcode=$_POST["zipcode"];
	  if (isset($_POST["cardtype"]))
		  $cardtype=$_POST["cardtype"];	  
		if (isset($_POST["cardnumber"]))
		  $cardnumber=$_POST["cardnumber"]; 
		if (isset($_POST["expirationdate"]))
		  $expirationdate=$_POST["expirationdate"];
	if (isset($_POST["products"]))
		  $products=$_POST["products"];	
	
	echo "<p></p>";
	echo "<h2> Enter Order Information Below</h2>";
	echo "<p></p>";	 	
	?>

	<h5>Complete the information in the form below and click Submit to place your order. All fields are required.</h5>
	
<form action="InsertCustomer.php" method="POST" id="my_form">
ID: <input type="text" name="id" value='<?php echo $id ?>'><br>
Last Name: <input type="text" name="lastname" value='<?php echo $lastname ?>'><br>
First Name: <input type="text" name="firstname" value='<?php echo $firstname ?>'><br>
Street Address: <input type="text" name="street" value='<?php echo $street ?>'><br>
City: <input type="text" name="city" value='<?php echo $city ?>'><br>
State: <input type="text" name="state" value='<?php echo $state ?>'><br>
Zip Code: <input type="text" name="zipcode" value='<?php echo $zipcode ?>'><br>
Credit Card Type: <select name="cardtype" value='<?php echo $cardtype ?>'>
<option>Visa</option>
<option>Mastercard</option>
<option>American Express</option></select><br>
Credit Card Number: <input type="text" name="cardnumber" value='<?php echo $cardnumber ?>'><br>
Expiration Date: <input type="date" name="expirationdate" value='<?php echo $expirationdate ?>'><br>
Purchased Products Names (Maximum 3): <input type="text" name="products" value='<?php echo $products ?>'>
<tr>
				<td width="157"><input type="submit" value="Submit" name="CreateSubmit"></td>
				<td>&nbsp;</td>
			</tr>
</table>
</form>
	<?php
} // End Show form
?>

<?php
function validate_form()
{
		
	$messages = array();
  $redisplay = false;
  // Assign values
  $id = $_POST["id"];
  $lastname = $_POST["lastname"];
  $firstname = $_POST["firstname"];
  $street = $_POST["street"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zipcode = $_POST["zipcode"];
  $cardtype = $_POST["cardtype"];
  $cardnumber = $_POST["cardnumber"];
  $expirationdate = $_POST["expirationdate"];
   $products = $_POST["products"];

  $customer = new CustomerClass($id,$lastname,$firstname,$street,$city,$state,$zipcode,$cardtype,$cardnumber,$expirationdate,$products);
  	$count = countCustomer($customer);    	  
 
 	 
  	// Check for accounts that already exist and Do insert
  	if ($count==0) 
  	{  		
  		$res = insertCustomer($customer);
  		echo "<h3>Your order is successfully placed!</h3> ";         
  	}
  	else 
  	{
  		echo "<h3>Your order already exists.</h3> ";  		
  	}  	
  }
  
 function countCustomer ($customer)
  {  	  	 
  	// Connect to the database
   $mysqli = connectdb();
   $id = $customer->getId();
   $lastname = $customer->getLastName();
   $firstname = $customer->getFirstName();
   $street = $customer->getStreet();
   $city = $customer->getCity();
   $state = $customer->getState();
   $zipcode = $customer->getZipcode();
   $cardtype = $customer->getCardType();
   $cardnumber = $customer->getCardNumber();
   $expirationdate = $customer->getExpirationDate();
   $products = $customer->getProducts();
   
		// Connect to the database
	$mysqli = connectdb();
		
	// Define the Query
	// For Windows MYSQL String is case insensitive
	 $Myquery = "SELECT count(*) as count from Customers
		   where ID='$id'";	 
		
	 if ($result = $mysqli->query($Myquery)) 
	 {
	    /* Fetch the results of the query */	     
	    while( $row = $result->fetch_assoc() )
	    {
	  	  $count=$row["count"];	    			   	     	  	     	  
	    }	 
	
 	    /* Destroy the result set and free the memory used for it */
	    $result->close();	      
   }
	
	$mysqli->close();   
	    
	return $count;
  	  	
  }
  	
  function insertCustomer ($customer)
  {
		
		// Connect to the database
   $mysqli = connectdb();
$id = $customer->getId();
   $lastname = $customer->getLastName();
   $firstname = $customer->getFirstName();
   $street = $customer->getStreet();
   $city = $customer->getCity();
   $state = $customer->getState();
   $zipcode = $customer->getZipcode();
   $cardtype = $customer->getCardType();
   $cardnumber = $customer->getCardNumber();
   $expirationdate = $customer->getExpirationDate();
   $products = $customer->getProducts();
		
		// Add Prepared Statement
		$Query = "INSERT INTO Customers(ID,LastName,FirstName,Street,City,State,ZipCode,CreditCardType,CreditCardNumber,ExpirationDate,PurchasedProducts) 
	           VALUES (?,?,?,?,?,?,?,?,?,?,?)";
	           
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("issssssssss", $id, $lastname, $firstname,$street,$city,$state,$zipcode,$cardtype,$cardnumber,$expirationdate,$products);
$stmt->execute();
		
		
	
	$stmt->close();
	$mysqli->close();
		
		return true;
	}
	  	
  function getDbparms()
	 {
	 	$trimmed = file('parms/dbparms.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$key = array();
	$vals = array();
	foreach($trimmed as $line)
	{
		  $pairs = explode("=",$line);    
	    $key[] = $pairs[0];
	    $vals[] = $pairs[1]; 
	}
	// Combine Key and values into an array
	$mypairs = array_combine($key,$vals);
	
	// Assign values to ParametersClass
	$myDbparms = new DbparmsClass($mypairs['username'],$mypairs['password'],
	                $mypairs['host'],$mypairs['db']);
	
	// Display the Paramters values
	return $myDbparms;
	 }
	 
  function connectdb() {      		
		// Get the DBParameters
	  $mydbparms = getDbparms();
	  
	  // Try to connect
	  $mysqli = new mysqli($mydbparms->getHost(), $mydbparms->getUsername(), 
	                        $mydbparms->getPassword(),$mydbparms->getDb());
	
	   if ($mysqli->connect_error) {
	      die('Connect Error (' . $mysqli->connect_errno . ') '
	            . $mysqli->connect_error);      
	   }
	  return $mysqli;
	}
 
 class DBparmsClass
	{
	    // property declaration  
	    private $username="";
	    private $password="";
	    private $host="";
	    private $db="";
	   
	    // Constructor
	    public function __construct($myusername,$mypassword,$myhost,$mydb)
	    {
	      $this->username = $myusername;
	      $this->password = $mypassword;
			  $this->host = $myhost;
				$this->db = $mydb;
	    }
	    
	    // Get methods 
		  public function getUsername ()
	    {
	    	return $this->username;
	    } 
		  public function getPassword ()
	    {
	    	return $this->password;
	    } 
		  public function getHost ()
	    {
	    	return $this->host;
	    } 
		  public function getDb ()
	    {
	    	return $this->db;
	    } 	 
	
	    // Set methods 
	    public function setUsername ($myusername)
	    {
	    	$this->username = $myusername;    	
	    }
	    public function setPassword ($mypassword)
	    {
	    	$this->password = $mypassword;    	
	    }
	    public function setHost ($myhost)
	    {
	    	$this->host = $myhost;    	
	    }
	    public function setDb ($mydb)
	    {
	    	$this->db = $mydb;    	
	    }    
	    
	} // End DBparms class
	
 // Class to construct Customers with getters/setter
class CustomerClass
{
    // property declaration
    private $id="";
    private $lastname="";
    private $firstname="";
	private $street="";
    private $city="";
    private $state="";
	private $zipcode="";
    private $cardtype="";
    private $cardnumber="";
	private $expirationdate="";
	private $products="";
   
    // Constructor
    public function __construct($id, $lastname, $firstname,$street,$city,$state,$zipcode,$cardtype,$cardnumber,$expirationdate,$products)
    {
      $this->id = $id;
      $this->lastname = $lastname;
      $this->firstname = $firstname;  
	$this->street = $street;
      $this->city = $city;
      $this->state = $state;    
	$this->zipcode = $zipcode;
      $this->cardtype = $cardtype;
      $this->cardnumber = $cardnumber;  
	$this->expirationdate = $expirationdate;
	$this->products = $products;
    }
    
    // Get methods 
	  public function getId ()
    {
    	return $this->id;
    } 
	  public function getLastName ()
    {
    	return $this->lastname;
    } 
	  public function getFirstName ()
    {
    	return $this->firstname;
    } 
public function getStreet ()
    {
    	return $this->street;
    } 
	  public function getCity ()
    {
    	return $this->city;
    } 
	  public function getState ()
    {
    	return $this->state;
    } 
public function getZipcode ()
    {
    	return $this->zipcode;
    } 
	  public function getCardType ()
    {
    	return $this->cardtype;
    } 
	  public function getCardNumber ()
    {
    	return $this->cardnumber;
    } 
public function getExpirationDate ()
    {
    	return $this->expirationdate;
}
public function getProducts()
{
	return $this->products;
}
	  

    // Set methods 
    public function setId($value)
    {
    	$this->id = $value;    	
    }
     public function setLastName ($value)
    {
    	return $this->lastname = $value;
    } 
	  public function setFirstName ($value)
    {
    	return $this->firstname = $value;
    } 
    public function setStreet($value)
    {
    	$this->street = $value;    	
    }
     public function setCity ($value)
    {
    	return $this->city = $value;
    } 
	  public function setState ($value)
    {
    	return $this->state = $value;
    } 
public function setZipcode($value)
    {
    	$this->zipcode = $value;    	
    }
     public function setCardType ($value)
    {
    	return $this->cardtype = $value;
    } 
	  public function setCardNumber ($value)
    {
    	return $this->cardnumber = $value;
    } 
    
    public function setExpirationDate($value)
    {
    	$this->expirationdate = $value;    	
    }
   public function setProducts($value)
{
$this->products=$value;
}
    
} // End ChocolateClass

?>
</body>
</html>
