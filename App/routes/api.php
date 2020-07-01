<?php
    namespace App;
    use App\UserController;

    $Klein = new \Klein\Klein();

    // Routes.....
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);

    // Dispatch all routes....
    $Klein->dispatch();

?>
