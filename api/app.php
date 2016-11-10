<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:12 PM
 */

\Slim\Slim::registerAutoloader();

global $app;

if(!isset($app))
    $app = new \Slim\Slim();

$app->response->headers->set('Access-Control-Allow-Credentials',  'true');

$app->response->headers->set('Content-Type', 'application/json');

/* Starting routes */

$app->get('/app/:appId/states','getAppStates');
$app->get('/app/:appId/tasks/:state/options','getOptions');
$app->get('/app/:appId/tasks','getTasks');
//$app->post('/feedbacks/:user_id/:id', 'saveFeedback');

/* Ending Routes */

$app->run();																																																																										