<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BlackList
 *
 * @author Robert
 */
class BlackList {

    function getIp($value) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "SELECT * from blacklist where 'ip' = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('s', $value);

        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }


        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        // 5. Bind selected columns to variables
        $stmt->bind_result($ip, $comment);

        // 6. Use results

        $results = null;
        while ($stmt->fetch()) {
            echo 'Ip: ' . $ip . '<br />';
            echo 'Comment: ' . $comment . '<br />';
            echo '<br />';
            $results = [ 'ip' => $ip, 'comment' => $comment,];
        }

        // 7. Free results
        $stmt->free_result();

        // 8. Close statment
        $stmt->close();

        // 9. Close MySQL connection
        $mysqli->close();
        return $results;
    }

    function addIp($ip) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "INSERT into blacklist SET  'ip' = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('s', $ip);
        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

}
