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
		$chocolateName="";
		$price="";
		if (isset($_POST["id"]))
		  $id=$_POST["id"];
	  if (isset($_POST["chocolateName"]))
		  $chocolateName=$_POST["chocolateName"];	  
		if (isset($_POST["price"]))
		  $price=$_POST["price"];  
	
	echo "<p></p>";
	echo "<h2> Enter New Chocolate Below</h2>";
	echo "<p></p>";	 	
	?>
	<h5>Complete the information in the form below and click Submit to create new chocolate data. All fields are required.</h5>
	<form name="createchocolate" method="POST" action="InsertChocolate.php">	
	<table border="1" width="100%" cellpadding="0">			
			<tr>
				<td width="157">Id:</td>
				<td><input type="text" name="id" value='<?php echo $id ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Chocolate Name:</td>
				<td><input type="text" name="chocolateName" value='<?php echo $chocolateName ?>' size="30"></td>
			</tr>
			<tr>
				<td width="157">Price:</td>
				<td><input type="text" name="price" value='<?php echo $price ?>' size="30"></td>
			</tr>
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
  $chocolateName = $_POST["chocolateName"];
  $price = $_POST["price"];
  
  $chocolate = new ChocolateClass($id,$chocolateName,$price);
  	$count = countChocolate($chocolate);    	  
 
 	 
  	// Check for accounts that already exist and Do insert
  	if ($count==0) 
  	{  		
  		$res = insertChocolate($chocolate);
  		echo "<h3>New chocolate data is successfully inserted!</h3> ";         
  	}
  	else 
  	{
  		echo "<h3>A chocolate account with that ChocolateName already exists.</h3> ";  		
  	}  	
  }
  
 function countChocolate ($chocolate)
  {  	  	 
  	// Connect to the database
   $mysqli = connectdb();
   $id = $chocolate->getId();
   $chocolateName = $chocolate->getChocolateName();
   $price = $chocolate->getPrice();
   
		// Connect to the database
	$mysqli = connectdb();
		
	// Define the Query
	// For Windows MYSQL String is case insensitive
	 $Myquery = "SELECT count(*) as count from Chocolates
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
  	
  function insertChocolate ($chocolate)
  {
		
		// Connect to the database
   $mysqli = connectdb();
		
	 $id = $chocolate->getId();
   $chocolateName = $chocolate->getChocolateName();
   $price = $chocolate->getPrice();
		
		// Add Prepared Statement
		$Query = "INSERT INTO Chocolates 
	          (id,chocolateName,price) 
	           VALUES (?,?,?)";
	           
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("isd", $id, $chocolateName, $price);
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
	
 // Class to construct Chocolates with getters/setter
class ChocolateClass
{
    // property declaration
    private $id="";
    private $chocolateName="";
    private $price="";
   
    // Constructor
    public function __construct($id,$chocolateName,$price)
    {
      $this->id = $id;
      $this->chocolateName = $chocolateName;
      $this->price = $price;    
    }
    
    // Get methods 
	  public function getId ()
    {
    	return $this->id;
    } 
	  public function getChocolateName ()
    {
    	return $this->chocolateName;
    } 
	  public function getPrice ()
    {
    	return $this->price;
    } 
	 
	  

    // Set methods 
    public function setId($value)
    {
    	$this->id = $value;    	
    }
    public function setChocolateName ($value)
    {
    	$this->chocolateName = $value;    	
    }
    public function setPrice ($value)
    {
    	$this->price = $value;    	
    }
         
    
} // End ChocolateClass

?>
</body>
</html>
