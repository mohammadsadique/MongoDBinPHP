<?php
// connection in db
    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $bulk = new MongoDB\Driver\BulkWrite;
        //insert
        $document = array(
            '_id' => new MongoDB\BSON\ObjectID,
            'item'=> $item, 
            'qty'=> $qty          
        );            
        $bulk->insert($document);
        $mng->executeBulkWrite('database_name.table_name', $bulk);

        //update
        //$bulk->update(where_clause, ['$set' => $rr]);
        $bulk->update(['item' => new MongoDB\BSON\ObjectID($item)], ['$set' => $rr]);
        $mng->executeBulkWrite('productdb.product', $bulk);

        //delete
        //$bulk->delete(where_clause);
        $bulk->delete(['item' => new MongoDB\BSON\ObjectID($id)]);
        $mng->executeBulkWrite('productdb.product', $bulk);

        //fetchdata
        $qry = new MongoDB\Driver\Query([]);
        $rows = $mng->executeQuery("productdb.product", $qry); 
        foreach ($rows as $row) { 
            echo $row->item;
        }
            //show specific data\\
            $filter = [ 'item' =>  new MongoDB\BSON\ObjectID($item) ]; 
            $qry2 = new MongoDB\Driver\Query($filter);
            $rows2 = $mng->executeQuery("productdb.product", $qry2); 
            $car = current($rows2->toArray());
            $car->item;

?>