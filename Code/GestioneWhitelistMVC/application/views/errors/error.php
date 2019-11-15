<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error Page</title>
</head>

<body>
    <h1>Error: <?php echo urldecode($error) ?></h1>
    <p>
        <a href="javascript:history.go(-1)" title="Torna alla pagina precedente">&laquo; Torna indietro</a>
    </p>
</body>

</html>