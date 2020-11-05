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
if(isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
    $name = $_POST["name"];
    $amount = $_POST["quantity"];
    $cost= $_POST["price"];
    $desc = $_POST["description"];
    $nst = date('Y-m-d H:i:s');//calc
    $user = get_user_id();
    $db = getDB();
    if(isset($id)){
        $stmt = $db->prepare("UPDATE Products set name=:name, amount=:quantity, cost=:price, desc=description");
        $quantity = $amount;
        $price = $cost;
        $description = $desc;
        $r = $stmt->execute([
            ":name"=>$name,
            ":amount"=>$quantity,
            ":cost"=>$price,
            ":desc"=>$description,
            ":user"=>$user,
        ]);
        if($r){
            flash("Updated successfully with id: " . $id);
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error updating: " . var_export($e, true));
        }
    }
    else{
        flash("ID isn't set, we need an ID in order to update");
    }
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Products where id = :id");
    $r = $stmt->execute([":id"=>$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
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

<?php require(__DIR__ . "/partials/flash.php");

