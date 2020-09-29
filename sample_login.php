<p>Run me in the browser from your server to try</p>
<form method="POST">
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required/>
  <label for="p1">Password:</label>
  <input type="password" id="p1" name="password" required/>
  <input type="submit" name="login" value="Login"/>
</form>

<?php
if(isset($_POST["login"])){
  $email = null;
  $password = null;
  if(isset($_POST["email"])){
    $email = $_POST["email"];
  }
  if(isset($_POST["password"])){
    $password = $_POST["password"];
  }
  $isValid = true;
  if(!isset($email) || !isset($password)){
   $isValid = false; 
  }
  //TODO other validation as desired, remember this is the last line of defense
  //here you'd probably want some email validation, for sake of example let's do a super basic one
  if(!strpos($email, "@")){
   $isValid = false;
    echo "<br>Invalid email<br>";
  }
  if($isValid){
    //for password matching, we can't use this, every time it's ran it'll be a different value
    //so will never log us in!
    //$hash = password_hash($password, PASSWORD_BCRYPT);
    //instead we'll want to run password_verify
    //TODO pretend we got our use from the DB
    //make sure if you're pasting a sample hash here that you use single quotes
    //if you use double quotes it'll try to parse values with $ as a php variable
    //and the sample won't work
    require_once("db.php");
    $db = getDB();
	if(isset($db)){
		//here we'll use placeholders to let PDO map and sanitize our data
		$stmt = $db->prepare("SELECT email, password from Users WHERE email = :email LIMIT 1");
		//here's the data map for the parameter to data
		$params = array(":email"=>$email);
		$r = $stmt->execute($params);
		//let's just see what's returned
		echo "db returned: " . var_export($r, true);
		$e = $stmt->errorInfo();
		if($e[0] != "00000"){
			echo "uh oh something went wrong: " . var_export($e, true);
		}
		//since it's a select command we must fetch the results
		//we'll tell pdo to give it to us as an associative array
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if($result && isset($result["password"])){
			$password_hash_from_db = $result["password"];
			if(password_verify($password, $password_hash_from_db)){
			 echo "<br>Welcome! You're logged in!<br>"; 
			}
			else{
			 echo "<br>Invalid password, get out!<br>"; 
			}
		}
		else{
			echo "<br>Invalid user<br>";
		}
	}
  }
  else{
   echo "There was a validation issue"; 
  }
}
?>
