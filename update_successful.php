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
                    <li><a href="login">Login</a></li>
                </ul>
            </div>
        </div>
            <main>
                <h2>Update successful</h2>
            
                <a href="<?php echo $_GET['message']?>"><button type="submit" name="submit" >Return</button></a>
                
            </main>
    </div>
        <div class="footer">
            <p>© 2023 Municipality Water Website. All rights reserved.</p>
        </div>
    </div>
</body>
</html>