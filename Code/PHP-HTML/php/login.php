<?php
    require_once "database.php";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["username"])) {
            $error = "Username required!<br>";
        }
        if (empty($_POST["password"])) {
            $error .= "Password required!";
        }
        if(empty($error)){
            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT name,surname,type,setpassword  FROM user WHERE username = ? AND password = ?");
            $stmt->execute([$_POST["username"],$_POST["password"]]); 
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);  
            
            session_start();
            if($result[0]["setpassword"] == 1){
                
            }
            $_SESSION["name"] = $result[0]["name"];
            $_SESSION["surname"] = $result[0]["surname"];
            $_SESSION["type"] = $result[0]["type"];
            if($result[0]["type"] == 1){
                header("Location: admin.php");
            }else if($result[0]["type"] == 2){
                header("Location: whitelist.php");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/global.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <div class="container">
        
        <div class="container login">
            <form action="login.php" method="post">               
                <div class="loginC">
                    <span>Username:</span>
                    <input type="text" name="username" placeholder="Username...">
                </div>
              
                <div class="loginC">
                    <span>Password:</span>
                    <input type="password" name="password" placeholder="Password...">
                </div>   
                <div class="loginC"> 
                    <input type="submit" value="Login"> 
                </div>
            </form>
            <p class="error">
                <?php echo $error;?>
            </p>
        </div>
    </div>
    
</body>
</html>