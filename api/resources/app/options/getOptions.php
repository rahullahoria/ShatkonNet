<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/7/16
 * Time: 1:54 PM
 *
 * There can be different states based on user type, Ignoring JIT
 */


function getOptions($appId, $state){

    $sql = "SELECT a.field, b.id, b.state_name FROM options as a join states as b WHERE 
                    a.parent_state_id = :parent_state_id and b.id = a.child_state_id and b.app_id = :app_id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("app_id", $appId);
        $stmt->bindParam("parent_state_id", $state);

        $stmt->execute();
        $feedbacks = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;

        echo '{"states": ' . json_encode($feedbacks) . '}';



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}