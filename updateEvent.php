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

    ?>
    <form method="post" action="updateEvent.php">
        <input type="hidden" name="eventID" value="<?php echo $eventID ?>"/>
        Start Time:<br>
        <input id="datepicker" name="start_time" value="<?php echo date($eventData['start_time']) ?>">
        End Time:<br>
        <input id="datepicker" name="end_time" value="<?php echo date($eventData['end_time']) ?>">
        Location: <br>
        <input type="text" name="location" value="<?php echo $eventData['location'] ?>">
        Description: <br>
        <input type="text" name="description" value="<?php echo $eventData['description'] ?>"> <br>
        <button type="submit" value="Submit"> Submit</button>
    </form>
    <?php

}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script> $(function () {
        $("#datepicker").datepicker();
    });</script>