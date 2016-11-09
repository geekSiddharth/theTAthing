<?php

require_once('course-ta-user.php');

class Event
{
    private $mysqli;
    public $eventID;
    public $topic_user;// topic => list_OF_user_objects ;; assoc array with topics as key and value as an array of user object
    public $topic;
    public $topic_frequency;// topic => no_of_users assoc array with topics as key and value as a int representing the number of students choosing that topic
    public $location;
    public $start_time;
    public $end_time;
    public $course;//course object
    public $course_id;
    public $ta_name;
    public $ta;//ta Object
    public $feedback_id;
    public $user;//contains arrays of users object


    function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "thetathing");
        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
        }

    }

    function setEventID($eventID)
    {
        $this->eventID = $eventID;
        $resultEVENT = $this->mysqli->query("SELECT * FROM events WHERE event_id=\"$eventID\"");
        $event = $resultEVENT->fetch_assoc();

        //TA
        $ta_id = $event['ta_id'];
        $this->ta = new TA();
        $this->ta = $this->ta->TA($ta_id);
        $this->ta_name = $this->ta->name;

        //Course
        $this->course_id = $event['course_id'];
        $this->course = new Courses() . selctCourse($this->course_id);
        $this->user = null;

        //User
        $this->user = array();//list of users objects
        $this->topic = array();//list of topics
        $this->topic_frequency = array();//list of topic -> int(showing number of people joinig it)
        $topic_user = json_decode($event['topic_user'], true);
        $j = 0;
        foreach ($topic_user as $topic => $user_list) {
            $i = 0;
            $this->topic[$j++] = $topic;
            foreach ($user_list as $user) {
                $temp = new User() . selectUser($user);
                $topic_user[$topic][$i] = $temp;
                $this->user[$i] = $temp;
                $i++;
            }
            $this->topic_frequency[$topic] = $i;
        }

        $this->topic_user = $topic_user;

    }


    function addtopic_user($topic, $userID)
    {
        $query = "
UPDATE
  `events`
SET
  `course_id` = [ VALUE -2 ],
  `location` = [ VALUE -3 ],
  `start_time` = [ VALUE -4 ],
  `end_time` = [ VALUE -5 ],
  `topics` = [ VALUE -6 ],
  `usersID` = [ VALUE -7 ],
  `topic-user` = [ VALUE -8 ],
  `fixed` = [ VALUE -9 ],
  `feedback_id` = [ VALUE -10 ],
  `ta_id` = [ VALUE -11 ]
WHERE event_id=\"" . $this->eventID . "\"";

    }

    function addTopic($top, $userID)
    {

    }

    function addUsers($userID)
    {

    }

    function updateLocation($location)
    {

    }

    function updateStartTime($startTime)
    {

    }

    function updateEndTime($endTime)
    {

    }



    /* INSERT INTO EVENTS  INSERT INTO `events` (`event_id`, `course_id`, `location`, `start_time`, `end_time`, `topics`, `usersID`, `topic-user`, `fixed`, `feedback_id`, `ta_id`) VALUES ('10', '10', '$location', '2016-11-08 00:00:00', '2016-11-09 00:00:00', 'topics', 'userID', 'topic-user', '0', '123', '234'); */
    /* UPDATE EVENTS UPDATE `events` SET `event_id`=[value-1],`course_id`=[value-2],`location`=[value-3],`start_time`=[value-4],`end_time`=[value-5],`topics`=[value-6],`usersID`=[value-7],`topic-user`=[value-8],`fixed`=[value-9],`feedback_id`=[value-10],`ta_id`=[value-11] WHERE 1 */

    /*UPDATE
      `events`
    SET
      `event_id` = [ VALUE -1 ],
      `course_id` = [ VALUE -2 ],
      `location` = [ VALUE -3 ],
      `start_time` = [ VALUE -4 ],
      `end_time` = [ VALUE -5 ],
      `topics` = [ VALUE -6 ],
      `usersID` = [ VALUE -7 ],
      `topic-user` = [ VALUE -8 ],
      `fixed` = [ VALUE -9 ],
      `feedback_id` = [ VALUE -10 ],
      `ta_id` = [ VALUE -11 ]
    WHERE


    */

    /*
     *
     * INSERT
    INTO
      `events`(
        `event_id`,
        `course_id`,
        `location`,
        `start_time`,
        `end_time`,
        `topics`,
        `usersID`,
        `topic-user`,
        `fixed`,
        `feedback_id`,
        `ta_id`
      )
    VALUES(
      [ VALUE -1 ],
      [ VALUE -2 ],
      [ VALUE -3 ],
      [ VALUE -4 ],
      [ VALUE -5 ],
      [ VALUE -6 ],
      [ VALUE -7 ],
      [ VALUE -8 ],
      [ VALUE -9 ],
      [ VALUE -10 ],
      [ VALUE -11 ]
    )
     */
}

?>