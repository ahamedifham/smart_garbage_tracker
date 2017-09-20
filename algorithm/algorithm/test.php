<?php

function initializingParameters(){

    global $windowSize;
    $windowSize=5;

}

$file = new SplFileObject("inputDataStream");
$consoleReadingsJson=array();
initializingParameters();
while(1){
    $waiting=$windowSize;
    while($waiting) {

// Loop until we reach the end of the file.
        if (!$file->eof()) {
            echo "file read\n";
            // Echo one line from the file.
            $consoleReadingsJson[$waiting]=json_decode($file->fgets());
        }
        else{
            $file = null;
            break;
        }

// Unset the file to call __destruct(), closing the file handle.


        //$consoleReadingsJson[$waiting]=fgets(STDIN);

        //var_dump(json_decode(substr($consoleReadingsJson[$waiting],1,-2)));

        $waiting -= 1;
    }
    foreach ($consoleReadingsJson as $consoleReadingJson) {
        var_dump($consoleReadingJson[0]->name);
        var_dump($consoleReadingJson[0]->lon);
        var_dump($consoleReadingJson[0]->lat);
        //echo $consoleReadingJson;
    }
    $consoleReadingsJson=null;
    if ($file==null){
        break;
        echo "file is over";
    }
}
?>
