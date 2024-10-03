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

$sql = "SELECT name 
FROM issue_type";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $issues = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $issue_types[] = $row;
    }
}

// Check if form is submitted
if (isset($_POST['submit'])) {
  // Get the form data
 
  $street = $_POST['street'];
  $city = $_POST['city'];
  $postal_code = $_POST['postal_code'];
  $content = $_POST['description'];
  $issue_type = $_POST['issue_type'];

  // Prepare the SQL query
  $address = "INSERT INTO address (street , city , postal_code) VALUES(?,?,?)";
  $issue = "INSERT INTO issue (content, address_id,issue_type_id, user_id) VALUES (?, (SELECT MAX(address_id) FROM address),(SELECT issue_type_id FROM issue_type WHERE name = ? ), ?)";

  // Bind the parameters
  $stmt1 = $conn->prepare($address);
  $stmt1->bind_param("sss",$street , $city , $postal_code);

  $stmt2 = $conn->prepare($issue);
  $stmt2->bind_param("sss",$content , $issue_type ,$_SESSION['current_user'] );

  // Execute the query
  if ($stmt1->execute()) {
    if ($stmt2->execute()) {
      echo "<div class='notification'>Reported successfully</div>";
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
                <p>Please fill in this form to report a damaged water pipe in your area. Your information will be sent to the municipality for further action.</p>
                <form method="post" action="report.php" onsubmit="return confirmSubmit()">
                    <label for="street">Address</label>
                    <label for="street">Address : street</label>
                    <input type="text" id="street" name="street" required>
                    <label for="city">Address : city</label>
                    <input type="text" id="city" name="city" required>
                    <label for="postal_code">Address: Postal code</label>
                    <input type="text" id="postal_code" name="postal_code" required>
                    <label for="issue_type">Issue type</label>
                    <select name="issue_type" class="type" required>
                      <option disabled selected value>Select issue type</option>
                      <?php
                        foreach ($issue_types as $issue_type) {
                                echo '<option value="'.$issue_type["name"].'">'.$issue_type["name"].'</option>';
                            } ?>
                    </select>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
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