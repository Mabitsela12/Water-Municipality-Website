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

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    // Prepare the SQL query
    $user = "INSERT INTO user (username, password, role_id) VALUES (?, ?, (SELECT role_id FROM role WHERE name = 'citizen'))";
    $citizen = "INSERT INTO citizen (first_name,last_name,phone,user_id) VALUES (? , ? ,?, (SELECT MAX(user_id) FROM user)) ";
    // Bind the parameters
    $stmt = $conn->prepare($user);
    $stmt->bind_param("ss", $username, $password);

    $stmt1 = $conn->prepare($citizen);
    $stmt1->bind_param("sss", $name, $surname , $phone);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt1->execute()) {
            header("location: account_created.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
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
                    <li><a href="/report">Logout</a></li>
                </ul>
            </div>
        </div>

            <main>
                <h2>Create account</h2>
                <p>Please enter your username and password to create account.</p>
                <form method="POST" action="create_citizen.php" id="form" onsubmit="return confirmSubmit()">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" required>
                    <label for="phone">Phone number</label>
                    <input type="text" id="phone" name="phone" required>
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