<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use \Firebase\JWT\JWT as jwt; 
    require_once './vendor/autoload.php';
    require_once './Clases/media.php';
    require_once './Clases/usuario.php';
    require_once './Clases/mw.php';
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);

    //A nivel de aplicacion un post
    $app->post('[/]', function (Request $request, Response $response) {   
          
        return $response;
    })->add(\Media:: class ."::AgregarMedia");

    $app->get('/medias', function (Request $request, Response $response) {   
        
        $json= Media::TraerTodasMedias($request,$response);
        echo $json;
        return $response;

    });

    $app->post('/usuarios', function (Request $request, Response $response) {   
          
        
        return $response;
    })->add(\Usuario:: class ."::AgregarUsuario");
    
    $app->get('[/]', function (Request $request, Response $response) {   
        
        $json= Usuario::TraerTodosUsuarios($request,$response);
        echo $json;
        return $response;

    });

    $app->group('/login',function(){

        $this->post('[/]', function (Request $request, Response $response) {   
          
           echo Usuario::LoginUsuario($request,$response);
            return $response;
        })->add(\MW:: class ."::VerificarSeteados");

        $this->get('[/]', function (Request $request, Response $response) {   
          
          echo  Usuario::VerificarTokenUsuario($request,$response);
            return $response;
        });

    });
    

   

$app->run();
?>