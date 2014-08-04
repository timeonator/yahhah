<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FailedLogins
 *
 * @author Robert
 */
class FailedLogins {

    static function getUser($username) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "SELECT * from failed_logins where username = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('s', $username);

        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }


        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        // 5. Bind selected columns to variables
        $stmt->bind_result($username, $attempts, $last_attempt);

        // 6. Use results

        $results = null;
        while ($stmt->fetch()) {
            echo 'Username: ' . $username . '<br />';
            echo 'Attempts: ' . $attempts . '<br />';
            echo 'Last Attempt: ' . $last_attempt . '<br />';
            echo '<br />';
            $results = [ 'username' => $username, 'attempts' => $attempts, 'last_attempt' => $last_attempt];
        }

        // 7. Free results
        $stmt->free_result();

        // 8. Close statment
        $stmt->close();

        // 9. Close MySQL connection
        $mysqli->close();
        return $results;
    }

    function update($user) {

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "UPDATE failed_logins SET  attempts=?, last_attempt=? WHERE username=?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('iis', $user['attempts'], $user['last_attempt'], $user['username']);
        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->free_result();

        $stmt->close();

        $mysqli->close();
    }

    function insert($user) {

        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "INSERT INTO failed_logins SET username = ?,  attempts = ?, last_attempt = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('sii', $user['username'], $user['attempts'], $user['last_attempt']);
        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->free_result();

        $stmt->close();

        $mysqli->close();
    }

}
