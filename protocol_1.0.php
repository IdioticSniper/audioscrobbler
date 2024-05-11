<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/config.php");
header("Content-Type: text/plain");

function err($msg) {
	$err = "FAILED " . $msg;
	return $err;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$response2 = "BADPASS"; // Wrong password
	$response3 = "OK"; // Successfully added song
	
	if($_POST["u"] || $_POST["p"] !== NULL) {
		//TODO: make this better
		$stmt = $conn->prepare("SELECT * FROM as_users WHERE username=:t0");
		$stmt->bindParam(":t0", $_POST["u"]);
		$stmt->execute();
		foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $user);
		if($stmt->rowCount() == 0) { die(err("User does not exist")); }
		if($_POST["p"] !== $user->password_md5) { die($response2); }
	}
	
	if($_POST["s"] !== NULL) {
		$songs = array($_POST["s"]);
		$count = -1;
		foreach($songs as $song) {
			$count++;
			if($_POST["a"][$count] == "") { $_POST["a"][$count] = "Unknown Artist"; } //as did this
			$_POST["a"][$count] = html_entity_decode($_POST["a"][$count]);
			$_POST["s"][$count] = html_entity_decode($_POST["s"][$count]);
			
			// check if artist is already in database
			$stmt = $conn->prepare("SELECT * FROM as_artists WHERE name=:t0");
			$stmt->bindParam(":t0", $_POST["a"][$count]);
			$stmt->execute();
			if($stmt->rowCount() == 0) {
				$stmt = $conn->prepare("INSERT INTO as_artists (name) VALUES (:t0)");
				$stmt->bindParam(":t0", $_POST["a"][$count]);
				$stmt->execute();
			}
			
			// check if song is already in database
			$stmt = $conn->prepare("SELECT * FROM as_songs WHERE song_artist=:t0 AND song_title=:t1");
			$stmt->bindParam(":t0", $_POST["a"][$count]);
			$stmt->bindParam(":t1", $_POST["s"][$count]);
			$stmt->execute();
			if($stmt->rowCount() == 0) {
				$stmt = $conn->prepare("INSERT INTO as_songs (song_artist, song_title, song_length) VALUES (:t0, :t1, :t2)");
				$stmt->bindParam(":t0", $_POST["a"][$count]);
				$stmt->bindParam(":t1", $_POST["s"][$count]);
				$stmt->bindParam(":t2", $_POST["l"][$count]);
				$stmt->execute();
			}
			
			$stmt = $conn->prepare("INSERT INTO as_scrobbles (song_artist, song_title, song_length, played_by, played_at) VALUES (:t0, :t1, :t2, :t3, :t4)");
			$stmt->bindParam(":t0", $_POST["a"][$count]);
			$stmt->bindParam(":t1", $_POST["s"][$count]);
			$stmt->bindParam(":t2", $_POST["l"][$count]);
			$stmt->bindParam(":t3", $_POST["u"]);
			$stmt->bindParam(":t4", $_POST["d"][$count]);
			$stmt->execute();
			die($response3);
		}
	}
}
?>