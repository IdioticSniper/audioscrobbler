<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");

if(isset($_GET["n"])) {
	$stmt = $conn->prepare("SELECT * from as_users where username=:t0");
	$stmt->bindParam(":t0", $_GET["n"]);
	$stmt->execute();
	if($stmt->rowCount() == 0) { header("Location: /"); }
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $user);
} else {
	header("Location: /");
}

$stmt = $conn->prepare("SELECT song_length FROM as_scrobbles WHERE played_by=:t0");
$stmt->bindParam(":t0", $user->username);
$stmt->execute();

if($stmt->rowCount() > 0) {
	$things = [];
	$sum = 0;

	foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $thing) {
		$things[] = $thing;
	}

	$out1 = round(array_multisum($things) / count($things));
	$output = sprintf('%02d:%02d', ($out1/ 60 % 60), $out1% 60);

	$stmt = $conn->prepare("SELECT * from as_scrobbles WHERE played_by=:t0");
	$stmt->bindParam(":t0", $_GET["n"]);
	$stmt->execute();
	$songs_played = $stmt->rowCount();
} else {
	$output = "00:00";
	$songs_played = 0;
}

//parse regdate
$now = new DateTime($user->registered_on);
$regdate = $now->format('F jS, Y');
?>

<div class="content">
	<h3>User Statistics: <?php echo $user->username; ?></h3>
	<table>
		<tr><td>Average Song Length:</td><td><?php echo $output; ?></td></tr>
		<tr><td>Total Songs Played:</td><td><?php echo $songs_played; ?></td></tr>
		<tr><td>Registered On:</td><td><?php echo $regdate; ?></td></tr>
	</table>
</div>

<?php
$stmt = $conn->prepare("SELECT * FROM as_scrobbles WHERE played_by=:t0 ORDER BY id DESC LIMIT 10");
$stmt->bindParam(":t0", $user->username);
$stmt->execute();

if($stmt->rowCount() > 0) {
	echo '<div class="content">
	<h3>Last 10 Tracks</h3>
	<table class="topn">
	';

	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $last) {
		echo '<tr><td>' . $last->played_at . '</td><td><a href="' . str_replace(" ", "+", htmlentities($last->song_artist)) . '">' . htmlspecialchars($last->song_artist) . '</a> - ' . htmlspecialchars($last->song_title) . '</td></tr>';
	}
	
	echo '	</table>
</div>';

	echo '<div class="content">
	<h3>Top Artists</h3>
	<table class="topn">
		<tbody>';

	//get total songs count
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title=as_scrobbles.song_title WHERE as_scrobbles.played_by=:t0 GROUP BY as_scrobbles.song_artist ORDER BY COUNT(as_scrobbles.id) DESC");
	$stmt->bindParam(":t0", $user->username);
	$stmt->execute();
	$songs = $stmt->rowCount();
	//limit the songs count to 25
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title=as_scrobbles.song_title WHERE as_scrobbles.played_by=:t0 GROUP BY as_scrobbles.song_artist ORDER BY COUNT(as_scrobbles.id) DESC LIMIT 20");
	$stmt->bindParam(":t0", $user->username);
	$stmt->execute();
	//calc percentage
	$percentage = cal_percentage($songs, 100);
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $song) {
		echo '		<tr>
			<td><a href="/artist.php?n=' . str_replace(" ", "+", htmlentities($song->song_artist)) . '">' . htmlspecialchars($song->song_artist) . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
	}
	
	echo '		</tbody>
	</table>
</div>

<div class="content">
	<h3>Top Songs</h3>
	<table class="topn">
	';

	//get total songs count
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title = as_scrobbles.song_title WHERE played_by=:t0 GROUP BY as_scrobbles.song_title ORDER BY COUNT(as_scrobbles.id) DESC");
	$stmt->bindParam(":t0", $user->username);
	$stmt->execute();
	$songs = $stmt->rowCount();
	//limit the songs count to 25
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title = as_scrobbles.song_title WHERE played_by=:t0 GROUP BY as_scrobbles.song_title ORDER BY COUNT(as_scrobbles.id) DESC LIMIT 25");
	$stmt->bindParam(":t0", $user->username);
	$stmt->execute();
	//calc percentage
	$percentage = cal_percentage($songs, 100);
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $song) {
		echo '		<tr>
			<td><a href="/artist.php?n=' . str_replace(" ", "+", htmlentities($song->song_artist)) . '">' . htmlspecialchars($song->song_artist) . ' - ' . htmlspecialchars($song->song_title) . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
	}

	echo '	</table>
</div>';

}
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); 
?>