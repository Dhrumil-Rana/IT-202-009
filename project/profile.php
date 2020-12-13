<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    die(header("Location: login.php"));
}

$db = getDB();
//save data if we submitted the form
if (isset($_POST["saved"])) {
    $isValid = true;
    //check if our email changed
    $newEmail = get_email();
    if (get_email() != $_POST["email"]) {
        //TODO we'll need to check if the email is available
        $email = $_POST["email"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where email = :email");
        $stmt->execute([":email" => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash( "Email is already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newEmail = $email;
        }
    }
    $newUsername = get_username();
    if (get_username() != $_POST["username"]) {
        $username = $_POST["username"];
        $stmt = $db->prepare("SELECT COUNT(1) as InUse from Users where username = :username");
        $stmt->execute([":username" => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $inUse = 1;//default it to a failure scenario
        if ($result && isset($result["InUse"])) {
            try {
                $inUse = intval($result["InUse"]);
            }
            catch (Exception $e) {

            }
        }
        if ($inUse > 0) {
            flash( "Username is already in use");
            //for now we can just stop the rest of the update
            $isValid = false;
        }
        else {
            $newUsername = $username;
        }
    }
    if ($isValid) {
        $stmt = $db->prepare("UPDATE Users set email = :email, username= :username where id = :id");
        $r = $stmt->execute([":email" => $newEmail, ":username" => $newUsername, ":id" => get_user_id()]);
        if ($r) {
            flash( "Updated profile\n");
        } else {
            flash( "Error updating profile");
        }
        //password is optional, so check if it's even set
        //if so, then check if it's a valid reset request
        if(isset($_post["current"])) {
            $stmt = $db->prepare("SELECT password from Users WHERE id = :id LIMIT 1");
            $stmt -> execute([":id" => get_user_id()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($_POST["current"], $result["password"])) {
                if (!empty($_POST["password"]) && !empty($_POST["confirm"])) {
                    if ($_POST["password"] == $_POST["confirm"]) {
                        $password = $_POST["password"];
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        //this one we'll do separate
                        $stmt = $db->prepare("UPDATE Users set password = :password where id = :id");
                        $r = $stmt->execute([":id" => get_user_id(), ":password" => $hash]);
                        if ($r) {
                            flash( "<br>\n", "Reset password");
                        } else {
                            flash( "Error resetting password");
                        }
                    }
                }
            }
        }

//fetch/select fresh data in case anything changed
        $stmt = $db->prepare("SELECT email, username from Users WHERE id = :id LIMIT 1");
        $stmt->execute([":id" => get_user_id()]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $email = $result["email"];
            $username = $result["username"];
            //let's update our session too
            $_SESSION["user"]["email"] = $email;
            $_SESSION["user"]["username"] = $username;
        }
    }
    else {
        //else for $isValid, though don't need to put anything here since the specific failure will output the message
    }
}


?>

<form method="POST">
    <label for="email">Email</label>
    <input type="email" name="email" value="<?php safer_echo(get_email()); ?>"/>
    <label for="username">Username</label>
    <input type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>"/>
    <!-- DO NOT PRELOAD PASSWORD-->
    <label for="curpas">Current Password</label>
    <input type = "password" name = "current" />
    <label for="pw">Password</label>
    <input type="password" name="password"/>
    <label for="cpw">Confirm Password</label>
    <input type="password" name="confirm"/>
    <input type="submit" name="saved" value="Save Profile"/>
</form>

    <form action ="scorehistory.php" method ="POST">
        <input type = "submit" value = "Previous scores">
    </form>

<br>
<!-- this is for the top thingy -->
    <form action="top.php" method ="get">
    <label for="score">Choose the top score: </label>
    <input list ="scores" name = "score" id ="score">
    <datalist id = "scores">
        <option value= "Top weekly">
        <option value="Top monthly">
        <option value ="Top Lifetime">
    </datalist>
    <input type = "submit">
    </form>
<br>
<!-- this is for the competition -->

    <form action ="create_competition.php" method ="POST">
        <input type = "submit" value = "Create Competition">
    </form>

    <form action ="mycompetition.php" method ="POST">
        <input type = "submit" value = "My competition">
    </form>

    <form action ="Competition.php" method ="POST">
        <input type = "submit" value = "Running Competition">
    </form>

<?php require(__DIR__ . "/partials/flash.php");
