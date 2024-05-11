<?php
if(isset($_GET["p"])) {
	header("Content-Type: text/plain");
	die("UPTODATE\nhttp://post.audioscrobbler.com/protocol_1.0.php\nINTERVAL 1");
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php");

?>

<div class="content" style="border: 1px dotted gray">
	<h3>Welcome to the Audioscrobbler Community</h3>
	<p>Audioscrobbler is a central resource for tracking your listening habits and learning about new music. It acts as a plugin to your music player to build your personal musical profile. To learn more click <a href="/about.php">here</a>.</p>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); ?>