<?php
/**
 * Created by PhpStorm.
 * User: Sid
 * Date: 01-12-2016
 * Time: 01:32 AM
 */


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


if (!empty($_GET['eventID'])) {
    //Check wheather student in
    $eventData = $pdo->prepare('SELECT * FROM events INNER JOIN courses ON events.course_id = courses.course_id WHERE event_id = ?');
    $eventData->execute([$eventID]);
    $eventData = $eventData->fetch(PDO::FETCH_ASSOC);

    echo "<h1>FEEDBACK FORM FOR " . $eventData['name'] . "</h1><br>";

    $stmtRegistered = $pdo->prepare('SELECT description, courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` = ? ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtRegistered->execute([$eventID]);
    $event = $stmtRegistered->fetch(PDO::FETCH_ASSOC);
    $stmtTopicUserFrequency->execute([$event['event_id']]);
    foreach ($stmtTopicUserFrequency as $highestFreq) {
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";
    }
    ?>


    <?php
}
?>