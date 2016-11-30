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

    $userID = 1;//Check This

    $course_id = $_POST['course_name']; //Check This
    $location = $_POST['location'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $ta_id = $userID; //Check This
    $description = $_POST['description'];
    try {
        $pdo->beginTransaction();
        $stmtInsertQuery = $pdo->prepare('
                                    INSERT
                                INTO
                                  `events`(
                                    `course_id`,
                                    `location`,
                                    `start_time`,
                                    `end_time`,
                                    `fixed`,
                                    `ta_id`,
                                    `description`
                                  )
                                VALUES(?,?,?,?,?,?,?,?)');

        $stmtInsertQuery->execute([$course_id, $location, $start_time, $end_time, 0, $ta_id, $description]);
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo $e->getMessage();
    }


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

    $userID = 1; //Check This

    $stmtCourses = $pdo->prepare('SELECT * FROM ta_course INNER JOIN courses ON courses.course_id=ta_course.course_id WHERE user_id = ? ');
    $stmtCourses->execute([$userID]);
    foreach ($stmtCourses as $course) {
        echo "Courses Name Are <br>" . $course['name'];
    }


//sugesstion for location
//sugesstion for start time


}
?>