<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */


function getFeedbacks($userId, $objectId){

    $sql = "SELECT * FROM feedbacks WHERE object_id=:id and user_id =:user_id ORDER BY `id` DESC ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $objectId);
        $stmt->bindParam("user_id", $userId);

        $stmt->execute();
        $feedbacks = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;

        echo '{"feedbacks": ' . json_encode($feedbacks) . '}';



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}