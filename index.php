<?php
   
   $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
   $qry = new MongoDB\Driver\Query([]);
   $bulk = new MongoDB\Driver\BulkWrite;

  
   

    if(isset($_POST['sub'])){
        $item = $_POST['item'];
        $qty = $_POST['qty'];

        $rr = array(
            '_id' => new MongoDB\BSON\ObjectID,
            'item'=> $item, 
            'qty'=> $qty          
        );        
        $_id1 = $bulk->insert($rr);
        $mng->executeBulkWrite('productdb.product', $bulk);
      
        echo "<script>window.location.href='http://localhost/mongodb/';</script>";
    }


    if(isset($_POST['upd'])){
        $_id = $_POST['upd'];
        $filter = [ '_id' =>   new MongoDB\BSON\ObjectID($_id) ]; 
        $qry2 = new MongoDB\Driver\Query($filter);
        $rows2 = $mng->executeQuery("productdb.product", $qry2); 
        $car = current($rows2->toArray());
    
        if (!empty($car)) {        
            $_id =  $car->_id;
            $item =  $car->item;
            $qty = $car->qty;
        } 
    } else {
        $_id = '';
        $item = '';
        $qty = '';
    }

    if(isset($_POST['update'])){
        $_id = $_POST['update'];
        $item = $_POST['item'];
        $qty = $_POST['qty'];

        $rr = array(
            'item'=> $item, 
            'qty'=> $qty          
        );      
        $bulk->update(['_id' => new MongoDB\BSON\ObjectID($_id)], ['$set' => $rr]);
        $mng->executeBulkWrite('productdb.product', $bulk);

        echo "<script>window.location.href='http://localhost/mongodb/';</script>";
    }


    if(isset($_POST['del'])){
        $id = $_POST['del'];
        $bulk->delete(['_id' => new MongoDB\BSON\ObjectID($id)]);
        $mng->executeBulkWrite('productdb.product', $bulk);

        echo "<script>window.location.href='http://localhost/mongodb/';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mongo DB</title>
    <style>
         tr td{
            border:1px solid;
            padding:5px;
        }
        .head{
            text-align:center;
            font-size:25px;
            font-weight:bold;
        }
    </style>
</head>
<body>
        <div class="head">MongoDB</div>
    <form action="#" method="post">
        <div class="container">
            <input type="text" name="item" id="item" value="<?php echo $item ?>">
            <input type="text" name="qty" id="qty" value="<?php echo $qty ?>">
            <?php
                if(empty($_id)){
            ?>
                <button type="submit" name="sub">Submit</button>
            <?php
                } else {
                ?>
                <button type="submit" value="<?php echo $_id ?>" name="update">Update</button>
                <?php
                }
            ?>
        </div>
    </form>
    <hr>
    <h1>Show Data</h1>
    <table>
        <thead>
            <th>Id</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <tbody>
        <?php
            $i = 1;
            $rows = $mng->executeQuery("productdb.product", $qry); 
            foreach ($rows as $row) { 
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row->item; ?></td>
                    <td><?php echo $row->qty; ?></td>
                    <td>
                        <form method="post">
                            <button type="submit" value="<?php echo $row->_id ?>" name="upd">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post">
                            <button type="submit" value="<?php echo $row->_id ?>" name="del">Delete</button> 
                        </form>
                    </td> 
                </tr>
                <?php
                $i++;
            } ?>         
        </tbody>
    </table>
</body>
</html>