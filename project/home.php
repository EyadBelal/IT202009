<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
 <h2>   <p>Welcome, <?php echo $email; ?> to Logan's Computer Hardware</p></h2>

<img src="static/css/hardware.jpg" width="500" height="300" title="Logo of a my site" alt="Logo of my website" />


<?php require(__DIR__ . "/partials/flash.php");