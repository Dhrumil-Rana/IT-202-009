<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<form method="POST">
    <label for ="email">Email</label>
    <input type ="email" name = "email"/>
    <label for="pw">Password</label>
    <input type="password" name="password"/>
    <label for="cpw">Confirm Password</label>
    <input type="password" name="confirm"/>
    <input type="submit" name="saved" value="Save Profile"/>
</form>

<?php
if(isset($_POST["saved"]))
{

    if (isset($_POST["password"]) && isset($_POST["confirm"]) && isset($_POST["email"])) {

        $email = $_POST["email"];
        $password = $_POST["password"];
        $newP = $_POST["confirm"];
        $isValid = true;
        // to check whether the password match in the server side
        if($password != $newP)
        {
            flash("Password does not match");
        }else{
            flash("Password match");
        }

        if($isValid)
        {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $db = getDB();
            $stmt = $db -> prepare("UPDATE Users set password = :p WHERE email = :e");
            $r = $stmt -> execute([":p" => $hash, ":e" => $email ]);
            $e = $stmt->errorInfo();
            if ($e[0] == "00000") {
                flash( "<br>Welcome! You successfully updated the password, please login.");
            }
            else {
                    flash( "uh oh something went wrong: " . var_export($e, true));
            }
        }// is valid
    }// the if statement
    else{
        flash("Password or Confirm password needed to be filled");
    }
}

?>
<?php require(__DIR__ . "/partials/flash.php");