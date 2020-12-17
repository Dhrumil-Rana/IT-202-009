
<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$id = get_user_id();
$page = 1;
$per_page = 10;
// fetching
$result = [];
$total_pages = 0;
if(isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT count(*) as total FROM Scores JOIN Users on Scores.user_id = Users.id where Scores.user_id = :id");
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = 0;
    $total = (int)$result["total"];
    $total_pages = ceil($total/$per_page);
    $offset = ($page-1) * per_page;
    $stmt = $db-> prepare("SELECT s.score u.username FROM Scores s LEFT JOIN Users u on s.user_id = u.id where s.user_id = :id LIMIT :offset, :count");
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $e = $stmt->errorInfo();
    if($e[0] != "00000"){
        flash(var_export($e, true), "alert");
    }
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<h2>My Previous Scores</h2>
<?php if ($results && count($results) > 0): ?>
    <?php foreach ($results as $r): ?>

            <div>Score: <?php safer_echo($r["score"]); ?> </div>
            <div>Owner: <?php safer_echo($r["username"]); ?></div>
            <?php endforeach; ?>
<?php else: ?>
    <p>No results</p>
<?php endif; ?>
<nav>
    <ul>
        <li>
            <a class="page-link" href="?page=<?php echo $page-1;?>" tabindex="-1">Previous</a>
        </li>
        <?php for($i = 0; $i < $total_pages; $i++):?>
        <li>
            <a class="page-link" href="?page=<?php echo ($i+1);?>"><?php echo ($i+1);?></a>
        </li>
        <?php endfor; ?>
        <li>
            <a class="page-link" href="?page=<?php echo $page+1;?>">Next</a>
        </li>
    </ul>
</nav>
<?php require(__DIR__ . "/partials/flash.php");

