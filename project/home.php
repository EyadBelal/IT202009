<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
    <p>Welcome, <?php echo $email; ?> to Logan's Computer Hardware</p>

<img src="static/css/hardware.jpg" width="500" height="250" title="Logo of a my site" alt="Logo of my website" />


<?php require(__DIR__ . "/partials/flash.php");