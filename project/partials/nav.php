
<link rel = "stylesheet" href = "static/css/style.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<ul>
    <li><a href="home.php">Home</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
    <?php if(HAS_ROLE("Admin")): ?>
        <li><a href = "test_create_score.php"> Create Score</a></li>
        <li><a href = "test_list_score.php"> List Score</a></li>
	<li><a href = "test_create_scorehistory.php"> Create Score History </a></li>
	<li><a href = "test_list_scorehistory.php"> List score History </a> </li>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
	<li><a href = "arcadeShooter.html">Play the Game</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    <?php endif; ?>
</ul>
