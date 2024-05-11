<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");
$artist_name_nor = str_replace("+", " ", $_GET["a"]);
$artist_name_url = str_replace(" ", "+", $_GET["a"]);
$stitle_name_nor = str_replace("+", " ", $_GET["s"]);
$stitle_name_url = str_replace(" ", "+", $_GET["s"]);
?>

<h2><a href="/artist.php?n=<?php echo htmlspecialchars($artist_name_url); ?>"><?php echo htmlspecialchars($artist_name_nor); ?></a> - <?php echo htmlspecialchars($stitle_name_nor); ?></h2>

<div class="content">
	<h3>Top Fans</h3>
	<table class="topn">
	<?php
	$stmt = $conn->prepare("SELECT COUNT(id), played_by FROM as_scrobbles WHERE song_artist=:t0 AND song_title=:t1 GROUP BY played_by");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->bindParam(":t1", $stitle_name_nor);
	$stmt->execute();

	$stmt = $conn->prepare("SELECT COUNT(id), played_by FROM as_scrobbles WHERE song_artist=:t0 AND song_title=:t1 GROUP BY played_by LIMIT 50");
	$stmt->bindParam(":t0", $artist_name_nor);
	$stmt->bindParam(":t1", $stitle_name_nor);
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