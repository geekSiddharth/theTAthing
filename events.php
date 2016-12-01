
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


    $userID = 3;

    /**
     * Upcoming Registered Events are being displayed here.
     */

    echo "<h1>Upcoming Registered Event</h1>";
    $stmtRegistered = $pdo->prepare('SELECT description, courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no  FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` IN (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?) AND `start_time` > NOW() ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtRegistered->execute([$userID]);
    //$registeredEvents = $stmtRegistered->fetch(PDO::FETCH_ASSOC);
    foreach ($stmtRegistered as $event) {
        /**
         * Code for each card for a event starts here
         * Upcoming Registered Events below
         * $event is an array which stores all the data
         */

        echo "<h4>" . $event['course'] . "</h4>";
        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
        echo "Email of TA " . $event['ta_email'] . "\n <br>";
        echo "Will start at " . $event['start_time'] . "\n <br>";
        echo "Will end by " . $event['end_time'] . "\n <br>";
        echo "Location is " . $event['location'] . "\n <br>";
        echo "Description is" . $event['description'] . "\n <br>";

        $time = strtotime($event['start_time']);
        $myFormatForView = date("l d M, g:i A", $time);
        echo "$myFormatForView <br>";

        $stmtTopicUserFrequency->execute([$event['event_id']]);
        $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</a>';

        /**
         * Code for each card for e event ends here
         */
    }


    /**
     * Past Registered Events are being displayed
     */

    echo "<h1>Past Registered Event</h1>";
    $stmtPastRegistered = $pdo->prepare('SELECT courses.name as course, location, start_time, end_time, event_id, Y.name as ta_name, user_id as ta_user_id, email as ta_email, dp as ta_dp, roll_no as ta_roll_no  FROM courses INNER JOIN (SELECT * FROM users INNER JOIN ( SELECT * FROM `events` WHERE `event_id` IN (SELECT DISTINCT `event_id` FROM `user-topic` WHERE user_id = ?) AND `start_time` < NOW() ORDER BY `start_time` DESC ) as X ON X.ta_id=users.user_id) as Y ON courses.course_id=Y.course_id');
    $stmtPastTopicUserFrequency = $pdo->prepare('SELECT COUNT(user_id) AS freq, user_id, event_id, topic  FROM `user-topic` WHERE event_id=? GROUP BY topic ORDER BY freq DESC ');
    $stmtPastRegistered->execute([$userID]);
//$registeredEvents = $stmtRegistered->fetch(PDO::FETCH_ASSOC);
    foreach ($stmtPastRegistered as $event) {

        /**
         * Code for each card for a event starts here
         * PAST registered Events below
         * $event is an array which stores all the data
         */

        echo "<h4>" . $event['course'] . "</h4>";
        echo "To be taken by TA - " . $event['ta_name'] . "\n <br>";
        echo "Email of TA " . $event['ta_email'] . "\n <br>";
        echo "Location is " . $event['location'] . "\n <br>";

        $time = strtotime($event['start_time']);
        $myFormatForView = date("l d M, g:i A", $time);
        echo "Which was held on" . "$myFormatForView <br>";

        $stmtTopicUserFrequency->execute([$event['event_id']]);
        $highestFreq = $stmtTopicUserFrequency->fetch(PDO::FETCH_ASSOC);
        echo "-" . $highestFreq['topic'] . " --- " . $highestFreq['freq'] . "<br>";


        echo '<a href="events.php/?eventID=' . $event['event_id'] . '"> View More</a> <br>';
        echo '<a href="feedbacks.php/?eventID=' . $event['event_id'] . '">Give Feedback</a> <br>';

        /**
         * Code for each card for e event ends here
         */
    }

}

$queryFORupcoming = "
SELECT
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
) AS Y ON courses.course_id = Y.course_id"


?>