<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/7/16
 * Time: 1:54 PM
 *
 * There can be different states based on user type, Ignoring JIT
 */


function getTasks($appId){
    $state = $app->request()->get('state');

    $sql = "SELECT fetch_url FROM task_fetch_info WHERE app_id= :app_id ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("app_id", $appId);
        $stmt->execute();
        $url = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '$stmt->("fetch_url")');
        curl_setopt($ch, CURLOPT_HEADER, 0);            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
        $raw_data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($raw_data);
        
        $sql2 = "SELECT mapping_fields FROM field_mapping WHERE app_id = :app_id and field_names = 'title'";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam("app_id", $appId);
        $stmt2->execute();
        $title = $stmt2->fetchAll(PDO::FETCH_OBJ);
        $title_fields = explode(",", $title->('mapping_fields'));

        $sql3 = "SELECT mapping_fields FROM field_mapping 
                    WHERE app_id = :app_id and field_names = 'description'";
        $stmt3 = $db->prepare($sql3);
        $stmt3->bindParam("app_id", $appId);
        $stmt3->execute();
        $description = $stmt3->fetchAll(PDO::FETCH_OBJ);
        $description_fields = explode(",", $description->('mapping_fields'));
        echo $description_fields;
        //echo '{"states": ' . json_encode($feedbacks) . '}';



    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}