<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>

<?php
echo "The Item you have chosen is:";
?>
<?php
echo "The product you have chosen is :";
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Products.id,name,quantity,price,description, user_id, Users.username FROM Products JOIN Users on Products.user_id = Users.id where Products.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["name"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>This product's Information: </p>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Price: <?php safer_echo($result["price"]); ?></div>
                <div>Stock: <?php safer_echo($result["quantity"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<form method="POST">
              <div class="card">
                <div class="card-body">
                  <h3 class="card-title">Please let us know your experience.Review our Product!</h3>
                  <label for="exampleFormControlInput1" class="form-label"></label>
                  <input type="text" name="review" class="form-control" id="exampleFormControlInput1" placeholder="amazing product!" required>
                  <select class="form-control" id="quantity" name="stars" style= "width: 50;">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>
                  <button type="submit" name = "reviewButton" class="btn btn-primary btn-lg">submit review</button>
                <div>
              </div>
            </form>


<?php require(__DIR__ . "/partials/flash.php");

