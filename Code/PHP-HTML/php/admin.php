<?php
    session_start();
    if($_SESSION["type"] !=1){
        header("Location: error.html");
    }
    require_once "database.php";    
   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["username"])) {
            $error .= "Username required!<br>";
        }
        if (empty($_POST["name"])) {
            $error .= "Name required!<br>";
        }
        if (empty($_POST["surname"])) {
            $error .= "Surname required!<br>";
        }
        if (empty($_POST["email"])) {
            $error .= "Email required!<br>";
        }
        if (empty($_POST["type"])) {
            $error .= "Type required!<br>";
        }
        if (empty($_POST["password"])) {
            $error .= "Password required!";
        }
        if(empty($error)){
            $conn = Database::getConnection();
            $type = ($_POST["type"]=="Admin")? 1:2;
            $stmt = $conn->prepare("INSERT into user values(:username,:name,:surname,:email,:type,:password,1)");
            $stmt->bindParam(':username', $_POST["username"]);
            $stmt->bindParam(':name', $_POST["name"]);
            $stmt->bindParam(':surname', $_POST["surname"]);
            $stmt->bindParam(':password', $_POST["password"]);
            $stmt->bindParam(':email', $_POST["email"]);
            $stmt->bindParam(':type', $type);
            $stmt->execute(); 

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
    <title>Manage</title>
</head>
<body>
    <h1>Manage Users</h1>
    <div class="container">
        
        <div class="container login">
            <form action="admin.php" method="post">               
                <div class="loginC">
                    <span>Username:</span>
                    <input type="text" name="username" placeholder="Username...">
                </div>
              
                <div class="loginC">
                    <span>Name:</span>
                    <input type="text" name="name" placeholder="Name...">
                </div>   

                <div class="loginC">
                    <span>Surname:</span>
                    <input type="text" name="surname" placeholder="Surname...">
                </div> 

                <div class="loginC">
                    <span>Email:</span>
                    <input type="text" name="email" placeholder="Email...">
                </div>  

                <div class="loginC">
                    <span>Type:</span>
                    <select name="type">
                        <option>User</option>
                        <option>Admin</option>
                    </select>
                </div> 

                <div class="loginC">
                    <span>Password:</span>
                    <input type="text" name="password" placeholder="Password...">
                </div>  

                <div class="loginC"> 
                    <input type="submit" value="Insert"> 
                </div>
            </form>
            <p class="error">
                <?php echo $error;?>
            </p>
        </div>
            <?php
                require_once "database.php";    
                $conn = Database::getConnection();
                $stmt = $conn->prepare("SELECT 
                    username as Username,
                    name as Name,
                    surname as Surname,
                    email as Email,
                    type as Type
                    FROM user
                ");
                $stmt->execute(); 
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                
                echo "<table class='tableR'>";
                echo "<thead><tr>";
                foreach ($result[0] as $key => $col) {
                    echo "<th>$key</th>";
                }
                echo "</tr></thead>";
                foreach ($result as $row) {
                    echo "<tr>";
                    foreach ($row as $key => $col) {
                        if($key == "Type"){
                            $type = ($col==1)?"Admin":"User";
                            echo "<td>$type</td>";
                        }else{
                            echo "<td>$col</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            ?>
    </div>   
</body>
</html>