<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/config.php"); ?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta name="resource-type" content="document"/>
<title>Audioscrobbler</title>
<link rel="stylesheet" type="text/css" href="/assets/img/main.css" title="Default stylesheet" />
</head>

<body>

<div id="topbar">
	<a href="/"><img alt="Audioscrobbler" src="/assets/img/logo.gif"/></a>
	<?php
	if(!isset($_SESSION["username"])) {
		echo '
	<div id="login">
		<form action="/login.php" method="post">
			<fieldset>
				<label for="tlogin">User:</label> <input class="formtext" id="tlogin" name="login" /> 
				<label for="tpassword">Pass:</label> <input class="formtext" id="tpassword" name="password" type="password" />
				<input class="form" type="submit" value="log in" name="submit"/>
			</fieldset>
		</form>
	</div>
	';
	}
	?>
</div>

<div id="sidebar">
	<div class="sidebox" id="links">
		<a href="/about.php">About</a><br />
		<a href="/download.php">Download Plugin</a><br/>
	</div>
	<div class="sidebox" id="stats">
		<a href="/topn.php">Overall Statistics</a><br />
	</div>
	<div class="sidebox" id="user">
		<?php
		if(isset($_SESSION["username"])) {
			echo '~<a href="user.php?n=' . $_SESSION["username"] . '">' . $_SESSION["username"] . '</a><br>
			<a href="logout.php">Log out</a>';
		} else {
			echo 'Ready? <a href="/signup.php"><b>Sign up</b></a>, it\'s fast and doesn\'t require an email address.';
		}
		?>
		
	</div>
	<div class="sidebox" id="search">
		<strong>Search</strong>
		<form method="get" action="/search.php">
			<fieldset>
				<select class="form" name="searchfor">
					<option value="artist">Artist</option>
					<option value="song">Song</option>
					<option value="user">User</option>
				</select><br />
				<input id="textbox2" class="form" name="string" /><br />
				<input class="form" type="submit" value="Go!"/>
			</fieldset>
		</form>
	</div>
	<div class="sidebox" id="irc">
		<strong>Find us on IRC:</strong><br />
		<a href="irc://irc.phasenet.co.uk/audioscrobbler">irc.phasenet.co.uk #audioscrobbler</a>
	</div>
	<div class="sidebox" id="network">
		<strong>Partners:</strong><br />
		<a href="http://last.fm">last.fm</a><br />
		<a href="http://www.musicbrainz.org">MusicBrainz</a><br />
	</div>
</div>

<div id="main">