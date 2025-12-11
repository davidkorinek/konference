<?php
namespace Davca\Konference\Controllers;

use Davca\Konference\Models\File;

class HomeController {
    public function index(){
        session_start();
        // nacteni publikovanych clanku
        $fileModel = new File();
        $files = $fileModel->getPublishedFiles();

        // render homepage
        require __DIR__ . '/../View/home.php';
    }

    public function program()
    {
        require __DIR__ . '/../View/program.php';
    }

}
