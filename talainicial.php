<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="#" method="post">
        <input type="submit"name="registro" value="Registrar Chegada">
    </form>
</body>

<?php
if(isset($_POST['registro'])){
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = time();
    echo "Data: " . date("d/m/Y H:i:s", $dataAtual);
}
?>
</html>