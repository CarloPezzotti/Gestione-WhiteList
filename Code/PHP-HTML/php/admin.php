<?php
session_start();
if ($_SESSION["type"] != 1) {
    header("Location: error.html");
}
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["username"]))
        $username = htmlspecialchars($_POST["username"]);
    else
        $error .= "Username required!<br>";
    if (!empty($_POST["name"]))
        $name = htmlspecialchars($_POST["name"]);
    else
        $error .= "Name required!<br>";
    if (!empty($_POST["surname"]))
        $surname = htmlspecialchars($_POST["surname"]);
    else
        $error .= "Surname required!<br>";
    if (!empty($_POST["email"]))
        $email = htmlspecialchars($_POST["email"]);
    else
        $error .= "Email required!<br>";
    if (!empty($_POST["type"]))
        $type = htmlspecialchars($_POST["type"]);
    else
        $error .= "Type required!<br>";
    if (!empty($_POST["password"]))
        $password = htmlspecialchars($_POST["password"]);
    else
        $error .= "Password required!<br>";

    if ($_POST["submit"] == "Insert") {
        if (empty($error)) {
            $sql = "INSERT into user values(0,:username,:name,:surname,:email,:type,:password,1)";
            $values = array(
                'username'    => $username,
                'name'  => $name,
                'surname' => $surname,
                'email' => $email,
                'type' => ($type == "Admin") ? 1 : 2,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            );
            Database::executeQuery($sql, $values);
        }
    } else if ($_POST["submit"] == "Update") {
        $error = "";
        $sql = "UPDATE user set 
                username=:username,
                name=:name,
                surname=:surname,
                email=:email,
                type=:type
                " . (empty($password) ? '' : ' ,password=:password ') . "
                WHERE id=:id";
        $values = array(
            'username'    => $username,
            'name'  => $name,
            'surname' => $surname,
            'email' => $email,
            'type' => ($type == "Admin") ? 1 : 2,
            'id'    => htmlspecialchars($_POST["id"])
        );
        if (!empty($password))
            $values["password"]=password_hash($password, PASSWORD_BCRYPT);
            
        var_dump($values);
        Database::executeQuery($sql, $values);
    }
}
if (!empty($_GET["modify"])) {
    $query = "SELECT id,username,name,surname,type,email FROM user WHERE id = :id";
    $values = array(
        "id" => $_GET["modify"],
    );

    $result = Database::executeQuery($query, $values);
    $setId = $result[0]["id"];
    $setUser = $result[0]["username"];
    $setName = $result[0]["name"];
    $setSurname = $result[0]["surname"];
    $setType = $result[0]["type"];
    $setEmail = $result[0]["email"];
    $update = true;
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
    <nav>
        <ul>
            <li><a href="whitelist.php">Gestione Whitelist</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

    </nav>
    <h1>Manage Users</h1>
    <div class="container">

        <div class="container login">
            <form action="admin.php" method="post">
                <input type="hidden" name="id" value="<?php if (!empty($setId)) echo $setId ?>">
                <div class="loginC">
                    <span>Username:</span>
                    <input value="<?php if (!empty($setUser)) echo $setUser ?>" type="text" name="username" placeholder="Username...">
                </div>

                <div class="loginC">
                    <span>Name:</span>
                    <input value="<?php if (!empty($setName)) echo $setName ?>" type="text" name="name" placeholder="Name...">
                </div>

                <div class="loginC">
                    <span>Surname:</span>
                    <input value="<?php if (!empty($setSurname)) echo $setSurname ?>" type="text" name="surname" placeholder="Surname...">
                </div>

                <div class="loginC">
                    <span>Email:</span>
                    <input value="<?php if (!empty($setEmail)) echo $setEmail ?>" type="text" name="email" placeholder="Email...">
                </div>

                <div class="loginC">
                    <span>Type:</span>
                    <select name="type">
                        <option>User</option>
                        <option <?php if ($setType == 1) echo "selected" ?>>Admin</option>
                    </select>
                </div>

                <div class="loginC">
                    <span>Password:</span>
                    <input type="text" name="password" placeholder="Password...">
                </div>

                <div class="loginC">
                    <input name="submit" type="submit" value="<?php if ($update) echo "Update";
                                                                else echo "Insert"; ?>">
                </div>
            </form>
            <p class="error">
                <?php echo $error; ?>
            </p>
        </div>
        <?php
        require_once "database.php";
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT 
                    id as ID,
                    username as Username,
                    name as Name,
                    surname as Surname,
                    email as Email,
                    type as Type,
                    password as Password
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
            $ID = $row["ID"];
            foreach ($row as $key => $col) {
                if ($key == "Type") {
                    $type = ($col == 1) ? "Admin" : "User";
                    echo "<td>$type</td>";
                } else {
                    echo "<td>$col</td>";
                }
            }
            echo "<td onclick='remove(this)' id='$ID'><span class='operation'>&times;</span></td>";
            echo "<td onclick='modify(this)' id='$ID'><span class='operation'>&#9998;</span></td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>
    <script>
        function remove(elem) {
            if (confirm('Are you sure you want to remove this thing to the database?')) {
                var value = elem.id;
                location.replace("remove.php?id=" + value);
            }
        }

        function modify(elem) {
            var value = elem.id;
            location.replace("?modify=" + value);
        }
    </script>
</body>

</html>