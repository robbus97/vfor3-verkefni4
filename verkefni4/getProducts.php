<?php
if(isset($_POST['price_range'])){
    
    //Include database configuration file
    include('dbConnect.php');
    
    //set conditions for filter by price range
    $whereSQL = "";
    $orderSQL = "";
    $priceRange = $_POST['price_range'];

    
    
    if(!empty($priceRange)){
        $priceRangeArr = explode(',', $priceRange);
        $orderSQL = " ORDER BY price ASC ";
    }else{
        $orderSQL = " ORDER BY created DESC ";
    }
    
    //get product rows
    
    try
		{
			$sql = 'SELECT * FROM products WHERE price BETWEEN :range1 AND :range2 ORDER BY :order';
      $s = $pdo->prepare($sql);
      $s->bindValue(':range1', $priceRangeArr[0]);
      $s->bindValue(':range2', $priceRangeArr[1]);
      $s->bindValue(':order', $orderSQL);

      $s->execute();
		}
		catch (PDOException $e)
		{
			$error = 'Error fetching price details.' . $e->getMessage();
      include "error.php"; 
			exit();
		}
    
    if($s->rowCount() > 0){
      $result = $s->fetchALL();
      foreach ($result as $row)
      {
        ?>
          <div class="list-item">
              <h2><?php echo $row["name"]; ?></h2>
              <h4>Price: <?php echo $row["price"]; ?></h4>
          </div>
          <?php
      }
    }else{
        echo 'Product(s) not found';
    }
}
?>