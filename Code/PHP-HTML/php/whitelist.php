<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/global.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <title>Gestione White</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <h1>Manage Whitelist</h1>
    <div class="container">
        <div class="container table">
            <div class="table header">
                <h1>Whitelisted sites</h1>
            </div>
            <div class="table body">
                <?php
                    require_once "file.php";
                    $lines = File::readFile("/etc/squid/whitelist.acl");
                    echo "<table><tr>";
                    for ($i = 0; $i < count($lines); $i++) {
                        echo "<td>$lines[$i]</td>";
                    }
                    echo "</tr></table>";
                ?>
            </div>
        </div>
    </div>
</body>

</html>