<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");
$artist_name_nor = str_replace("+", " ", $_GET["n"]);
$artist_name_url = str_replace(" ", "+", $_GET["n"]);
?>

<h2><?php echo htmlspecialchars($artist_name_nor); ?></h2>

<div class="content">
	<h3>Top Songs</h3>
	<table class="topn">
	<?php
	$stmt = $conn->prepare("SELECT * FROM as_scrobbles WHERE song_artist=:t0 GROUP BY song_title ORDER BY COUNT(id) DESC");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->execute();
	$songs = $stmt->rowCount();

	$stmt = $conn->prepare("SELECT * FROM as_scrobbles WHERE song_artist=:t0 GROUP BY song_title ORDER BY COUNT(id) DESC LIMIT 50");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->execute();
	
	//calc percentage
	$percentage = cal_percentage($songs, 100);
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $song) {
		$song_name_url = str_replace(" ", "+", $song->song_title);
		echo '		<tr>
			<td><a href="/song.php?a=' . $artist_name_url . '&s=' . $song_name_url .'">' . htmlspecialchars($song->song_title) . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
		}
		?>
	</table>
</div>

<div class="content">
	<h3>Top Fans</h3>
	<table class="topn">
	<?php
	$stmt = $conn->prepare("SELECT COUNT(id), played_by FROM as_scrobbles WHERE song_artist=:t0 GROUP BY played_by");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->execute();

	$stmt = $conn->prepare("SELECT COUNT(id), played_by FROM as_scrobbles WHERE song_artist=:t0 GROUP BY played_by LIMIT 50");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->execute();
	
	//calc percentage
	$percentage = cal_percentage($stmt->rowCount(), 100);
	foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $fan) {
		echo '		<tr>
			<td><a href="/user.php?n=' . $fan->played_by . '">' . $fan->played_by . '</a></td>
			<td class="graph"><div class="bar" style="width:' . $percentage . '%">&nbsp;</div></td>
		</tr>';
		}
		?>
	</table>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); ?>