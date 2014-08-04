<?php

// Brute force throttling


function record_failed_login($username) {
    $failed_login = FailedLogins::getUser(sql_prep($username));
    if (!isset($failed_login['last_attempt'])) {

        $failed_login = [
            'username' => sql_prep($username),
            'attempts' => 1,
            'last_attempt' => time()
        ];
        FailedLogins::insert($failed_login);
    } else {
        // existing failed_login record
        $failed_login['attempts'] = $failed_login['attempts'] + 1;
        $failed_login['last_attempt'] = time();
        FailedLogins::update($failed_login);
    }

    return true;
}

function clear_failed_logins($username) {
    $failed_login = FailedLogins::getUser(sql_prep($username));

    if (isset($failed_login)) {
        $failed_login['attempts'] = 0;
        $failed_login['last_attempt'] = time();
        FailedLogins::update($failed_login);
    }

    return true;
}

// Returns the number of minutes to wait until logins 
// are allowed again.
function throttle_failed_logins($username) {
    $throttle_at = 5;
    $delay_in_minutes = 10;
    $delay = 60 * $delay_in_minutes;

    $failed_login = FailedLogins::getUser(sql_prep($username));

    // Once failure count is over $throttle_at value, 
    // user must wait for the $delay period to pass.
    if (isset($failed_login) && $failed_login['attempts'] >= $throttle_at) {
        $remaining_delay = ($failed_login['last_attempt'] + $delay) - time();
        $remaining_delay_in_minutes = ceil($remaining_delay / 60);
        return $remaining_delay_in_minutes;
    } else {
        return 0;
    }
}

?>
