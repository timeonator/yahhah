<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Robert B Gifford
 */
class User {

    static function getUser($by, $value) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "SELECT * from users where $by = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)
        switch ($by) {
            case 'username':
            case 'reset_token':
                $bind_result = $stmt->bind_param('s', $value);
                break;
            case 'id':
                $bind_result = $stmt->bind_param('i', $value);
                break;
        }
        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }


        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        // 5. Bind selected columns to variables
        $stmt->bind_result($id, $username, $hashed_password, $reset_token);

        // 6. Use results

        $results = NULL;
        while ($stmt->fetch()) {
            echo 'ID: ' . $id . '<br />';
            echo 'Username: ' . $username . '<br />';
            echo 'Password: ' . $hashed_password . '<br />';
            echo '<br />';
            $results = [ 'id' => $id, 'username' => $username, 'hashed_password' => $hashed_password, 'reset_token' => $reset_token];
        }

        // 7. Free results
        $stmt->free_result();

        // 8. Close statment
        $stmt->close();

        // 9. Close MySQL connection
        $mysqli->close();
        return $results;
    }

    function updateResetToken($user) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "UPDATE users SET  reset_token = ? where id = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('si', $user['reset_token'], $user['id']);
        if (!$bind_result) {
            echo "Binding failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // 4. Execute
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    function findbyResetToken($token) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_errno) {
            die("Connect failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
        }

        $sql = "select * from users where 'reset_value' = ? ";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        // 3. Bind params
        // s = string
        // i = integer
        // d = double (float)
        // b = blob (binary data)

        $bind_result = $stmt->bind_param('s', $user['reset_token']);
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
