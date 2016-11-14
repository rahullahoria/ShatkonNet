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
        $fetch_url = trim($url[0]['fetch_url']);
        try {
            
            $data = httpGet($fetch_url);
            $testdata = json_decode($data);
            var_dump($testdata['root']['srs'][0]); die();
            
        } catch(Exception $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
                
        $sql2 = "SELECT mapping_fields FROM field_mapping WHERE app_id = :app_id and field_names = 'title'";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindParam("app_id", $appId);
        $stmt2->execute();
        $title = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $title_field = $title[0]['mapping_fields'];
        $title_fields = explode(",", $title_field);

        $sql3 = "SELECT mapping_fields FROM field_mapping 
                    WHERE app_id = :app_id and field_names = 'description'";
        $stmt3 = $db->prepare($sql3);
        $stmt3->bindParam("app_id", $appId);
        $stmt3->execute();
        $description = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $description_field = $description[0]['mapping_fields'];
        $description_fields = explode(",", $description_field);
        foreach ($variable as $key => $value) {
            # code...
        }
              
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