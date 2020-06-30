<?php    
    namespace App;
    use App\UserController;

    $Klein = new \Klein\Klein();
    
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
    $Klein->respond('GET', '/hello-world', [ new UserController(), 'index' ]);
        
    $Klein->dispatch();

?>