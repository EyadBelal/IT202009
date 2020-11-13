<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
//admin only
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();

    //$stmt = $db->prepare("SELECT cart.id,cart.product_id,cart.price,cart.quantity,product.name as product, Users.username from Cart as Cart JOIN Users on cart.user_id = Users.id LEFT JOIN Products as product on cart.product_id = product.id WHERE cart.id like :q LIMIT 10");
     $stmt = $db->prepare("SELECT Cart.id, Cart.product_id, Cart.price, Cart.quantity, product.name as product, Users.username from Cart as Cart JOIN Users on Cart.users_id =  Users.id LEFT JOIN Products as product on cart.product_id = product.id WHERE cart.id like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
<h2>Cart List</h2>
<form method="POST">
    <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
    <input type="submit" value="Search" name="search"/>
</form>
<div class="results">
    <?php if (count($results) > 0): ?>
        <div class="list-group">
            <?php foreach ($results as $r): ?>
                <div class="list-group-item">
                    <div>
                        <div>Username: <?php safer_echo($r["username"]); ?></div>
                    </div>
                    <div>
                        <div> Id: <?php safer_echo($r["id"]); ?></div>
                    </div>
                    <div>
                        <div> price:</div>
                        <div><?php safer_echo($r["price"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_cart.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_cart.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                    <div> <br> </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>
