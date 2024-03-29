<?php

// Blacklist functions
// Check if an IP has been blacklisted.
// Returns true or false.
function is_blacklisted_ip($ip) {
    $blacklisted_ip = BlackList::getIp(sql_prep($ip));
    return !empty($blacklisted_ip);
}

// The function that actually performs the blocking.
function block_blacklisted_ips() {
    $request_ip = $_SERVER['REMOTE_ADDR'];
    if (isset($request_ip) && is_blacklisted_ip($request_ip)) {
        die("Request blocked");
    }
}

// Add an IP address to the blacklist
// Can be done automatically after a certain 
// amount of bad behavior is reached.
function add_ip_to_blacklist($ip) {
    $record = ['ip' => sql_prep($ip)];
    BlackList::addIp($record['ip']);
    return true;
}

?>
