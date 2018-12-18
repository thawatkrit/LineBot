<?php
    require 'messageController.php';

    foreach($userList as $user) {
        echo '<h2>'.$user->getUserId().'</h2>';
        foreach($user->getMessages() as $message) {
            echo '<p>'.$message.'</p>';
        }
    }