<?php 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');   

// At the very beginning, start output buffering
ob_start();

// Initialize Facebook
require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
    'app_id' => config.appId,
    'app_secret' => config.appSecret,
    'default_graph_version' => config.version,
    'default_access_token' => config.appToken,
]);

if(isset($_GET['keyword'])){
    //Call Facebook API -- USERS 
    $userrequest = $fb->request('GET', '/search?q='.$_GET['keyword'].'&type=user&fields=id,name,picture.width(700).height(700)');
    try {        
        $userresponse = $fb->getClient()->sendRequest($userrequest);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error : ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $userfacebookJSON = $userresponse->getDecodedBody();

    //Call Facebook API -- PAGES 
    $pagerequest = $fb->request('GET', '/search?q='.$_GET['keyword'].'&type=page&fields=id,name,picture.width(700).height(700)');
    try {        
        $pageresponse = $fb->getClient()->sendRequest($pagerequest);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $pagefacebookJSON = $pageresponse->getDecodedBody();

    //Call Facebook API -- EVENTS 
    $eventrequest = $fb->request('GET', '/search?q='.$_GET['keyword'].'&type=event&fields=id,name,picture.width(700).height(700),place');
    try {        
        $eventresponse = $fb->getClient()->sendRequest($eventrequest);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $eventfacebookJSON = $eventresponse->getDecodedBody();

    //Call Facebook API -- PLACES 
    $placerequest = $fb->request('GET', '/search?q='.$_GET['keyword'].'&type=place&center='.$_GET['position'].'&fields=id,name,picture.width(700).height(700)');
    try {        
        $placeresponse = $fb->getClient()->sendRequest($placerequest);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $placefacebookJSON = $placeresponse->getDecodedBody();

    //Call Facebook API -- GROUPS 
    $grouprequest = $fb->request('GET', '/search?q='.$_GET['keyword'].'&type=group&fields=id,name,picture.width(700).height(700)');
    try {        
        $groupresponse = $fb->getClient()->sendRequest($grouprequest);

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $groupfacebookJSON = $groupresponse->getDecodedBody();

    ob_end_clean();

    if ($_GET["tabName"] == "place"){
        echo json_encode($placefacebookJSON);
    }
    else if ($_GET["tabName"] == "user"){
        echo json_encode($userfacebookJSON);
    }
    else if ($_GET["tabName"] == "group"){
        echo json_encode($groupfacebookJSON);
    }
    else if ($_GET["tabName"] == "event"){
        echo json_encode($eventfacebookJSON);
    }
    else if ($_GET["tabName"] == "page"){
        echo json_encode($pagefacebookJSON);
    }
    else if ($_GET["tabName"] == "place"){
        echo json_encode($placefacebookJSON);
    }
}

//Call Facebook API -- ALBUMS AND POSTS 
else if(isset($_GET['detail'])){
    $request = $fb->request('GET', '/'.$_GET['detail'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5){created_time,message}');
    try {        
        $response = $fb->getClient()->sendRequest($request);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error : ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $facebookJSON = $response->getDecodedBody();
    ob_end_clean();
    echo json_encode($facebookJSON, JSON_UNESCAPED_SLASHES);
}
else if(isset($_GET['picture'])){
    $request = $fb->request('GET', '/'.$_GET['picture'].'/picture?redirect=false&height=700&width=700');
    try {        
        $response = $fb->getClient()->sendRequest($request);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error : ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<p>';
    $facebookJSON = $response->getDecodedBody();
    ob_end_clean();
    echo json_encode($facebookJSON);
}