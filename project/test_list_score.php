<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if(!has_role("Admin"))
{
    // check for the admin login or kill the process
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));

}
?>


<?php
$query = "";
$result = [];
if(isset($_POST["query"])){
    $query = $_POST["query"];
	flash("Get into the query");
}
if(isset($_POST["search"])&& !empty($query)){
    $db = getDB();
    $stmt = $db -> prepare("SELECT id,score from Scores WHERE id like :q LIMIT 10");
    $r = $stmt -> execute([":q" => "%$query%"]);
    if($r)
    {
        $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    }
    else{
        flash("There was a problem fetching the results");
    }
}
?>



<form method ="POST">

    <input name = "query" placeholder = "Search" value = "<?php safer_echo($query); ?>"/>
    <input type = "submit" value = "Search" name = "search"/>
</form>

<div class = "result">
    <?php if (count($result)> 0): ?>
    <div class = "list-group">
        <?php foreach ($result as $r): ?>
        <div class = "list-group-item">
            <div>
                 <div> ID:</div>
                <div> <?php safer_echo($r["id"]); ?></div>
            </div>
            <div>
                <div>Score:</div>
                <div> <?php safer_echo($r["score"]); ?></div>
            </div>
            <div>
                 <a type = "button" href="test_edit_score.php?id = <?php safer_echo($r["id"]); ?>">Edit</a>
                <a type = "button" href = "test_view_score.php?id = <?php safer_echo($r["id"]); ?>">View </a>
             </div>
        </div> 
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No results</p>
    <?php endif; ?>

</div>

