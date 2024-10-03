<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "water_issues_db";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // SQL query to retrieve data
    $sql = "SELECT u.user_id as user_id , r.name as role FROM user u,role r  WHERE (username = '".$username."' and password = '".$password."' )and u.role_id = r.role_id";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are results 

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['current_user']=$row['user_id'];
        if( strcmp($row['role'],'admin') == 0 )
        {
            header("location: admin.php");
            exit;
        }
        else if(strcmp($row['role'],'plumber') == 0){
            header("location: plumber.php");
            exit;
        }else{
            header("location: report.php");
            exit;
        }
    }
    else{
        echo "<div class='notification'>Wrong username or password</div>";
    }

    if (isset($_POST['create'])) {
        header("location: create_citizen.php");
        exit;
    }

}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Municipality Water Website</title>
        <link rel="stylesheet" href="loginCss.css">
   
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
                    </ul>
                </div>
            </div>
            <main>
                <h2>Login</h2>
                <p>Please enter your username and password to log in.</p>
                <form method="POST" action="login.php" id="form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit" name="submit">Log In</button>
                </form>
                   <a href="create_citizen.php"> <button type="button">Create Account</button></a>
                <!-- Add a paragraph element to display an error message if any -->
                
            </main>
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
</body>
</html>