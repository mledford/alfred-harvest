<?php

  $shortname   = "sparkbox";
  $email		   = "neil@heysparkbox.com";
  $password    = "whi-mimo-kej";

  $credentials = $email . ":" . $password;
  $get_daily   = "https://$shortname.harvestapp.com/daily";

  $headers = array (
    "Content-type: application/json",
    "Accept: application/json",
    "Authorization: Basic " . base64_encode($credentials)
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $get_daily);
  curl_setopt($ch, CURLOPT_VERBOSE, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $data_raw = curl_exec($ch);

  if (curl_errno($ch)) {
    print "Error: " . curl_error($ch);
  } else {
    curl_close($ch);
  }

  $data = json_decode($data_raw, true);
  $xml = "<?xml version=\"1.0\"?>\n<items>\n";

  foreach ($data["day_entries"] as $day_entry){
    $project = $day_entry["project"];
    $task    = $day_entry["task"];
    $client  = $day_entry["client"];
    $hours   = $day_entry["hours"];

    $xml .= "<item arg=\"https://$shortname.harvestapp.com\">\n";
    $xml .= "<title>[$hours] $project</title>\n";
    $xml .= "<subtitle>$client, $task</subtitle>\n";
    $xml .= "<icon>icon.png</icon>\n";
    $xml .= "</item>\n";
  }

  $xml .="</items>";
  echo $xml;
?>