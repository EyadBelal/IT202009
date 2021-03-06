<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $r) {
            if ($r["name"] == $role) {
                return true;
            }
        }
    }
    return false;
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}
function getQuantityPrice($quantity,$id){

    $db = getDB();
    $stmt = $db->prepare("SELECT price FROM Products WHERE id=:id");
    $r = $stmt->execute([
    ":id" => $id,
    ]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
    
    $pr = $result["price"];
    
    $total = $pr*$quantity;
    return $total;

}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

//for flash feature
function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flashes = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flashes;
    }
    return array();
}
function totalPrice($id, $quantity)
{
$db = getDB();
$stmt = $db->prepare("SELECT price FROM Products where id = :id");
$r = $stmt->execute([":id" => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
$price = $product["price"] * $quantity;
return $price;
}

function getURL($path) {
    if (substr($path, 0, 1) == "/") {
        return $path;
    }
    return $_SERVER["CONTEXT_PREFIX"] . "/repo/project/$path";
}

function deleteRow($id)
{	
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Carts WHERE product_id=$id");
    $r = $stmt->execute();
    if($r)
      return true;
    else
      return false;
}
function get_account_type() {
    if (is_logged_in() && isset($_SESSION["user"]["account_type"])) {
        return $_SESSION["user"]["account_type"];
    }
    return "";
}
function clearCart($id)
{	
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Carts WHERE user_id=$id");
    $r = $stmt->execute();
    if($r)
      return true;
    else
      return false;
}

//end flash
?>
