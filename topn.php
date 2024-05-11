<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php"); 

//total counts
$stmt = $conn->prepare("SELECT * FROM as_users");
$stmt->execute();
$users = $stmt->rowCount();

$stmt = $conn->prepare("SELECT * FROM as_artists");
$stmt->execute();
$artists = $stmt->rowCount();

$stmt = $conn->prepare("SELECT * FROM as_songs");
$stmt->execute();
$tracks = $stmt->rowCount();
?>

<div class="content">
	<h3>Overall Statistics:</h3>
	<table>
		<tr><td>Users:</td><td><?php echo $users; ?></td></tr>
		<tr><td>Artists:</td><td><?php echo $artists; ?></td></tr>
		<tr><td>Albums:</td><td></td></tr>
		<tr><td>Tracks:</td><td><?php echo $tracks; ?></td></tr>
	</table>
</div>

<div class="content">
	<h3>Top Artists:</h3>
	<table class="topn">
		<?php
		//get total artists count
		$stmt = $conn->prepare("SELECT * FROM as_artists LEFT JOIN as_scrobbles ON as_artists.name = as_scrobbles.song_artist GROUP BY as_scrobbles.song_artist ORDER BY COUNT(as_scrobbles.id) DESC");
		$stmt->execute();
		$artists = $stmt->rowCount();
		//limit the artists count to 25
		$stmt = $conn->prepare("SELECT * FROM as_artists LEFT JOIN as_scrobbles ON as_artists.name = as_scrobbles.song_artist GROUP BY as_scrobbles.song_artist ORDER BY COUNT(as_scrobbles.id) DESC LIMIT 25");
		$stmt->execute();
		//calc percentage
		$percentage = cal_percentage($artists, 100);
		foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $artist) {
			echo '		<tr>
			<td><a href="/artist.php?n=' . str_replace(" ", "+", htmlentities($artist->song_artist)) . '">' . htmlspecialchars($artist->song_artist) . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
		}
		?>
	</table>
</div>

<div class="content">
	<h3>Top Tracks:</h3>
	<table class="topn">
	<?php
	//get total artists count
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title = as_scrobbles.song_title GROUP BY as_scrobbles.song_title ORDER BY COUNT(as_scrobbles.id) DESC");
	$stmt->execute();
	$songs = $stmt->rowCount();
	//limit the artists count to 25
	$stmt = $conn->prepare("SELECT * FROM as_songs LEFT JOIN as_scrobbles ON as_songs.song_title = as_scrobbles.song_title GROUP BY as_scrobbles.song_title ORDER BY COUNT(as_scrobbles.id) DESC LIMIT 25");
	$stmt->execute();
	//calc percentage
	$percentage = cal_percentage($songs, 100);
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $song) {
		echo '		<tr>
			<td><a href="/artist.php?n=' . str_replace(" ", "+", htmlentities($song->song_artist)) . '">' . htmlspecialchars($song->song_artist) . ' - ' . htmlspecialchars($song->song_title) . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
		}
	?>
	</table>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); ?>