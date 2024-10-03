<?php
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

// SQL query to retrieve data
$sql = "SELECT i.*, it.name AS title
FROM issue i
JOIN user u ON i.user_id = u.user_id
JOIN issue_type it ON i.issue_type_id = it.issue_type_id
WHERE u.role_id = 1
ORDER BY i.report_date DESC
LIMIT 1;";
// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    $issues = array(); // Initialize an array to store the retrieved data
    
    // Fetch data from the result set
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
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
    <link rel="stylesheet" href="indexCSS.css">
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
                    <li><a href="login.php" id="login">Login</a></li>
                </ul>
            </div>
        </div>
        <div class="banner">
            <h1>Welcome to Municipality Water Website</h1>
            <p>This website provides information and updates on water and sanitation issues in our municipality. You can also report any water-related problems or complaints here.</p>
            <a href="login.php"><button>Report issues</button></a>
        </div>
        <div class="content">
            <div class="section">
                <h2>Water Issues</h2>
                <h3>Recent report</h3>
                <ul>
                    <?php
                    if (!empty($issues)) {
                        foreach ($issues as $issue) {
                            echo '<li>';
                            echo '<h3>' . $issue['title'] . '</h3>';
                            echo '<p>' . $issue['content'] . '</p>';
                            echo '<p class="date">' . $issue['report_date'] . '</p>';
                            echo '</li>';
                        }
                    } else {
                        echo '<li>No issue yet.</li>';
                    }
                    ?>
                </ul>
                <a href="#"><button class="view">Read More</button></a>
            </div>
            <div class="section">
                <h2>Report a Problem</h2>
                <p>We value your feedback and suggestions on how we can improve our water services. If you have any water-related problems or complaints, please let us know by filling out this form. You can also call us on our toll-free number or email us at our address.</p>
                <a href="login.php"><button class="report">Fill form</button></a>
            </div>
            
        </div>
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
