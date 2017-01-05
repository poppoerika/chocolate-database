<html>
	<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
   <title>Update Student </title>
</head>
<body > 

<?php   			
// Check to see if Delete name is provided
if (isset($_GET["id"])) {
  $toUpdate = $_GET["id"];
  // A bit dangerous without checks and use of getMethod
  updateIt($toUpdate);
  
   echo "<p></p>";
	    echo "<a href=InsertChocolate.php> Insert Another Chocolate </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=SelectChocolate.php> Select Chocolates </a>";	
	     echo "<p></p>"; 
	    echo "<a href=DeleteChocolate.php> Delete Chocolate </a>";	
	     echo "<p></p>"; 
	    echo "<a href=UpdateChocolate.php> Update Chocolates </a>";	
	    // Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	    
}
else if (isset($_POST["UpdateMe"])) {
	// Assign values
  $id = $_POST["id"];
  $chocolateName = $_POST["chocolateName"];
  $price = $_POST["price"];
  
  $chocolate = new ChocolateClass($id,$chocolateName,$price);
  // Update the database
  FinalUpdate($chocolate);
 echo "<p></p>";
	    echo "<a href=InsertChocolate.php> Insert Another Chocolate </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=SelectChocolate.php> Select Chocolates </a>";	
	     echo "<p></p>"; 
	    echo "<a href=DeleteChocolate.php> Delete Chocolate </a>";	
	     echo "<p></p>"; 
	    echo "<a href=UpdateChocolate.php> Update Chocolates </a>";
	    // Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	    	 
}
 else {
	    show_form();  
	    
	    // Provide option for inserting another student
	    echo "<p></p>";
	    echo "<a href=InsertChocolate.php> Insert Another Chocolate </a>";	   
	     echo "<p></p>"; 
	    echo "<a href=SelectChocolate.php> Select Chocolates </a>";	
	// Provide option for going back to the login page
	    echo "<p></p>";
	    echo "<a href=loginPage.html> Go Back to the Login Page </a>";	       }
  	
	?>
		
<?php
function show_form() { 			
	
	echo "<p></p>";
	echo "<h2> Select the Chocolate to Delete</h2>";
	echo "<p></p>";	 	
	// Retrieve the chocolates
	$chocolates = selectChocolates();
	
	echo "<h3> " . "Number of Chocolates in Database is:  " . sizeof($chocolates) . "</h3>";
	// Loop through table and display
	echo "<table border='1'>";
	foreach ($chocolates as $data) {
	echo "<tr>";	
	// Provide Hyperlink for Selection
	// Could also use Form with Post method 
	echo "<td> <a href=UpdateChocolate.php?id=" . $data->getId() . ">" . "Update" . "</a></td>";
	 echo "<td>" . $data->getId() . "</td>";
	 echo "<td>" . $data->getChocolateName() . "</td>";
	 echo "<td>" . $data->getPrice() . "</td>";
	echo "</tr>";
}
	echo "</table>";

} // End Show form
?>

<?php
  	
  function getChocolate ($chocolateD) {
  	// Connect to the database
   $mysqli = connectdb();
   
   // Add Prepared Statement
		$Query = "Select ID, ChocolateName, Price from Chocolates 
		         where ID = ?";	         
	           
		$stmt = $mysqli->prepare($Query);
				
// Bind and Execute
$stmt->bind_param("i", $chocolateD);
$result = $stmt->execute();

 $stmt->bind_result($id,$chocolateName,$price);
 
  /* fetch values */
    $stmt->fetch();
  $chocolateData = new Chocolateclass($id,$chocolateName,$price);

// Clean-up				
	$stmt->close();   
   $mysqli->close();
   return $chocolateData;
  }
  function updateIt($chocolateD) {
  	
  	
	$chocolate = getChocolate($chocolateD);
	// Extract data
	$id = $chocolate->getId();
	$chocolateName = $chocolate->getChocolateName();
	$price = $chocolate->getPrice();
	
	// Show the data in the Form for update
	?>
	<p></p>
	
	<form name="updateChocolate" method="POST" action="UpdateChocolate.php">	
	<table border="1" width="75%" cellpadding="0">			
			<tr>
				<td width="157">ID:</td>
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
				<td width="157"><input type="submit" value="Update" name="UpdateMe"></td>
				<td>&nbsp;</td>
			</tr>
	</table>			
	</form>
		  	
  <?php	
}
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

// Final Update
function FinalUpdate($chocolate) {
	// Assign values
  $id = $chocolate->getId();
  $chocolateName = $chocolate->getChocolateName();
  $price = $chocolate->getPrice();
  
  // update
  // Connect to the database
   $mysqli = connectdb();		
	 		
		// Add Prepared Statement
		$Query = "Update Chocolates set ID = ?,
		         ChocolateName = ?, Price = ?
		         where ID = ?";
		         
	                    
		
		$stmt = $mysqli->prepare($Query);
				
$stmt->bind_param("isdi", $id, $chocolateName, $price,$id);
$stmt->execute();

 //Clean-up				
	$stmt->close();   
   $mysqli->close();

}

?>
</body>
</html>
