<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Select Student </title>
</head>
<body OnLoad="document.createstudent.firstname.focus();"> 

<?php   			
	    show_form();  
	    
	    // Provide option for inserting another student
	    echo "<p></p>";
	    echo "<a href=InsertChocolate.php> Insert Another Chocolate </a>";	    
	    // Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	    
  	
	?>
		
<?php
function show_form() { 			
	
	echo "<p></p>";
	echo "<h2> Existing Chocolates Data</h2>";
	echo "<p></p>";	 	
	// Retrieve the choclates
	$chocolates = selectChocolates();
	
	echo "<h3> " . "Number of Chocolates in Database is:  " . sizeof($chocolates) . "</h3>";
	// Loop through table and display
	echo "<table border='1'>";
	foreach ($chocolates as $data) {
	echo "<tr>";	
	 echo "<td>" . $data->getId() . "</td>";
	 echo "<td>" . $data->getChocolateName() . "</td>";
	 echo "<td>" . $data->getPrice() . "</td>";
	echo "</tr>";
}
	echo "</table>";

} // End Show form
?>

<?php
  	
  function selectChocolates ()
  {
		
		// Connect to the database
   $mysqli = connectdb();
		
	 
		// Add Prepared Statement
		$Query = "Select ID, ChocolateName, Price from Chocolates";	         
	          
		$result = $mysqli->query($Query);
		$myChocolates = array();
if ($result->num_rows > 0) {    
    while($row = $result->fetch_assoc()) {
    	// Assign values
    	$id = $row["ID"];
    	$chocolateName = $row["ChocolateName"];
    	$price = $row["Price"]; 	
      
       // Create a Chocolate instance     
       $chocolateData = new ChocolateClass($id,$chocolateName,$price);
       $myChocolates[] = $chocolateData;         
      }    
 } 

	$mysqli->close();
	
	return $myChocolates;		
		
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
