<?php
//fetching
$result = [];
if(isset($id)){
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM products where id = :id");
    $r = $stmt->execute([":id"=>$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC)
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

    <label> Modified </label>
    <input type="time" mod="current_time_stamp" name="modified"/>

    <label> Created </label>
    <input type="time" crea="current_time_stamp" name="created"/>

    <input type="submit" name="save" value="create"/>
</form>
<?php require(__DIR__ . "/partials/flash.php");

