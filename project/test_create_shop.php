<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
    <form method="POST">
        <label>Name</label>
        <input name="name" placeholder="Name"/>

        <label> Quantity </label>
        <input type="number" amount ="1" name="quantity"/>

        <label> Price </label>
        <input type="number" cost="0.00" name="price"/>

        <label> Description </label>
        <input type="text" desc="" name="description"/>

        <input type="submit" name="save" value="create"/>
    </form>

<?php
if(isset($_POST["save"])){
    //TODO add proper validation/checks
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $price= $_POST["price"];
    $description = $_POST["description"];
    $user = get_user_id();
    $nst = date('Y-m-d H:i:s');//calc
    $db = getDB();


    $stmt = $db->prepare("INSERT INTO Products (name, quantity, price, description, user_id) VALUES(:name, :quantity, :price, :description)");

    $r = $stmt->execute([
        ":name"=>$name,
        ":quantity"=>$quantity,
        ":price"=>$price,
        ":description"=>$description,

    ]);
    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>

<?php require(__DIR__ . "/partials/flash.php");

