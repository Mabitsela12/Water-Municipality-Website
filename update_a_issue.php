<?php

session_start();
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "water_issues_db";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


//select all types
$sql = "SELECT name 
FROM issue_type";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $issue_types = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $issue_types[] = $row;
    }
}

$issue = array();
//get all data before update , the request will come from the view_reports page
if (isset($_POST['update'])) {

    $issue_id = $_POST['issue_id'];

    $sql = "SELECT i.*, it.name AS issue_type , a.*
    FROM issue i
    JOIN address a ON i.address_id = a.address_id
    JOIN issue_type it ON i.issue_type_id = it.issue_type_id
    WHERE i.issue_id = ".$issue_id;
    
    $result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $issue = $result->fetch_assoc();
}

}
//end

// Check if form is submitted
if (isset($_POST['submit'])) {
  // Get the form data
 
  $street = $_POST['street'];
  $city = $_POST['city'];
  $postal_code = $_POST['postal_code'];
  $content = $_POST['description'];
  $issue_type = $_POST['issue_type'];
  $address_id = $_POST['address_id'];
  $id = $_POST['issue_id'];

  // Prepare the SQL query
  $address = "UPDATE address SET street = ? , city = ? , postal_code = ? WHERE address_id = ?";
  $my = "UPDATE issue SET content = ? , issue_type_id = (SELECT issue_type_id FROM issue_type WHERE name = ?) WHERE issue_id = ?";

  // Bind the parameters
  $stmt1 = $conn->prepare($address);
  $stmt1->bind_param("ssss",$street , $city , $postal_code , $address_id);

  $stmt2 = $conn->prepare($my);
  $stmt2->bind_param("sss",$content ,$issue_type,$id);

  // Execute the query
  if ($stmt1->execute()) {
    if ($stmt2->execute()) {
        header("location: update_successful.php?message=admin.php");
        exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close the statement
  //$stmt3->close();
  
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Municipality Water Website</title>
        <link rel="stylesheet" href="reportCSS.css">
   
    </head>
    <body>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <h2>Mathipa Municipality</h2>
                </div>
                <div class="menu">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login.php">Employee Login</a></li>
                    </ul>
                </div>
            </div>
            <div class="container" >
            <div class="navbar2">
                <div class="menu">
                    <ul>
                        <li><a href="report.php">Report Issues</a></li>
                        <li><a href="citizen.php">View MyReports </a></li>
                    </ul>
                </div>
            </div>
        </div>
            <main>
                <h2>Report water issues</h2>
                <p>Please fill in this form to update a report a damaged water pipe in your area. Your information will be sent to the municipality for further action.</p>
                <form method="post" action="update_a_issue.php" onsubmit="return confirmSubmit()">
                    <input type="hidden"  name="address_id" value="<?php echo $issue["address_id"];?>">
                    <input type="hidden"  name="issue_id" value="<?php echo $issue["issue_id"];?>">
                    <label for="street">Address</label>
                    <label for="street">Address : street</label>
                    <input type="text" id="street" name="street" value="<?php echo $issue["street"];?>" required>
                    <label for="city">Address : city</label>
                    <input type="text" id="city" name="city" value="<?php echo $issue["city"];?>" required>
                    <label for="postal_code">Address: Postal code</label>
                    <input type="text" id="postal_code" name="postal_code" value="<?php echo $issue["postal_code"];?>"required>
                    <label for="issue_type">Issue type</label>
                    <select name="issue_type" class="type" required>
                      <option selected value="<?php echo $issue["issue_type"];?>"><?php echo $issue["issue_type"];?></option>
                      <?php
                        foreach ($issue_types as $issue_type) {
                                echo '<option value="'.$issue_type["name"].'">'.$issue_type["name"].'</option>';
                            } ?>
                    </select>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo $issue["content"];?></textarea>
                    <button type="submit" name="submit" >Submit</button>
                </form>
            </main>
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
    <script>
        function confirmSubmit(){
            return confirm("Confirm report submission?")
        }

    </script>
</body>
</html>