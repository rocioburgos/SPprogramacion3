<?php

    class MW{

        public function VerificarSeteados($request,$response,$netx){
           
            //devuelve TRUE si var existe y tiene un valor distinto de NULL, FALSE de lo contrario.
            if(isset($request->getParsedBody()['correo']) && isset($request->getParsedBody()['clave'])){ 
            
           

                MW::VerificarCamposVacios($request,$response,$netx);
               $response= $netx($request,$response);
                    
            }
            else
            { 
                
                return $response->withJson(array(["error"=>"valores no seteados"]));
            }

            return $response;

        }

        public function VerificarCamposVacios($request,$response,$netx){
            
            $correo = $request->getParsedBody()['correo'];
            $clave = $request->getParsedBody()['clave'];

            if(empty($correo) && empty($clave) ){ 

             return $response->withJson(array(["error"=>"valores vacios"]));
            
            }
            else
            {
                
                MW::VerificarBD($request,$response,$netx);
              
               
            }
                return $request;
        }


        public function VerificarBD($request,$response,$netx){

            $correo = $request->getParsedBody()['correo'];
            $clave = $request->getParsedBody()['clave'];

            $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', "root", "");
                  
                    $sql =$objetoPDO->prepare("SELECT * FROM usuarios WHERE correo = :correo AND clave = :clave");
                    $sql->bindValue(':correo', $correo);
                    $sql->bindValue(':clave', $clave);
                    $sql->execute();
                
                    $result = $sql->rowCount();
                

                if($result==1){

                    $response = $next($request, $response);
            
                    return $response;

                }else
                {
                    return $response->withJson(array(["error"=>"ERROR"]));
                }
        }

    }
?>