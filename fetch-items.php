<?php
// items tablomuzdaki item_name sütunua göre filtreleme yapıyoruz
require_once 'db.php';
try{
    if(isset($_REQUEST["term"])){
        // create prepared statement
        $sql = "SELECT * FROM items WHERE item_name LIKE :term";
        $stmt = $db->prepare($sql);
        $term = $_REQUEST["term"] . '%';
        // bind parameters to statement
        $stmt->bindParam(":term", $term);
        // execute the prepared statement
        $stmt->execute();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch()){
                echo '<div class="element" style="margin-top:2px;">' . $row["item_name"] . '</div>';
            }
        } else{
            echo '<div class="element">Eşleşme bulunamadı</div>';
        }
    }  
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

// Close statement
unset($stmt);
 
// Close connection
unset($db);
?>
