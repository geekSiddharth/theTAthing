<?php

/**
 * Created by PhpStorm.
 * User: Sid
 * Date: 07-11-2016
 * Time: 02:24 PM
 */
class userEvent extends Event
{
    public $userEventID;

    function setUserID($userID)
    {
        $this->userEventID = new User().selectUser($userID);
    }
}
?>