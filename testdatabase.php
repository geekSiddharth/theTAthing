<?php
/**
 * Created by PhpStorm.
 * User: Sid
 * Date: 07-11-2016
 * Time: 03:15 AM
 */

$mysqli = new mysqli("theta.ipdev.in:3306", "theta", "sid@123$", "theta_thing");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $this->mysqli->connect_error;
}

?>