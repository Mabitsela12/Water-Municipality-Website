<?php
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
FROM role";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $roles = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    
  // Get the form data
  $username = $_POST['username'];
  $password = $_POST['password'];
  
    // Prepare the SQL query
$sql = "INSERT INTO user (username, password, role_id) VALUES (?, ?, (SELECT role_id FROM role WHERE name = 'plumber'))";

// Bind the parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

// Execute the query
if ($stmt->execute()) {
    echo "<div class='notification'>New record created successfully.</div>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


  
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Municipality Water Website</title>
        <link rel="stylesheet" href="create_empCss.css">
   
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
                    <li><a href="/report">Report a Problem</a></li>
                    <li><a href="/employeeLogin">Employee Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="container" >
            <div class="navbar2">
                <div class="menu">
                    <ul>
                        <li><a href="admin_report.php">Report Issues</a></li>
                        <li><a href="view_reports.php">View MyReports </a></li>
                        <li><a href="admin.php">Allocate</a></li>
                        <li><a href="view_employees.php">View employees</a></li>
                        <li><a href="create_emp.php">Create employee</a></li>
                    </ul>
                </div>
            </div>
        </div>
            <main>
                <h2>Create worker account</h2>
                <p>Please enter your username and password to log in as a worker.</p>
                <form method="POST" action="create_emp.php" id="form" onsubmit="return confirmSubmit()">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" name="submit" >Create</button>
                </form>
                <!-- Add a paragraph element to display an error message if any -->
                
            </main>
    </div>
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
    <script>
        function confirmSubmit(){
            return confirm("Are you sure you want to create account??")
        }

    </script>
</body>
</html>