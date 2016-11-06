<?php

require_once('course-ta-user.php');

class Event
{
    private  $mysqli;
    public $eventID;
    public $topic_user;
    public $location;
    public $start_time;
    public $end_time;
    public $course;
    public $course_id;
    public $ta_name;
    public $ta;
    public $feedback_id;


    function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "thetathing");
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }

    }

    function setEventID($eventID)
    {
        $this->eventID =$eventID;
        $resultEVENT = $this->mysqli->query("SELECT * FROM events WHERE event_id=\"$eventID\"");
        $event = $resultEVENT->fetch_assoc();
        $this->ta_id = $event['ta_id'];
        $this->course_id = $event['course_id'];

        $this->ta = new TA();
        $this->ta = $this->ta->TA( $this->ta_id);

    }

}

?>