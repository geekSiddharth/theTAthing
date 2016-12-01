<?php

if (!empty($_POST)) {
    $userD = 2;
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
    $stmtCourses = $pdo->prepare('SELECT * FROM ta_course INNER JOIN courses ON courses.course_id=ta_course.course_id WHERE user_id = ? ');
    $stmtCourses->execute([$userID]);
    $course = $stmtCourses->fetch(PDO::FETCH_ASSOC);
    $stmtCourses = $pdo->prepare('
                                    INSERT
                                INTO
                                  `events`(
                                    `course_id`,
                                    `location`,
                                    `start_time`,
                                    `end_time`,
                                    `ta_id`,
                                    `description`
                                  )
                                VALUES(?,?,?,?,?,?)');

    //$epoch1 =  strtotime($_POST['start_time_date'].' '.$_POST['start_time_time']);
    //$epoch2 =  strtotime($_POST['end_time_date'].' '.$_POST['end_time_time']);

    //$dt1 = new DateTime("@$epoch1");
    //$dt2 = new DateTime("@$epoch2");

    $start_time = $_POST['start_time_date'] . ' ' . $_POST['start_time_time'] . ':00';
    $end_time = $_POST['end_time_date'] . ' ' . $_POST['end_time_time'] . ':00';
    $stmtCourses->execute([$course['course_id'], $_POST['location'], $start_time, $end_time, $userID, $_POST['description']]);
    print_r($stmtCourses);
} else {
//confirm if he or she is a ta
//sugesstion for location
//sugesstion for start time
    ?>
    <form method="post" action="createEvent.php">
        Start Time:<br>
        <input type="date" name="start_time_date"> <input type="time" name="start_time_time">
        End Time:<br>
        <input type="date" name="end_time_date"> <input type="time" name="end_time_time">
        Location: <br>
        <input type="text" name="location">
        Description: <br>
        <input type="text" name="description"> <br>
        <button type="submit" value="Submit"> Submit</button>
    </form>


    <?php
}
?>