<?php
require_once(__DIR__ . "/lib/helpers.php");

if (!is_logged_in()) {
    die(header(':', true, 403));
}
$result = [];
$user = get_user_id();
$score = $_POST["myscore"];
$db = getDB();
$stmt = $db -> prepare("INSERT INTO Scores (score, user_id) VALUES(:score, :user)");
$r = $stmt -> execute([
    ":score" => $score,
    ":user" => $user
]);

if ($r) {
    $response = ["status" => 200, "score" => $score];
    echo json_encode($response);
    //die();
}
else {
    $e = $stmt->errorInfo();
    $response = ["status" => 400, "error" => $e];
    echo json_encode($response);
    //die();
}

// this is to update the users score

$db = getDB();
$stmt = $db -> prepare("SELECT user_id, SUM(score) as sumscore from Scores WHERE user_id = :q");
$r = $stmt -> execute([":q" => $user]);
if($r)
{
    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    $_SESSION["user"]["Score"] = $result["sumscore"];
}
else{
    flash("There was a problem fetching the results");
}
$db = getDB();
$stmt = $db -> prepare("UPDATE Users set Score = :s where id = :id");
$r = $stmt -> execute([
    ":s" => $result["sumscore"],
    ":id" => $user
]);


?>



