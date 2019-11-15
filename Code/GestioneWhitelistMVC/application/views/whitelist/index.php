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
        <div class="container login">
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
                        <td><select name="fstPart" onchange='replaceOther(this,"First part...")'>
                                <option>com</option>
                                <option>ch</option>
                                <option>it</option>
                                <option>Other</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <input style="float: right" type="submit" value="Insert">
                            <span class="error" style="float: left"><?php if (isset($error)) echo $error; ?></span>
                            <span class="warning" style="float: left"><?php if (isset($info)) echo $info; ?></span>
                        </td>
                    </tr>
                </table>


            </form>
        </div>
        <div class="fstTableContainer">
            <span style="float:left">Filter table: <input type="text" onkeyup="searchInTable(this.value)" placeholder="Search url..."></span><br><br>
            <div class="tableContainer">
                <table class='tableR' id="table">
                    <thead>
                        <tr>
                            <th colspan="4">URL</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $file = Whitelist::get();
                        for ($i = 0; $i < count($file); $i++) {
                            echo "<tr>";
                            /*for ($j = 0; $j < 1; $j++) {*/
                                $cell = ((isset($file[$i]) && !empty($file[$i])) ? $file[$i] : "-");
                                echo "<td class='operation' onClick='showModal(this)'>" . $cell . "</td>";
                            //}
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Configure web domain</h2>
                <h3 class="domainName"></h3>
            </div>
            <div class="modal-body">
                <iframe id="iframeDomain"></iframe>
                <div>
                    <a id="removeDomain" href="<?php echo URL . "whitelistpanel/remove/" ?>">Remove</a>
                </div>
            </div>
            <div class="modal-footer">
                <p id="domainName"></p>
            </div>
        </div>
    </div>


    <script src="/assets/js/modal.js"></script>
    <script type="text/javascript">
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
            var rows = document.querySelector("#table tbody").rows;
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

        function showModal(site) {
            site = site.innerHTML;
            document.getElementById("myModal").style.display = "block";
            document.getElementById("domainName").innerHTML = "Domain name: " + site;
            var iframe = document.getElementById("iframeDomain");
            iframe.setAttribute("src", "https://" + site);
            iframe.style.width = "640px";
            iframe.style.height = "480px";
            var href = document.getElementById("removeDomain");
            href.setAttribute("href",href.getAttribute("href")+window.btoa(site));

        }
    </script>
</body>

</html>