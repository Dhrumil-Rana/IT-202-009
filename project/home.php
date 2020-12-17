<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
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

<?php require(__DIR__ . "/partials/flash.php");
