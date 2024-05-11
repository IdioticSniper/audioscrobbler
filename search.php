<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");
if(!isset($_GET["searchfor"])) { header("Location: /"); }

if($_GET["searchfor"] == "artist") { $stmt = $conn->prepare("SELECT * FROM as_artists WHERE name REGEXP :t0 ORDER BY name DESC"); }
if($_GET["searchfor"] == "song") { $stmt = $conn->prepare("SELECT * FROM as_songs WHERE song_title REGEXP :t0 ORDER BY song_title DESC"); }
if($_GET["searchfor"] == "user") { $stmt = $conn->prepare("SELECT * FROM as_users WHERE username REGEXP :t0 ORDER BY username DESC"); }
$stmt->bindParam(":t0", $_GET["string"]);
$stmt->execute();

$total = $stmt->rowCount();
//pagination
$limit = 20;
if(!isset($_GET['page'])) { $page = 1; } else { $page = intval($_GET['page']); }
$start = ($page-1)*$limit;
$total_pages = ceil($total/$limit);

echo '<div class="content"><h2>Search Results</h2></div><ul>';

if($_GET["searchfor"] == "artist") { $stmt = $conn->prepare("SELECT * FROM as_artists WHERE name REGEXP :t0 ORDER BY name DESC LIMIT :t1, :t2"); }
if($_GET["searchfor"] == "song") { $stmt = $conn->prepare("SELECT * FROM as_songs WHERE song_title REGEXP :t0 ORDER BY song_title DESC LIMIT :t1, :t2"); }
if($_GET["searchfor"] == "user") { $stmt = $conn->prepare("SELECT * FROM as_users WHERE username REGEXP :t0 ORDER BY username DESC LIMIT :t1, :t2"); }
$stmt->bindParam(":t0", $_GET["string"]);
$stmt->bindParam(":t1", $start, PDO::PARAM_INT);
$stmt->bindParam(":t2", $limit, PDO::PARAM_INT);
$stmt->execute();
	
foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $entry) {
	if($_GET["searchfor"] == "song") { echo '<li><a href="song.php?a=' . str_replace(" ", "+", $entry->song_artist) . '&s=' . str_replace(" ", "+", $entry->song_title) . '">' .  htmlspecialchars($entry->song_artist) . ' - ' . htmlspecialchars($entry->song_title) . '</a></li>'; }
	if($_GET["searchfor"] == "artist") { echo '<li><a href="artist.php?n=' . str_replace(" ", "+", $entry->name) . '">' . htmlspecialchars($entry->name) . '</a></li>'; }
	if($_GET["searchfor"] == "user") { echo '<li><a href="user.php?n=' . $entry->username . '">' . $entry->username . '</a></li>'; }
}

echo '</ul>';

echo '<div class="content">Page: ';
	for($p=1; $p<=$total_pages; $p++) {
		echo '<a href="search.php?searchfor=' . $_GET["searchfor"] . '&string=' . $_GET["string"] . '&page=' . $p . '">' . $p . '</a> ';
	}
echo '</div>';

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php");
?>