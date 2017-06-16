<?php

$path = './';

$directory = new RecursiveDirectoryIterator($path);


$iterator = new RecursiveIteratorIterator($directory);


$files = new RegexIterator($iterator, '/^.+\.html/i', RecursiveRegexIterator::GET_MATCH);

echo "[$path]\n";
foreach ($files as $file) {

    $filePath = $file[0];

    // don't move indexes
    if (basename($filePath) !== 'index.html') {
        $newPath = str_replace(['.1', '.html'], '', $filePath).'/index.html';
        if (!is_dir(dirname($newPath))) {
            mkdir(dirname($newPath), 0755, true);
        }
        rename($filePath, $newPath);
#        echo " ├ $newPath\n";
    }


}

#var_dump($iterator);
#var_dump($regex);
