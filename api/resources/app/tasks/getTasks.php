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
    global $app;
    $state = $app->request()->get('state');

    $sql = "SELECT fetch_url FROM task_fetch_info WHERE app_id= :app_id ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("app_id", $appId);
        $stmt->execute();
        $url = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $fetchUrl = trim($url[0]['fetch_url'])."=".$state;
        try {
            
            $data = json_decode(httpGet($fetchUrl), true);
            $tasks = $data['root']['srs'];
                        
        } catch(Exception $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
                
        $sql2 = "SELECT mapping_fields FROM field_mapping WHERE app_id = :app_id and field_names = 'title'";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam("app_id", $appId);
        $stmt2->execute();
        $title = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $titleField = $title[0]['mapping_fields'];
        $titleFields = explode(",", $titleField);

        $sql3 = "SELECT mapping_fields FROM field_mapping 
                    WHERE app_id = :app_id and field_names = 'description'";
        $stmt3 = $db->prepare($sql3);
        $stmt3->bindParam("app_id", $appId);
        $stmt3->execute();
        $description = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $descriptionField = $description[0]['mapping_fields'];
        $descriptionFields = explode(",", $descriptionField);
        $taskData = array();
        
        foreach ($tasks as $key => $value) {
            $taskTitle = array();
            $taskDescription = array();
            //var_dump($value);die();
            foreach ($titleFields as $field){
                if(isset($value[$field]))$taskTitle[] = array($field=>$value[$field]);
            }
            foreach ($descriptionFields as $field){
                if(isset($value[$field]))$taskDescription[] = array($field=>$value[$field]);
            }
            $taskData[] = array("title"=> $taskTitle , "description" => $taskDescription );
            

        }
        echo '{"tasks":[{'.json_encode($taskData).'}]}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
function httpGet($url){
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false);

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;
}