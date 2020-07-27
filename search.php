<?php
    //search by PDO
    include('config.php');
    if(isset($_GET['search'])) {
        $name = htmlspecialchars($_GET['search']);
        $sql = $connect->prepare("SELECT * FROM tbl_product WHERE name LIKE '%$name%'");

        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
    $output = '<div class="row">';
    foreach($result as $value) {
        $output .= '		
        <div class="col-md-3" style="margin-top:12px;">
            <div style="border:1px solid #333; background-color:#f1f1f1; border-radius:5px; padding:16px; height:350px;" align="center">
                <img src="images/'.$value["image"].'" class="img-responsive" /><br />
                <h4 class="text-info">'.$value["name"].'</h4>
                <h4 class="text-danger">$ '.$value["price"] .'</h4>
                <input type="text" name="quantity" id="quantity' . $value["id"] .'" class="form-control" value="1" />
                <input type="hidden" name="hidden_name" id="name'.$value["id"].'" value="'.$value["name"].'" />
                <input type="hidden" name="hidden_price" id="price'.$value["id"].'" value="'.$value["price"].'" />
                <input type="button" name="add_to_cart" id="'.$value["id"].'" style="margin-top:5px;" class="btn btn-success form-control add_to_cart" value="Add to Cart" />
            </div>
        </div>';
    }
    $output .= '</div>';
    echo $output;
?>