<?php
    class Media{
        public function AgregarMedia($request,$response,$next)//para el post
        {
           
                    $color = $request->getParsedBody()['color'];
                    $marca = $request->getParsedBody()['marca'];
                    $precio = $request->getParsedBody()['precio'];
                    $talle = $request->getParsedBody()['talle'];
                
                    $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', "root", "");
       
                        $consulta =$objetoPDO->prepare("INSERT INTO medias (color, marca, precio,talle) VALUES(:color, :marca, :precio, :talle)");
                    
                       
                        $consulta->bindValue(':color', $color, PDO::PARAM_STR);
                        $consulta->bindValue(':marca', $marca, PDO::PARAM_STR);
                        $consulta->bindValue(':precio', $precio, PDO::PARAM_STR);
                        $consulta->bindValue(':talle',$talle,PDO::PARAM_STR);

                        $consulta->execute(); 
                        $response->getBody()->write("Elemento agregado con exito");

                        $response = $next($request, $response);
            
                return $response;

        }

        public function TraerTodasMedias($request,$response){
            $arrayDeMedias=array();
            $objetoPDO = new PDO('mysql:host=localhost;dbname=merceriabd;charset=utf8', "root", "");
            $sql = $objetoPDO->prepare('SELECT * FROM medias');
            $sql->execute();
            
            while($result = $sql->fetchObject())
            {
                array_push($arrayDeMedias,$result);
            }
            $cant= count($arrayDeMedias);
           /* for($i=0;$i<$cant;$i++){
             $response->getBody()->write(  $arrayDeMedias[$i]->color." ".$arrayDeMedias[$i]->marca ." ".$arrayDeMedias[$i]->talle." ".$arrayDeMedias[$i]->precio."<br>");
            }*/
            return $response->withJson($arrayDeMedias);
           
        }

    }
    
?>