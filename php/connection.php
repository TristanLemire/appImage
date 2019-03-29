<?php
// retourne le bon device
function getDeviceType(){
  $query = http_build_query([
    'access_key' => 'a2b427056cc77f71b6422e3fec958ed8',
    'ua' => $_SERVER['HTTP_USER_AGENT'],
  ]);

  $ch = curl_init('http://api.userstack.com/detect?' . $query);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $json = curl_exec($ch);
  curl_close($ch);
  
  $api_result = json_decode($json, true);
  return $api_result['device']["type"];
}

function get_image($url)
{
  //connexion base de donnée
  try {
    $PDO = new PDO('mysql:host=localhost;dbname=appImage;charset=utf8', 'root', 'root', [
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_WARNING,
      PDO::MYSQL_ATTR_INIT_COMMAND  => 'SET NAMES utf8',
    ]);
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }

  $requete = $PDO->query("SELECT max(id) from images;");
  $lastID = $requete->fetch();

  echo $lastID[0];

  //retourne la bonne url de l'image
  $url2 = explode("///", $url);
  $trueUrl = 'http://' . $url2[1];
  copy($trueUrl, '../assets/images/image_Id_'.$lastID.'.jpg');
  
  // cherche le type de device (phone,tablette,desktop)
  $device = getDeviceType();
  

  if($device = 'desktop'){
    imagecopyresampled();
  } elseif ($device = 'phone') {

  } else {

  }

  // http://localhost:8000///static.service-voyages.com/photos/vacances/Pointe_A_Pitre/vue_61590_pgbighd.jpg
  
  $stmt = $PDO->prepare('SELECT * from images where url = :url ;');
  $stmt->execute(["url" => $trueUrl]);
  $image = $stmt->fetch();
  return $image;
}

get_image('http://localhost:8000///static.service-voyages.com/photos/vacances/Pointe_A_Pitre/vue_61590_pgbighd.jpg');
