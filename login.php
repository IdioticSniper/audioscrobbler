<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");
if(isset($_SESSION["username"])) { header("Location: /"); }

if($_SERVER["REQUEST_METHOD"] == "GET") {
	echo '<div class="content"><h3>Error</h3><p>Login failed: No user name or password specified.</p></div>';
}

if(isset($_POST["submit"])) {
	if(empty($_POST["login"])) { die(header("Location: /")); }
	if(empty($_POST["password"])) { die(header("Location: /")); }
	
	$username = $_POST["login"];
	
	$stmt = $conn->prepare("SELECT * FROM as_users WHERE username=:t0");
	$stmt->bindParam(":t0", $username);
	$stmt->execute();
	if($stmt->rowCount() > 0) {
		foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $user);
		
		$password_ok = false;

		if(password_verify($_POST["password"], $user->password)) {
			$password_ok = true;
		}
		
		if($password_ok) {
			$_SESSION["username"] = $username;
			header("Location: /");
		} else die('<div class="content"><h3>Error</h3><p>Login failed: Wrong password.</p></div>');
	} else die('<div class="content"><h3>Error</h3><p>The user you requested does not exist.</p></div>');
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php");
?>