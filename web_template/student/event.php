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

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['eventID'])) {

    $eventID = $_GET['eventID'];
    $stmtRegistered = $pdo->prepare('SELECT description, courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` = ? ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtRegistered->execute([$eventID]);
    $event = $stmtRegistered->fetch(PDO::FETCH_ASSOC);


    echo "<h1>" . $event['course'] . "</h1>";
    echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
    echo "Email of TA " . $event['ta_email'] . "\n <br>";
    echo "Will start at " . $event['start_time'] . "\n <br>";
    echo "Will end by " . $event['end_time'] . "\n <br>";
    echo "Location is " . $event['location'] . "\n <br>";
    echo "Description is" . $event['description'] . "\n <br>";
    ?>

    <?php
    $time = strtotime($event['start_time']);
    $myFormatForView = date("l d M, g:i A", $time);
    echo "$myFormatForView <br>";

    $stmtTopicUserFrequency->execute([$event['event_id']]);

    foreach ($stmtTopicUserFrequency as $highestFreq) {
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";
    }
    /**
     * Display of inviduals event ends here
     */

} else {

/**
 * We enter this block if GET is not set. We are displaying the list of events here.
 */


//CHANGE HERE
$userID = 3;
//CHANGE HERE


/**
 * Upcoming Registered Events are being displayed here.
 */

$stmtRegistered = $pdo->prepare('SELECT description, courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no  FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` IN (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?) AND `start_time` > NOW() ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
$stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
$stmtRegistered->execute([$userID]);
//$registeredEvents = $stmtRegistered->fetch(PDO::FETCH_ASSOC);

/**
 *html FROM BELOW
 */

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Student | the.TA.thing</title>

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

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.deep_purple-pink.min.css">
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
    </style>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--teal-500">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
            <img src="images/logo.png" height="128" width="128">
            <!-- <h3> the.TA.thing </h3> -->
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--teal-600">
            <div>
                <a href="#overview" class="mdl-layout__tab is-active">Registered</a>
                <a href="#upcoming" class="mdl-layout__tab">Upcoming</a>
            </div>
            <div class="mdl-layout-spacer"></div>
            <div>
                <a href="#edit" class="mdl-layout__tab">Edit Profile</a>
                <a href="#logout" class="mdl-layout__tab">Log Out</a>
            </div>
        </div>

    </header>
    <main class="mdl-layout__content">
        <div class="mdl-layout__tab-panel is-active" id="overview">


            <?php
            foreach ($stmtRegistered as $event) {
                /**
                 * Code for each card for a event starts here
                 * Upcoming Registered Events below
                 * $event is an array which stores all the data
                 */
//
//        echo "<h1>" . $event['course'] . "</h1>";
//        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
//        echo "Email of TA " . $event['ta_email'] . "\n <br>";
//        echo "Will start at " . $event['start_time'] . "\n <br>";
//        echo "Will end by " . $event['end_time'] . "\n <br>";
//        echo "Location is " . $event['location'] . "\n <br>";
//        echo "Description is" . $event['description'] . "\n <br>";

                $time = strtotime($event['start_time']);
                $date = date("l d M", $time);
                $time = date("g:i A", $time);
//        echo "$myFormatForView <br>";

                $stmtTopicUserFrequency->execute([$event['event_id']]);
                $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
//        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


//        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</a>';

                /**
                 * CODE FOR CARDS STARTS FROM BELOW
                 */

                ?>

                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                        <header
                            class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
                        </header>
                        <div class="mdl-card__supporting-text">
                            <div class="mdl-grid">
                                <div class="mdl-cell mdl-cell--8-col">
                                    <h4><?php echo $event['course'] ?></h4>
                                    <h5> <?php echo $event['ta_name'] ?>| <?php echo $highestFreq['topic'] ?></h5>
                                    <?php echo $event['description'] ?>
                                </div>
                                <div class="mdl-cell mdl-cell--2-col">
                                    <h5><?php echo $date ?></h5>
                                    <h5><?php echo $time ?></h5>
                                    <h5><?php echo $event['location'] ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">
                            <a href="?eventID=<?php echo $event['event_id'] ?>" class="mdl-button">View More</a>
                            <a href="#" class="mdl-button">Set Reminder</a>
                        </div>
                    </div>
                </section>


                <?php

                /**
                 * Code for each card for e event ends here
                 */
            }


            /**
             * Past Registered Events are being displayed
             */

            //    echo "<h1>Past Registered Event</h1>";
            $stmtPastRegistered = $pdo->prepare('SELECT courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no  FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` IN (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?) AND `start_time` < NOW() ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
            $stmtPastTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
            $stmtPastRegistered->execute([$userID]);
            //$registeredEvents = $stmtRegistered->fetch(PDO::FETCH_ASSOC);

            ?>


            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">
                    <div class="mdl-card__supporting-text mdl-grid mdl-grid--no-spacing">
                        <h4 class="mdl-cell mdl-cell--12-col">Past Events</h4>


                        <?php
                        foreach ($stmtPastRegistered as $event) {

                            /**
                             * Code for each card for a event starts here
                             * PAST registered Events below
                             * $event is an array which stores all the data
                             */

//        echo "<h1>" . $event['course'] . "</h1>";
//        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
//        echo "Email of TA " . $event['ta_email'] . "\n <br>";
//        echo "Location is " . $event['location'] . "\n <br>";

                            $time = strtotime($event['start_time']);
                            $myFormatForView = date("l d M, g:i A", $time);
//        echo "Which was held on" . "$myFormatForView <br>";

                            $stmtTopicUserFrequency->execute([$event['event_id']]);
                            $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
//        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


//        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</a> <br>';
//        echo '<a href="feedbacks.php/?eventID=' . $event['event_id'] . '">Give Feedback</a> <br>';

                            /**
                             * Code for each card for e event ends here
                             */


                            ?>


                            <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
                                <div class="section__circle-container"><img src="#" alt="TA dp"></img></div>
                            </div>
                            <div
                                class="section__text mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
                                <h5><?php echo $event['course'] ?> | <?php echo $date ?>
                                    | <?php echo $event['location'] ?></h5>
                                Click to <a href="?eventID=<?php echo $event['event_id'] ?>">view more</a> or <a
                                    href="#">give feedback</a>.
                            </div>


                            <?php
                        }

                        ?>


                        <div class="mdl-card__actions">
                            <a href="#" class="mdl-button">View full history</a>
                        </div>
                    </div>
            </section>

            <div class="mdl-layout__tab-panel" id="upcoming">
                <?php


                //CHANGE IT

                /**CODE FOR UPCOMING EVENTS STARTS HERE */

                $upcomingEvents = $pdo->prepare("SELECT
  description,
  courses.name AS course,
  location,
  start_time,
  end_time,
  event_id,
  Y.name AS ta_name,
  user_id AS ta_user_id,
  email AS ta_email,
  dp AS ta_dp,
  roll_no AS ta_roll_no
FROM
  courses
INNER JOIN
  (
  SELECT
    *
  FROM
    users
  INNER JOIN
    (
    SELECT
      *
    FROM
      `events`
    WHERE
      `course_id` IN(
      SELECT DISTINCT
        `course_id`
      FROM
        `student_courses`
      WHERE
        student_id = ?
    )
        AND NOT EXISTS (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?)
  ) AS X ON X.ta_id = users.user_id
) AS Y ON courses.course_id = Y.course_id");
                $stmtPastTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
                $upcomingEvents->execute([$userID, $userID]);
                foreach ($upcomingEvents as $event) {
                    /**
                     * Code for each card for a event starts here
                     * Upcoming Registered Events below
                     * $event is an array which stores all the data
                     */
                    //
                    //        echo "<h1>" . $event['course'] . "</h1>";
                    //        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
                    //        echo "Email of TA " . $event['ta_email'] . "\n <br>";
                    //        echo "Will start at " . $event['start_time'] . "\n <br>";
                    //        echo "Will end by " . $event['end_time'] . "\n <br>";
                    //        echo "Location is " . $event['location'] . "\n <br>";
                    //        echo "Description is" . $event['description'] . "\n <br>";

                    $time = strtotime($event['start_time']);
                    $date = date("l d M", $time);
                    $time = date("g:i A", $time);
                    //        echo "$myFormatForView <br>";

                    $stmtTopicUserFrequency->execute([$event['event_id']]);
                    $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
                    //        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


                    //        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</a>';

                    /**
                     * CODE FOR CARDS STARTS FROM BELOW
                     */

                    ?>

                    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                        <div
                            class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                            <header
                                class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
                            </header>
                            <div class="mdl-card__supporting-text">
                                <div class="mdl-grid">
                                    <div class="mdl-cell mdl-cell--8-col">
                                        <h4><?php echo $event['course'] ?></h4>
                                        <h5> <?php echo $event['ta_name'] ?>| <?php echo $highestFreq['topic'] ?></h5>
                                        <?php echo $event['description'] ?>
                                    </div>
                                    <div class="mdl-cell mdl-cell--2-col">
                                        <h5><?php echo $date ?></h5>
                                        <h5><?php echo $time ?></h5>
                                        <h5><?php echo $event['location'] ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card__actions">
                                <a href="?eventID=<?php echo $event['event_id'] ?>" class="mdl-button">View More</a>
                                <a href="#" class="mdl-button">Register</a>
                            </div>
                        </div>
                    </section>


                <?php }
                } ?>
            </div>
            <section class="section--footer mdl-color--white mdl-grid">
                <div class="mdl-layout-spacer"></div>
                <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
                    <div class="section__circle-container__circle mdl-color--accent section__circle--big"></div>
                </div>
                <div
                    class="section__text mdl-cell mdl-cell--4-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
                    <h5>Made with love by Sids</h5>
                    This was a project created and maintained by Siddharth Dhawan, Siddhartha Jain and Siddhartha Yadav
                </div>
            </section>


        </div>
        <div class="mdl-layout__tab-panel" id="logout">
        </div>
    </main>
</div>
<!-- <a href="https://github.com/google/material-design-lite/blob/mdl-1.x/templates/text-only/" target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast">View Source</a> -->
<script src="https://code.getmdl.io/1.2.1/material.min.js"></script>
</body>
</html>