<?php
session_start();

include("settings.php");

if (isset($_POST['submit'])) {
    $user = $_POST['user'];
    $password = $_POST['pass'];

    $ch = curl_init("https://" . $home_server . ":8448/_matrix/client/v3/login");

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        '{"type":"m.login.password", "user":"' . $user . '", "password":"' . $password . '"}'
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res_raw = curl_exec($ch);

    $res = json_decode($res_raw, true);

    if (!isset($res["error"])) {
        $_SESSION["user_id"]      = $res["user_id"];
        $_SESSION["access_token"] = $res["access_token"];
        $_SESSION["home_server"]  = $res["home_server"];
        $_SESSION["device_id"]    = $res["device_id"];

        header('Location: ./app/');

        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/bootstrap.min.css">
    <title>Arbi Login</title>
</head>

<body>
    <div class="container">
        <form action="./" method="post" class="form-horizontal">
            <div>
                <label for="user" class="control-label">Username</label>
                <input type="text" name="user">
            </div>
            <div>
                <label for="pass" class="control-label">Password</label>
                <input type="password" name="pass" id="pass">
            </div>
            <div>
                <input type="submit" class="btn btn-primary" value="Submit!" name="submit">
            </div>
        </form>
        
        <div class="error">
            <?php
            if (isset($res["error"])) {
                echo $res["error"];
            }
            ?>
        </div>
    </div>
</body>

</html>