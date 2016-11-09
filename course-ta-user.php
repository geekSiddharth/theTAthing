<?

class Courses
{
    var $course_id;
    var $name;
    var $tasID;
    var $topics;

    function __construct()
    {
        $conn = new mysqli("localhost", "root", "", "thetathing");

    }

    function selectCourse($course_id)
    {

    }

    function updatetopics() // Update Topics
    {

    }

    function update() //Everything
    {

    }

    function addCourse()//parameters in same order as above var declaration
    {

    }

    function deleteCourse($course_id)
    {

    }

    function __destruct()
    {
        $conn->close();
    }
}

class User
{
    var $user_id;
    var $name;
    var $email;
    var $roll_no;
    var $password;
    var $coursesID;
    var $eventsID;
    var $dp;

    function __construct()
    {
        $conn = new mysqli("localhost", "root", "", "thetathing");
    }

    function selectUser($user_id)
    {

    }

    function updatecoursesID()
    {

    }

    function updateeventsID()
    {

    }

    function updateDP()
    {

    }

    function update() //Everything
    {

    }

    function __destruct()
    {
        $conn->close();
    }
}

class TA
{
    var $ta_id;
    var $name;
    var $email;
    var $roll_no;
    var $password;
    var $course_id;
    var $fixed_id;
    var $dp;

    function __construct()
    {
        $conn = new mysqli("localhost", "root", "", "thetathing");
    }

    function TA($ta_id)
    {

    }

    function updatefixed_id()
    {

    }

    function updateDP()
    {

    }

    function update() //Everything
    {

    }

    function __destruct()
    {
        $conn->close();
    }
}

?>