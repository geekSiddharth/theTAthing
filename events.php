<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['eventID'])) {

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
    $eventID = $_GET['eventID'];
    $stmtRegistered = $pdo->prepare('SELECT courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` = ? ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtRegistered->execute([$eventID]);
    $event = $stmtRegistered->fetch(PDO::FETCH_ASSOC);

    echo "<h1>" . $event['course'] . "</h1>";
    echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
    echo "Email of TA " . $event['ta_email'] . "\n <br>";
    echo "Will start at " . $event['start_time'] . "\n <br>";
    echo "Will end by " . $event['end_time'] . "\n <br>";
    echo "Location is " . $event['location'] . "\n <br>";

    $time = strtotime($event['start_time']);
    $myFormatForView = date("l d M, g:i A", $time);
    echo "$myFormatForView <br>";

    $stmtTopicUserFrequency->execute([$event['event_id']]);
    foreach ($stmtTopicUserFrequency as $highestFreq) {
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";
    }

} else {


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

    echo "<h1>Upcoming Registered Event</h1>";
    $stmtRegistered = $pdo->prepare('SELECT courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no  FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` IN (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?) ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtRegistered->execute([$userID]);
//$registeredEvents = $stmtRegistered->fetch(PDO::FETCH_ASSOC);
    foreach ($stmtRegistered as $event) {

        echo "<h1>" . $event['course'] . "</h1>";
        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
        echo "Email of TA " . $event['ta_email'] . "\n <br>";
        echo "Will start at " . $event['start_time'] . "\n <br>";
        echo "Will end by " . $event['end_time'] . "\n <br>";
        echo "Location is " . $event['location'] . "\n <br>";

        $time = strtotime($event['start_time']);
        $myFormatForView = date("l d M, g:i A", $time);
        echo "$myFormatForView <br>";

        $stmtTopicUserFrequency->execute([$event['event_id']]);
        $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</>';
    }


}


?>