<?php

$host = '127.0.0.1';
$db = 'thetathing';
$user = 'root';
$pass = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $pass, $opt);
$userID = 1;
if (!empty($_POST)) {
    $eventID = $_POST['eventID'];
    $eventData = $pdo->prepare('SELECT * FROM events WHERE event_id = ? AND ta_id = ?');
    $eventData->execute([$eventID, $userID]);
    $eventData = $eventData->fetch(PDO::FETCH_ASSOC);


    $eventID = $_POST['eventID'];
    $location = $_POST['location'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['description'];

    try {
        $pdo->beginTransaction();
        $stmtUpdate = $pdo->prepare('UPDATE `events` SET `location`=?,`start_time`=?,`end_time`=?,`description`=? WHERE `event_id` = ? AND `ta_id`= ?');
        $stmtUpdate->execute([$location, $start_time, $end_time, $description, $eventID, $userID]);
        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo $e->getMessage();
    }

    //REDIRECT TO VIEW EVENTS
} elseif (!empty($_GET['eventID'])) {
    $eventID = $_GET['eventID'];
    $eventData = $pdo->prepare('SELECT * FROM events WHERE event_id = ? AND ta_id = ?');
    $eventData->execute([$eventID, $userID]);
    $eventData = $eventData->fetch(PDO::FETCH_ASSOC);


    $time = strtotime($eventData['start_time']);
    $date = date("l d M", $time);
    echo "$date";
    $time = date("g:i A", $time);

    ?>


    <!doctype html>

    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <title>TA | the.TA.thing</title>

        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="icon" sizes="192x192" href="images/android-desktop.png">

        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Material Design Lite">
        <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

        <!-- Tile icon for Win8 (144x144 + tile color) -->
        <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
        <meta name="msapplication-TileColor" content="#3372DF">

        <link rel="shortcut icon" href="images/favicon.png">

        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.2.0/material.deep_purple-pink.min.css">
        <link rel="stylesheet" href="styles.css">
        <style>
            #view-source {
                position: fixed;
                display: block;
                right: 0;
                bottom: 0;
                margin-right: 40px;
                margin-bottom: 40px;
                z-index: 900;
            }

            .mdl-cell {
                align-content: center;
                text-align: center;
                font-size: 28px;

            }

            label {
                color: blueviolet;
            }
        </style>
    </head>
    <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--teal-500">
            <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            </div>
            <div class="mdl-layout--large-screen-only mdl-layout__header-row">
                <img src="images/logo.png" height="128" width="128"></img>
            </div>
            <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            </div>
            <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--teal-600">
                <a href="index.html" class="mdl-layout__tab">Dashboard</a>
                <a href="#overview" class="mdl-layout__tab is-active">Update Event</a>
            </div>
        </header>
        <main class="mdl-layout__content">
            <div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <form action="update.php" method="post" id="form1">
                        <div class="mdl-grid">
                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="time" id="starttime"
                                           name="start_time_time">
                                    <label class="mdl-textfield__label" for="starttime">Start Time</label>
                                </div>
                            </div>

                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="date" id="startdate"
                                           name="start_time_date">
                                    <label class="mdl-textfield__label" for="startdate">Start Date</label>
                                </div>
                            </div>

                            <br>
                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="time" id="endtime" name="end_time_time">
                                    <label class="mdl-textfield__label" for="endtime">End Time</label>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input" type="date" id="enddate" name="end_time_date">
                                    <label class="mdl-textfield__label" for="enddate">End Time</label>
                                </div>
                            </div>
                            <br>
                            <div class="mdl-cell mdl-cell--12-col">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield">
                                    <input class="mdl-textfield__input" type="text" id="location" name="location">
                                    <label class="mdl-textfield__label" for="location">Location</label>
                                </div>
                            </div>
                            <div class="mdl-cell mdl-cell--12-col">
                                <div class="mdl-textfield mdl-js-textfield">
                                    <input class="mdl-textfield__input" type="text" rows="4" id="desc" name="desc">
                                    <label class="mdl-textfield_input" for="desc">Description</label>
                                </div>
                            </div>
                            <br>
                            <div class="mdl-cell mdl-cell--12-col">
                                <button type="submit"
                                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </section>
        </main>
    </div>
    <script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>
    </html>
    <?php
}

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script> $(function () {
        $("#datepicker").datepicker();
    });</script>