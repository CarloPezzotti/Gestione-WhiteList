<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <div class="container">
        
        <div class="container login">
            <form action="login.php" method="post">               
                <div class="loginC">
                    <span>Username:</span>
                    <input type="text" name="username">
                </div>
              
                <div class="loginC">
                    <span>Password:</span>
                    <input type="password" name="password">
                </div>   
                <div class="loginC"> 
                    <input type="submit" value="Login"> 
                </div>
            </form>
        </div>
    </div>
    <?php
        require_once "database.php";
        $conn = Database::getConnection();
    ?>
</body>
</html>