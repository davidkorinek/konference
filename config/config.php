<?php
return [
    'db_host' => '127.0.0.1',                                   //localhost
    'db_name' => 'konference',                                  //nazev databaze
    'db_user' => 'root',                                        //user - root
    'db_pass' => '',                                            //heslo
    'charset' => 'utf8mb4',                                     //charset pro češtinu
    'upload_dir' => __DIR__ . '/../public/assets/uploads/',     //misto ukladani souboru
    'upload_max_size' => 10 * 1024 * 1024 // 10 MB              //maximalni velikost pdf
];
