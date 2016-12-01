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
                                    `feedback_id`,
                                    `ta_id`,
                                    `description`
                                  )
                                VALUES(?,?,?,?,?,?,?)');
    $stmtCourses->execute([$course['course_id'], $_POST['location'], $_POST['start_time'], $_POST['end_time'], $userID, $_POST['description']]);

} else {
//confirm if he or she is a ta

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


    $stmtCourses = $pdo->prepare('SELECT * FROM ta_course INNER JOIN courses ON courses.course_id=ta_course.course_id WHERE user_id = ? ');
    $stmtCourses->execute([$userID]);
    $course = $stmtCourses->fetch(PDO::FETCH_ASSOC);


//sugesstion for location
//sugesstion for start time

}
?>