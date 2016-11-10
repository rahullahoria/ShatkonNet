<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/7/16
 * Time: 1:54 PM
 *
 * There can be different states based on user type, Ignoring JIT
 */


function getAppStates($appId){

    $sql = "SELECT * FROM states WHERE app_id=:app_id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("app_id", $appId);

        $stmt->execute();
        $states = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;

        echo '{"states": ' . json_encode($states) . '}';



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}