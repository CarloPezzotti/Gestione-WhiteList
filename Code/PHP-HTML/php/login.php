<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";
    require_once "changePassword.php";
    if ($_POST["setpassword"] == 1) {

        if (!empty($_POST["password"]) && !empty($_POST["repassword"]) && !empty($_POST["username"])) {
            if ($_POST["password"] == $_POST["repassword"]) {
                if (!ChangePassword::change($_POST["username"], $_POST["password"])) {
                    $error = "Error changing password";
                } else {

                    $success = "Password changed, login again";
                }
            } else {
                $error = "Password don't match :(";
            }
        }
    } else {
        if (empty($_POST["username"])) {
            $error = "Username required!<br>";
        }
        if (empty($_POST["password"])) {
            $error .= "Password required!";
        }
        if (empty($error)) {
            $conn = null;

            try {
                $query = "SELECT name,surname,type,setpassword,password  FROM user WHERE username = :username";
                $values = array(
                    "username" => $_POST["username"]
                );
                $result = Database::executeQuery($query, $values);
                ##No account find
                if (count($result) == 0) {
                    $error = "Username not found!";
                } else {
                    if (password_verify($_POST["password"], $result[0]["password"])) {
                        if ($result[0]["setpassword"] == 1) {
                            $setPassword = 1;
                            $name = $result[0]["name"];
                            $surname = $result[0]["surname"];
                            $username = $_POST["username"];
                        } else {
                            session_start();
                            $_SESSION["name"] = $result[0]["name"];
                            $_SESSION["surname"] = $result[0]["surname"];
                            $_SESSION["type"] = $result[0]["type"];
                            if ($result[0]["type"] == 1) {
                                header("Location: admin.php");
                            } else if ($result[0]["type"] == 2) {
                                header("Location: whitelist.php");
                            } else {
                                header("Refresh:0");
                            }
                        }
                    }else{
                        $error = "Password wrong :(";
                    }
                }
            } catch (\Throwable $th) {
                echo $th;
                echo "Error :(";
                exit;
            }
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
    <link rel="stylesheet" type="text/css" href="../css/modal.css">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <div class="container">
        <div class="container login">
            <form action="login.php" method="post">
                <div class="loginC">
                    <span>Username:</span>
                    <input value="<?php if ($_POST["setpassword"] == 1) {
                                        echo $_POST["username"];
                                    } ?>" type="text" name="username" placeholder="Username...">
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
                <b><?php echo $error; ?></b>
            </p>

            <p class="success">
                <b><?php echo $success; ?></b>
            </p>
        </div>
    </div>
    <div id="myModal" class="modal" <?php if ($setPassword) echo "style='display:block;'" ?>>
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2><?php echo "Hi $surname $name, change your password:" ?></h2>
            </div>
            <div class="modal-body">
                <br>
                <form method="post">
                    <div class="loginC">
                        <span>Password:</span>
                        <input id="password" type="password" name="password" placeholder="Password...">
                    </div>
                    <div class="loginC">
                        <span>Repeat Password:</span>
                        <input id="repassword" type="password" name="repassword" placeholder="Password...">
                    </div>
                    <div class="loginC">
                        <input disabled type="submit" id="chpwd" value="Change">
                    </div>
                    <input type="hidden" name="setpassword" value="1">
                    <input type="hidden" name="username" value="<?php echo $username ?>">
                </form>
            </div>
            <div class="modal-footer">
                <h3>Just first time.</h3>
            </div>
        </div>
    </div>
</body>
<script src="../js/login.js"></script>
<script src="../js/modal.js"></script>


</html>