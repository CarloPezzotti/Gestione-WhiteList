
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/assets/global.css">
    <link rel="stylesheet" type="text/css" href="/assets/util.css">
    <title>Manage</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="whitelist.php">Gestione Whitelist</a></li>
            <li><a href="<?php echo URL."adminpanel/logout"?>">Logout</a></li>
        </ul>

    </nav>
    <h1>Manage Users</h1>
    <div class="container">

        <div class="container login">
            <form action="<?php if (@isset($setID)) echo URL."adminpanel/modify/$setID";  else echo URL."adminpanel/insert";?>" method="post">
                <div class="loginC">
                    <span>Username:</span>
                    <input value="<?php if (@isset($setUsername)) echo $setUsername ?>" type="text" name="username" placeholder="Username...">
                </div>

                <div class="loginC">
                    <span>Name:</span>
                    <input value="<?php if (@isset($setName)) echo $setName ?>" type="text" name="name" placeholder="Name...">
                </div>

                <div class="loginC">
                    <span>Surname:</span>
                    <input value="<?php if (@isset($setSurname)) echo $setSurname ?>" type="text" name="surname" placeholder="Surname...">
                </div>

                <div class="loginC">
                    <span>Email:</span>
                    <input value="<?php if (@isset($setEmail)) echo $setEmail ?>" type="text" name="email" placeholder="Email...">
                </div>

                <div class="loginC">
                    <span>Type:</span>
                    <select name="type">
                        <option>User</option>
                        <option <?php if (@isset($setType)) if($setType == 1) echo "selected" ?>>Admin</option>
                    </select>
                </div>

                <div class="loginC">
                    <span>Password:</span>
                    <input type="text" name="password" placeholder="Password...">
                </div>

                <div class="loginC">
                    <input name="submit" type="submit" value="<?php  if (@isset($setUsername)) echo "Update";else echo "Insert"; ?>">
                </div>
            </form>
            <p class="error">
                <?php if(@isset($error)) echo $error; ?>
            </p>
        </div>
        <?php
            use Models\Admin;
            $result = Admin::getTable();
            echo "<table class='tableR'>";
            echo "<thead><tr>";
            foreach ($result[0] as $key => $col) {
                echo "<th>$key</th>";
            }
            echo "</tr></thead>";
            foreach ($result as $row) {
                echo "<tr>";
                $ID = $row["ID"];
                foreach ($row as $key => $col) {
                    if ($key == "Type") {
                        $type = ($col == 1) ? "Admin" : "User";
                        echo "<td>$type</td>";
                    } else {
                        echo "<td>$col</td>";
                    }
                }
                echo "<td><a href='". URL."adminpanel/delete/$ID'><span class='operation'>&times;</span></a></td>";
                echo "<td><a href='". URL."adminpanel/modify/$ID'><span class='operation'>&#9998;</span></a></td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </div>
</body>

</html>