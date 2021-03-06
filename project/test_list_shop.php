<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
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
    $stmt = $db->prepare("SELECT name, quantity, id, price, user_id,description from Products WHERE name like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
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
                        <div>Name: <?php safer_echo($r["name"]); ?></div>
                    </div>
                    <div>
                        <div>Quantity: <?php safer_echo($r["quantity"]); ?></div>
                    </div>
                    <div>
                        <div>Price: <?php safer_echo($r["price"]); ?></div>
                    </div>

                    <div>
                        <div>Description: <?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="test_edit_shop.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_shop.php?id=<?php safer_echo($r['id']); ?>">View</a>

                        <a type="button" href="test_edit_products.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                        <a type="button" href="test_view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>