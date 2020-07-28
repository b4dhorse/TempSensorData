<?php
/**
 * Connects to the appspot.com data location, downloads the data, creates a new array, grabs the current stats, and then the history stats, outputs a JSON object:
 * {
 *      current:{
 *          date
 *          ambient
 *          probe
 *          humidity
 *      }
 *      history:[
 *          array
 *              date
 *              ambient
 *              probe
 *              humidity
 *      ] 
 * }
 */

//Fill out these variables for your account
$pkey = "my pkey";
$ref = "my ref";
$sensor = "my sensor";

//data URL to get alllll the data
$dataURL = "http://decent-destiny-704.appspot.com/laxservices/user-api.php?pkey=$pkey&ref=$ref&sensor=$sensor&action=usercsv";

//convert to CSV object
$csv= file_get_contents($dataURL);

//convert to array
$history = array_map("str_getcsv", explode("\n", $csv));

//new data array
$data = array();

//variable for current stats
$data["current"] = array();

//apparently the first data set is the most current, so grab that as your current info
$data["current"]["date"] = $history[1][0];
$data["current"]["ambient"] = $history[1][1];
$data["current"]["probe"] = $history[1][2];
$data["current"]["humidity"] = $history[1][3];

//new history variable to store all history
$data["history"] = array();

//used to skip the first row since it has column names
$count = 0;

//loop through the history and create a new associative array
foreach($history as $h){
    if($count > 0){
        $new = array();
        //grab the data
        $new["date"] = $h[0];
        $new["ambient"] = $h[1];
        $new["probe"] = $h[2];
        $new["humidity"] = $h[3];
        //add to the history array
        array_push($data["history"],$new);
    }
    $count ++;
}

//create JSON object from the new data
$json = json_encode($data);

//header for JSON file
header("Content-type: application/json; charset=utf-8");
//print the results
print_r($json);