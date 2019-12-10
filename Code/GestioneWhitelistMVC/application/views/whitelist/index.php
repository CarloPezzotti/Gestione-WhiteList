<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/assets/css/global.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/modal.css">
    <title>Index</title>
</head>

<body>
    <nav>
        <ul>
            <?php

            use Libs\Auth;
            use Models\Whitelist;

            if (Auth::getAutentication() == Auth::ADMIN) echo "<li><a href=" . URL . "adminpanel/index>Gestione Users</a></li>"; ?>
            <li><a href="<?php echo URL . "whitelistpanel/logout" ?>">Logout</a></li>
            <li style="float:right;"><span>Username: <b><?php echo Auth::getUsername() ?></b></span></li>
        </ul>
    </nav>
    <h1>Manage Whitelist</h1>
    <div class="container">
        <?php
        if (Auth::getAutentication() == Auth::ADMIN) {
            ?>
            <div class="container login">
                <div class="container login content">
                    <form action="<?php echo URL . "whitelistpanel/insertCategorie" ?>" method="post">
                        <table>
                            <tr>
                                <td>
                                    <span>Categorie:</span>
                                </td>
                                <td>
                                    <input type="text" name="categorie" placeholder="Categorie..." id="">
                                </td>
                                <td>
                                    <input type="submit" value="Insert">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="fstTableContainer">
                <div class="tableContainer">
                    <table class="tableR">
                        <thead>
                            <tr>
                                <td colspan="2">
                                    Categorie
                                </td>
                                <td>
                                </td>
                            </tr>
                        </thead>
                        <?php
                            $table = Whitelist::getCategories();
                            $show = "";
                            for ($i = 0; $i < count($table); $i++) {
                                echo "<tr><td>$table[$i]</td>";
                                echo "<td><a onclick='removeCategoria(\"" . trim($table[$i]) . "\")'><span class='operation'>&times;</span></a></td></tr>";
                            }
                            ?>
                    </table>
                </div>
            </div>
        <?php
        }
        ?>

        <div class="container login">
            <div class="container login content">
                <form action="<?php echo URL . "whitelistpanel/insert" ?>" method="post">
                    <table>
                        <tr>
                            <td>
                                <span>URL:</span>
                            </td>
                            <td><select name="lastPart" onchange='replaceOther(this,"Last part...")'>
                                    <option>www</option>
                                    <option>web</option>
                                    <option>docs</option>
                                    <option>*</option>
                                    <option>Other</option>
                                </select></td>
                            <td><span>.</span></td>
                            <td><input type="text" name="middlePart" placeholder="Domain..."></td>
                            <td><span>.</span></td>
                            <td>
                                <select name="fstPart" onchange='replaceOther(this,"First part...")'>
                                    <option>com</option>
                                    <option>ch</option>
                                    <option>it</option>
                                    <option>Other</option>
                                </select></td>
                            <td>
                                <select style="float: right" name="categorie">
                                    <?php
                                    $categories = Whitelist::getCategories();
                                    for ($i = 0; $i < count($categories); $i++) {
                                        echo "<option>" . $categories[$i] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="7">
                                <input style="float: right" type="submit" value="Insert">
                                <button type="button" style="float:right" onclick="reload()">
                                    <span>Reload</span>
                                </button>
                                <span class="error" style="float: left"><?php if (isset($error)) echo $error; ?></span>
                                <span class="warning" style="float: left"><?php if (isset($info)) echo $info; ?></span>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <div class="fstTableContainer">
            <span style="float:left;display:inline-block">Filter table: <input type="text" onkeyup="searchInTable(this.value)" placeholder="Search url..."></span><br><br>

            <?php
            $table = Whitelist::get();
            $show = "";
            foreach ($table as $key => $value) {
                echo "<div class='tableContainer'><table   class='tableR' id='$key'>
                        <thead style='cursor: pointer;' onclick='showContent(this)'><tr><th>$key</th></tr></thead>
                        <tbody class='site' style='display:$show;'>";
                for ($i = 0; $i < count($value); $i++) {
                    if (trim($value[$i]) != "") {
                        echo "<tr><td>$value[$i]</td>";
                        echo "<td><a onclick='removeSite(\"" . urlencode(trim($value[$i])) . "\")'><span class='operation'>&times;</span></a></td></tr>";
                    }
                }
                echo "</tbody></table></div>";
                $show = "none";
            }
            ?>
        </div>
    </div>

    <script src="/assets/js/modal.js"></script>
    <script type="text/javascript">
        var currentTable;

        /** 
         * Funzione che va a rimpizare in un input select l'opzione other con un input di testo generico
         */
        function replaceOther(val, placeholder) {
            if (val.value == 'Other') {
                var index = Array.prototype.indexOf.call(val.parentElement.childNodes, val) - 1;
                var input = document.createElement("input");
                input.name = val.name;
                console.log(val.offsetHeight);
                input.style.width = val.offsetWidth + "px";
                input.style.height = val.offsetHeight + "px";
                input.childNodes = val.childNodes;
                input.setAttribute("placeholder", placeholder);
                val.parentElement.insertBefore(input, val.parentElement.childNodes[index]);
                val.style.display = 'none';
                val.name = "old" + input.name;
            }
        }

        /**
         * Funzione che va a cercare all'interno di una tabella un valore speifico rimuovendo
         * le celle che non contengono il valore cercato
         */
        function searchInTable(value) {
            var filter = value.toLowerCase();
            var rows = currentTable.querySelector("tbody").rows;
            for (var i = 0; i < rows.length; i++) {
                for (var j = 0; j < rows[i].cells.length; j++) {
                    var col = rows[i].cells[j].textContent.toLowerCase();;
                    if (col.indexOf(filter) > -1) {
                        rows[i].cells[j].style.display = "";
                    } else {
                        rows[i].cells[j].style.display = "none";
                    }
                }
            }
        }

        function showContent(thead) {

            var tbodies = document.getElementsByClassName("site");
            for (var i = 0; i < tbodies.length; i++) {
                tbodies[i].style.display = "none";
            }

            var table = thead.parentElement;
            currentTable = table;
            if (table.tBodies[0].style.display == "none") {
                table.tBodies[0].style.display = "";
            } else {
                table.tBodies[0].style.display = "none";
            }
        }


        function removeCategoria(categoria) {
            if (confirm("Are you sure?") == true) {
                window.location.href = "/whitelistpanel/deleteCategoria/" + encodeURI(categoria);
            }
        }

        function removeSite(site) {
            if (confirm("Are you sure?") == true) {
                window.location.href = "/whitelistpanel/deleteSite/" + btoa(site);
            }
        }

        function reload() {
            window.location.href = "/whitelistpanel/reload/";
        }
    </script>



</body>

</html>