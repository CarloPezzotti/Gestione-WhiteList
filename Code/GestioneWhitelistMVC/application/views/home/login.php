
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/assets/global.css">
    <link rel="stylesheet" type="text/css" href="/assets/util.css">
    <link rel="stylesheet" type="text/css" href="/assets/modal.css">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <div class="container">
        <div class="container login">
            <form action="<?php echo URL . "home/login"?>" method="post">
                <div class="loginC">
                    <span>Username:</span>
                    <input value="" type="text" name="username" placeholder="Username...">
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
                <b><?php if(@isset($error)) echo $error;?></b>
            </p>

            <p class="success">
                <b><?php if(@isset($success)) echo $success;?></b>
            </p>
        </div>
    </div>
    <div id="myModal" class="modal" <?php if(@isset($setpassword)) echo "style='display:block;'" ?>>
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2><?php if(@isset($surname)) echo "Hi $surname, change your password:" ?></h2>
            </div>
            <div class="modal-body">
                <br>
                <form action="<?php echo URL . "home/changePassword"?>"  method="post">
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
                    <input type="hidden" name="username" value="<?php if(@isset($username)) echo $username ?>">
                </form>
            </div>
            <div class="modal-footer">
                <h3>Just first time.</h3>
            </div>
        </div>
    </div>
</body>
<script src="/assets/js/login.js"></script>
<script src="/assets/js/modal.js"></script>


</html>