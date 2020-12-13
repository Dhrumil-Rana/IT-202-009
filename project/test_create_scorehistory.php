<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if(!has_role("Admin")){

    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
    <h2>Create Score table</h2>
    <form method = "POST">
        <label>reason</label>
        <input type = "text" name = "reason" />
        <label>score</label>
        <input type = "number" min = "0" name = "score"/>
        <input type = "submit" name = "save" value = "Create"/>
    </form>

<?php
if (isset ($_POST["save"])){
    $score = $_POST["score"];
    $user = get_user_id();
    $reason = $_POST["reason"];
    $db = getDB();
    $stmt = $db -> prepare("INSERT INTO PointsHistory(user_id, points_change, reason)VALUES(:users,:score,:reason)");
    $r = $stmt -> execute([
       ":users" => $user,
       ":score" => $score,
       ":reason" => "$reason"
    ]);

    if($r)
    {
        flash("Created successfully with id: " .$db->lastInsertId());
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
	// new update on the users
    // this is to get the sum of the score
    $db = getDB();
    $stmt = $db -> prepare("SELECT user_id, SUM(points_change) as sumscore from PointsHistory WHERE user_id = :q");
    $r = $stmt -> execute([":q" => "$user"]);
    if($r)
    {
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
	$_SESSION["user"]["Score"] = $result["sumscore"];

    }
    else{
        flash("There was a problem fetching the results");
    }

    // to update the score in the Users table
    $db = getDB();
    $stmt = $db -> prepare("UPDATE Users set Score = :s where id = :id");
    $r = $stmt -> execute([
        ":s" => $result["sumscore"],
        ":id" => $user
    ]);
    if($r)
    {
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    }
    else{
        flash("There was a problem fetching the results");
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
