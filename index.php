<?php
if (isset($_POST['submit'])) {
    $user = $_POST['user'];
    $password = $_POST['pass'];

    $ch = curl_init("https://plus.st:8448/_matrix/client/r0/login");

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        '{"type":"m.login.password", "user":"' . $user . '", "password":"' . $password . '"}'
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    echo curl_exec($ch);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbi Login</title>
</head>

<body>
    <form action="./" method="post">
        <input type="text" name="user">
        <input type="password" name="pass" id="pass">
        <input type="submit" value="submit" name="submit">
    </form>
</body>

</html>