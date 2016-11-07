<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 3:06 PM
 */

function saveFeedback($userId, $objectId){

    $request = \Slim\Slim::getInstance()->request();
    $feedback = json_decode($request->getBody());


    $sql = "INSERT INTO `wazir`.`feedbacks` (

                    `object_id` ,
                    `user_id` ,
                    `feedback`,
                    digieye_user_id
                    )
                    VALUES (
                    :object_id, :user_id, :feedback,:digieye_user_id
                    );";

    try {

        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("object_id", $objectId);
        $stmt->bindParam("user_id", $userId);

        $stmt->bindParam("feedback", $feedback->feedback);
        $stmt->bindParam("digieye_user_id", $feedback->digieye_user_id);


        $stmt->execute();

        $id = $db->lastInsertId();

        $feedback->id = $id;


        $db = null;

        echo '{"feedback": ' . json_encode($feedback) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }
}

?>
