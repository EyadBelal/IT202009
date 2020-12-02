<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
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
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    //$stmt = $db->prepare("SELECT Cart.id,Cart.product_id,Cart.quantity,Cart.price, Users.username, Product.name FROM Cart  JOIN Users on Cart.user_id = Users.id LEFT JOIN Products  on Product.id = Cart.product_id WHERE Cart.id = :id");
    //$stmt = $db->prepare("SELECT Cart.*,Products.name, Users.username from Cart JOIN Users on Users.id = Cart.user_id JOIN Products on Products.id = Cart.product_id WHERE Cart.id like :q LIMIT 10");
    $stmt = $db->prepare("SELECT Cart.price, Products.name,Products.description, Cart.quantity from Cart join Products on Cart.product_id = product_id where Cart.id = :id");
 
   $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
    <h2>Cart View</h2>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <div>Username: <?php safer_echo($result["username"]); ?></div>
        </div>
        <div class="card-body">
            <div>
                <p>Cart's details</p>
                <div>Cart Id: <?php safer_echo($result["id"]); ?></div>
                <div>Price: <?php safer_echo($result["price"]); ?></div>
                <div>Products: <?php safer_echo($result["product"]); ?></div>
                <div>Quantity: <?php safer_echo($result["quantity"]); ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
