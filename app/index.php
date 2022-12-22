<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location: ../');

    exit();
}

include("../settings.php");

$sidebar = "";

$ch = curl_init("https://" . $home_server . ":8448/_matrix/client/v3/sync?access_token=" . $_SESSION["access_token"]);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$res = curl_exec($ch);

$data = json_decode($res, true);

$rooms = array();

foreach (array_keys($data["rooms"]["join"]) as $room) {
    foreach ($data["rooms"]["join"][$room]["state"]["events"] as $event) {
        if ($event["type"] == "m.room.name") {
            $rooms[] = array(
                "name" => $event["content"]["name"],
                "id" => $room
            );
        }
    }
}
$messages = array();
if (isset($_GET["room"])) {
    foreach ($data["rooms"]["join"][$_GET["room"]]["timeline"]["events"] as $event) {
        if ($event["type"] == "m.room.message") {
            $messages[] = array(
                "body" => $event["content"]["body"],
                "sender" => $event["sender"]
            );
        }
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
    <title>Arbi App</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .full {
            height: 100%;
            width: 100%;
            border-spacing: 0px;
        }

        .sidebar {
            width: 20%;
        }
        .messages
        {
            overflow-y: scroll;
        }
    </style>
</head>

<body>
    <table class="full">
        <tr>
            <td class="sidebar">
                <?php
                foreach ($rooms as $id) {
                    echo '
                <div>
                    <a class="btn btn-sm btn-default" href="./?room=' . $id["id"] . '" role="button">' . $id["name"] . '</a>
                </div>';
                }
                ?>
            </td>
            <td class="messages">
                <?php
                foreach ($messages as $id) {
                    echo '
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">' . $id["sender"] . '</h3>
                    </div>
                    <div class="panel-body">
                        ' . $id["body"] . '
                    </div>
                </div>';
                }
                ?>
            </td>


        </tr>
    </table>
</body>

</html>