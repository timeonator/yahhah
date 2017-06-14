<?php require_once("../private/initialize.php"); ?>

<?php

// initialize variables to default values
$username = "";
$message = "";

if (request_is_post() && request_is_same_domain()) {

    if (!csrf_token_is_valid() || !csrf_token_is_recent()) {
        $message = "Sorry, request was not valid.";
    } else {
        // CSRF tests passed--form was created by us recently.
        // retrieve the values submitted via the form
        $username = $_POST['username'];

        if (has_presence($username)) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            // Search our fake database to retrieve the user data
            $sqlsafe_username = sql_prep($username);
            $user = User::getUser('username', $sqlsafe_username);

            if ($user) {
                // Username was found; okay to reset
                create_reset_token($username);
                email_reset_token($username);
            } else {
                // Username was not found; don't do anything
            }

            // Message returned is the same whether the user 
            // was found or not, so that we don't reveal which 
            // usernames exist and which do not.
            $message = "A link to reset your password has been sent to the email address on file.";
        } else {
            $message = "Please enter a username.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Forgot Password</title>
    </head>
    <body>

        <?php
        if ($message != "") {
            echo '<p>' . h($message) . '</p>';
        }
        ?>

        <p>Enter your username to reset your password.</p>

        <form action="forgot_password.php" method="POST" accept-charset="utf-8">
            <?php echo csrf_token_tag(); ?>
            Username: <input type="text" name="username" value="<?php echo h($username); ?>" /><br />
            <br />
            <input type="submit" name="submit" value="Submit" />
        </form>

        <?php
 //       echo "<br /><br />";
 //       echo "--- fake database contents ---";
 //       echo "------------------------------";
        ?>
    </body>
</html>
