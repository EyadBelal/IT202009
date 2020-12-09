<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php

if (!is_logged_in()) {
    //Is not logged in
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$db = getDB();
$stmt = $db->prepare("SELECT id,name from Products LIMIT 10");
$r = $stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h3>Cart</h3>
    <form method="POST">
        <label>Name</label>
        <select name="name">
            <option> <value="-1">Name</option>
            <?php foreach ($products as $product): ?>
                <option> <value="<?php safer_echo($product["name"]); ?>"><?php safer_echo($product["name"]); ?></option>
            <?php endforeach; ?>
        </select></br>
        <label>Product ID</label>
        <select name="product_id">
            <option> <value="-1">None</option>
            <?php foreach ($products as $product): ?>
                <option> <value="$product["id"]"><?php safer_echo($product["name"]); ?>:  <?php safer_echo($product["id"]); ?></option>
            <?php endforeach; ?>
        </select></br>
        <label>Quantity</label>
        <input type="number" min="0" max="10" name="quantity"/></br>
<<<<<<< HEAD
        <input type="submit" name="save" value="ADD TO CART"/>
=======
        <input type="submit" name="save" value="Add to Cart"/>
//only let's users access their cart if logged in
if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

//gets products for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,name,price,visibility from Products WHERE visibility != 0 LIMIT 10");
$r = $stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <h3>Add to Cart</h3>
    <form method="POST">
        <label>Select a Product</label>
        <select name="product_id">
            <option value="-1">None</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php safer_echo($product["id"]); ?>"> <?php safer_echo($product["name"]." $".$product["price"]);?> </option>
            <?php endforeach; ?>
        </select>
        <label>Quantity</label>
        <input name="quantity" type="number"/>
        <input type="submit" name="save" value="Submit"/>
>>>>>>> ec22c42e7e1d52cae39aca13959c0e6d3e8e7a40
    </form>

<?php
if (isset($_POST["save"])) {

    //TODO add proper validation/checks
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $product_id = substr($_POST["product_id"],-2);
    $price = getQuantityPrice($quantity, $product_id);
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Cart (product_id, price, quantity, user_id) VALUES(:product_id, :price, :quantity,:user)");
    $r = $stmt->execute([
        
        ":product_id" => $product_id,
        ":price" => $price,
        ":quantity" => $quantity,
        ":user" => $user
    ]);
    if ($r) {
        flash("Total price for ".$quantity." ".$name." is: $".$price);
    if(isset($_POST["product_id"])){
        $id = $_POST["product_id"];
        $db = getDB();
        $stmt = $db->prepare("SELECT id,price from Products WHERE id=:id");
        $r = $stmt->execute([":id" => $id]);
        $productSelection = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $price = $productSelection["price"];
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Cart (product_id, quantity, price, user_id) VALUES(:id, :quantity, :price, :user)");
    $r = $stmt->execute([
        ":id" => $id,
        ":quantity" => $quantity,
        ":price" => $price,
        ":user" => $user
    ]);
    if ($r) {
        flash("Successfully added to cart.");
    }
    else {
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>

<?php require(__DIR__ . "/partials/flash.php");


<?php
//below will display the cart contents for the user to see
$userID = get_user_id();
$db = getDB();
    $stmt = $db->prepare("SELECT Products.id,name,quantity,price,description,category,user_id, Users.username FROM Products JOIN Users on Products.user_id = Users.id where Products.id = :id");
$r = $stmt->execute([":id" => $userID]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="results">
        <div class="list-group">
            <div>
                <div><b>Cart Contents</b></div>
            </div>
            <div>
                <br>
            </div>
            <?php
            if(empty($results)){safer_echo("buy something your cart is empty.");echo("<br>");}
            $cartTotal = 0;
            foreach ($results as $product):?>
                <div class="list-group-item">
                    <div>
                        <div><u><?php safer_echo($product["product"]); ?></u></div>
                    </div>
                    <div>
                        <div>Quantity: <?php safer_echo($product["quantity"]); ?></div>
                    </div>
                    <div>
                        <div>Price: $<?php safer_echo($product["price"]); ?></div>
                    </div>
                    <div>
                        <div>Subtotal: $<?php safer_echo($product["price"]*$product["quantity"]); $cartTotal+=$product["price"]*$product["quantity"]; ?></div>
                    </div>
                    <div>
                        <a type="button" href="productView.php?id=<?php safer_echo($product["product_id"]); ?>">View</a>
                        <br>
                        <form method="POST">
                            <label>Change Quantity</label>
                            <input name="quantity" type="number"/>
                            <button type="submit" value="<?php safer_echo($product["product_id"]);?>" name="update">Update</button>
                        </form>

                        <?php
                        if(isset($_POST["update"])&&isset($_POST["quantity"])){
                            $productID = $_POST["update"];
                            $newQuantity = $_POST["quantity"];
                            $userID = get_user_id();
                            $db = getDB();

                            //remove if quantity set to 0
                            if($newQuantity==0){
                                $stmt = $db->prepare("DELETE FROM Cart where user_id=:id AND product_id=:pid");
                                $r = $stmt->execute([":pid" => $productID,":id" => $userID]);
                                if($r){
                                    flash("Removed item from cart");
                                }
                            }else{ //updates quantity
                                $stmt = $db->prepare("UPDATE Cart SET quantity=:quantity WHERE user_id=:id AND product_id=:pid");
                                $r = $stmt->execute([":quantity" => $newQuantity, ":id" => $userID, ":pid" => $productID]);
                                if ($r) {
                                    flash("Updated quantity");
                                }
                            }
                        }
                        ?>
                    </div>
                    <div>
                        <form method="POST">
                            <button type="submit" value="<?php safer_echo($product["product_id"]);?>" name="remove">Remove</button>
                        </form>

                        <?php
                        //removes product from cart
                        if(isset($_POST["remove"])){
                            $productID = $_POST["remove"];
                            $userID = get_user_id();
                            $db = getDB();
                            $stmt = $db->prepare("DELETE FROM Cart where user_id=:id AND product_id=:pid");
                            $r = $stmt->execute([":pid" => $productID,":id" => $userID]);
                            if($r){
                                flash("Removed item from cart");
                            }
                        }
                        ?>
                    </div>
                    <div>
                        <br>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <div><b>Total Cart Value: $<?php safer_echo($cartTotal); ?></b></div>
        </div>
        <div>
            <div><br></div>
        </div>
    </div>

    <form method="POST">
        <button type="submit" name="clear">Clear Cart</button>
    </form>

<?php
if(isset($_POST["clear"])){
    $userID = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Cart where user_id=:id");
    $r = $stmt->execute([":id" => $userID]);
    if($r){
        flash("Cart emptied");
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");

