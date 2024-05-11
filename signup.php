<?php 

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php"); 

if(isset($_SESSION["username"])) { header("Location: /"); }

if(isset($_POST["submit"])) {
	$username = preg_replace("/[^a-zA-Z0-9]/", "", $_POST["username"]);
	$password = $_POST["password1"];
	$c_password = $_POST["password2"];
	$i_password = $_POST["password_md5"];
	// now, check the length.
	if(strlen($username) > 25) { die(header("Location: /signup.php?err=2")); }
	if(strlen($password) > 20) { die(header("Location: /signup.php?err=3")); }
	if(strlen($i_password) > 20) { die(header("Location: /signup.php?err=3")); }
	if(strlen($username) < 2) { die(header("Location: /signup.php?err=4")); }
	if(strlen($i_password) < 1) { die(header("Location: /signup.php?err=8")); }
	// ----------------------
	if($password !== $c_password) { die(header("Location: /signup.php?err=5")); }
	if(empty($password)) { die(header("Location: /signup.php?err=7")); }
	if(empty($i_password)) { die(header("Location: /signup.php?err=8")); }
	// ----------------------
	$password = password_hash($password, PASSWORD_BCRYPT);
	$wordpass = md5($_POST["password_md5"]);
	// ----------------------
	// And now, the actual signup
	
	$result = $conn->query("SELECT username FROM as_users WHERE username='" . $username . "'");
	if($result->rowCount() == 0) {
		$login_ok = true;
	} else die(header("Location: /signup.php?err=9"));
	
	if($login_ok) {
		$stmt = $conn->prepare("INSERT INTO as_users (username, password, password_md5) VALUES (:t1, :t2, :t3)");
		$stmt->bindParam(':t1', $username);
		$stmt->bindParam(':t2', $password);
		$stmt->bindParam(':t3', $i_password);
		$stmt->execute();
		
		//set sessid
		$_SESSION["username"] = $username;
		header("Location: /");
	}
}

if(isset($_GET["err"])) {
	if($_GET["err"] == 2)  { error("Your username cannot be longer than 25 characters"); }
	if($_GET["err"] == 3)  { error("Your password cannot be longer than 20 characters."); }
	if($_GET["err"] == 4)  { error("Your username cannot be shorter than 2 characters."); }
	if($_GET["err"] == 5)  { error("Passwords do not match!"); }
	if($_GET["err"] == 7)  { error("Your password cannot be empty."); }
	if($_GET["err"] == 8)  { error("Your cannot have an empty MD5 password."); }
	if($_GET["err"] == 9)  { error("The username you entered is already in use."); }
}


?>

<div class="content">
	<h3>Sign Up for Audioscrobbler</h3>
	<p>Read the "Things you should know" section below, and fill out the form at the bottom of the page.</p>
	</div>

    
<div class="content">
	<h3>Things you should know</h3>
	<p>
	<ul>
		<li>Your Audioscrobbler user page will be visible to everyone. You cannot keep your listening statistics hidden.</li>
		<li>We will not distribute or sell personal details</li>
		<li>It's not clever or funny to always use "support@gracenote.com" when you signup for websites.</li>
	</ul>

	</p>
</div>


<div class="content">
	<h3>Instant gratification</h3>    
	<p>Although usernames are case <b>in</b>sensitive, they will be displayed on the site as you type it in here, so you might like to use a Capital letter at the start ;)</p>
	<form id="regform" method="post" action="signup.php">
		<table>
			<tr><td width="200" align="right" valign="middle"><label for="username">Desired Username:</label></td>
			<td align="right" valign="middle"><input type="text" maxlength="25" id="username" name="username"/></td><td>(no spaces or funny symbols please)</td></tr>
			<tr><td width="200" align="right" valign="middle"><label for="password1">Password:</label></td>
			<td align="left" valign="middle"><input type="password" maxlength="20" id="password1" name="password1"/></td></tr>
			<tr><td width="200" align="right" valign="middle"><label for="password2">Confirm Password:</label></td>
			<td align="left" valign="middle"><input type="password" maxlength="20" id="password2" name="password2"/></td></tr>

			<tr><td width="200" align="right" valign="middle"><label for="password1">Password (MD5):<br>This is insecure and used for plugin login.</label></td>
			<td align="left" valign="middle"><input type="password" maxlength="20" id="password_md5" name="password_md5"/></td></tr>

			<tr><td>&nbsp;</td><td align="left" valign="middle">
			<input type="submit" id="submit" value="Count me in!" name="submit"/>
			</td></tr>
		</table>
	</form>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); ?>