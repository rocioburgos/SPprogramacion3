<?php
 use \Firebase\JWT\JWT as jwt; 
    class Usuario{
        public function AgregarUsuario($request,$response,$next)//para el post
        {
           
                    $correo = $request->getParsedBody()['correo'];
                    $clave = $request->getParsedBody()['clave'];
                    $nombre = $request->getParsedBody()['nombre'];
                    $apellido = $request->getParsedBody()['apellido'];
                    $perfil = $request->getParsedBody()['perfil'];
                 
                   /* Fotos */
                    $archivos = $request->getUploadedFiles();
                    $destino="./FotosUsuarios/";
                                $nombreAnterior=$archivos['foto']->getClientFilename();
                    $extension= explode(".", $nombreAnterior)  ;
                    $extension=array_reverse($extension);
                    $foto=$destino.$apellido.".".$extension[0];
                    
          
                    $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', "root", "");
                        $consulta =$objetoPDO->prepare("INSERT INTO usuarios (correo, clave, nombre,apellido, foto,perfil) VALUES(:correo, :clave, :nombre,:apellido, :foto,:perfil)");
                    
                        $archivos['foto']->moveTo($foto);
                        $consulta->bindValue(':correo', $correo, PDO::PARAM_STR);
                        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
                        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                        $consulta->bindValue(':apellido',$apellido,PDO::PARAM_STR);
                        $consulta->bindValue(':foto',$foto,PDO::PARAM_STR);
                        $consulta->bindValue(':perfil',$perfil,PDO::PARAM_STR);

                        $consulta->execute(); 
                        $response->getBody()->write("Usuario agregado con exito");
                   
                        $response = $next($request, $response);
            
                return $response;


        }

        public function TraerTodosUsuarios($request,$response){
            $arrayDeUsuarios=array();
            $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', "root", "");
            $sql = $objetoPDO->prepare('SELECT * FROM usuarios');
            $sql->execute();
            
            while($result = $sql->fetchObject())
            {
                array_push($arrayDeUsuarios,$result);
            }
           /* $cant= count($arrayDeUsuarios);
            for($i=0;$i<$cant;$i++){
             $response->getBody()->write($arrayDeUsuarios[$i]->nombre." ".$arrayDeUsuarios[$i]->apellido ." ".$arrayDeUsuarios[$i]->correo." ".$arrayDeUsuarios[$i]->perfil."<br>");
            }*/
            return $response->withJson($arrayDeUsuarios);
           
        }

        public function LoginUsuario($request,$response){
            $correo = $request->getParsedBody()['correo'];
            $clave = $request->getParsedBody()['clave'];

            $usuario='root';
            $pass='';
            $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', $usuario, $pass);
            $sql=$objetoPDO->prepare('SELECT `correo`,`clave` FROM `usuarios` WHERE `correo` = :nombre AND `clave` = :clave');
            $sql->bindValue(':nombre', $correo);
            $sql->bindValue(':clave', $clave);
            $sql->execute();
            $result = $sql->rowCount();
            if($result)
            {
                $resultado=$sql->fetch();
                
                $ahora=time();

                $payload = array(
                   'iat' => $ahora,
                   'exp'=> $ahora +(100),
                   'data' => $resultado,
                   'app' => "probando"
                );
        
                $token = JWT::encode($payload, "miClave");
                
              //  $response->getBody()->write($token);
                
                return $response->withJson($token);
            }
            else
            {
                $response->getBody()->write('Error nombre o clave incorrectos');
            }
            return $response;
        }

        public function VerificarTokenUsuario($request,$response){

            $token= $request->getHeader('token');
            if(empty($token[0])|| $token[0] === "")
            {
                echo "el token esta vacio";
            }
            try
            {
                $jwtDecode = JWT::decode($token[0],'miClave',array('HS256'));
             }
             catch(Exception $e){

                return $response->withJson(array(["error"=>"token invalido"]));
             }

             return $response->withJson(array(["correcto"=>"token valido"]));
            
            }
            
        }



    
    
?>