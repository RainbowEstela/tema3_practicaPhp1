<?php
    session_start();


    //peticiones por post
        
        //peticion por post
        if($_POST) {
            //peticion de login
                //llevamos el usuario a la sesion
                if(isset($_POST["formLogin"])) {
                    $email = $_POST["email"];
                    $password = $_POST["password"];

                    $_SESSION["user"] = [
                        "email" => $email
                    ];

                    //lo redirigimos a index.php
                    header("Location: index.php");
                    die();
                }

                
            
            //peticion de registarse
                //comprobar peticion
                if(isset($_POST["formRegister"])) {
                    //comparar que los password coinciden
                    //var_dump($_POST);
                    if(strcmp($_POST["password"],$_POST["passwordRepeat"]) == 0) {
                        //procesar los datos
                        $name = $_POST["firstName"];
                        $lastName = $_POST["lastName"];
                        $email = $_POST["email"];
                        $password = $_POST["password"];

                        //pasar datos a la sesion
                        $_SESSION["user"] = [
                            "email" => $email
                        ];

                        //redirigir a index.php
                        //lo redirigimos a index.php
                         header("Location: index.php");
                         die();

                    } else {
                        //lo redirigimos a register.php
                        header("Location: register.php?error=passError");
                        die();
                    }
                }

                //peticion de crear proyecto
                    //comprobar que el usuario esta conectado
                    if(isset($_SESSION["user"])) {
                        if(isset($_POST["addProyect"])) {
                            //si no existe sesion de proyectos la creamos
                            if(!isset($_SESSION["proyects"])) {
                                $_SESSION["proyects"] = array();
                            }

                            //tratamos la informacion
                            $id = 0;
                            $nombre = $_POST["proyectName"];
                            $fechaIni = $_POST["startDate"];
                            $fechaFin = $_POST["endDate"];
                            $porcentaje = $_POST["completePercent"];
                            $importancia = $_POST["impotancia"];

                            //sacamos los dias transcurridos del proyecto
                            $diasPasados = date_diff(new DateTime($fechaIni), new DateTime("now"));
                            $diasPasadosNumerico = $diasPasados -> format("%a");
                            
                            //creamos una id
                                //comprobamos la ids de todos los proyectos
                                $ids = [];
                                foreach($_SESSION["proyects"] as $proyecto) {
                                    array_push($ids,$proyecto["id"]);
                                }

                                //recorremos las ids hasta que haya una disponible
                                $contador = 1;
                                while(true) {
                                    if(array_search($contador,$ids) === FALSE) {
                                        $id = $contador;
                                        break;
                                    }

                                    $contador++;
                                }

                            //creamos el array asociativo proyecto
                            $newProyect = [
                                "id" => $id,
                                "name" => $nombre,
                                "fechaIni" => $fechaIni,
                                "fechaFin" => $fechaFin,
                                "trascurrido" => $diasPasadosNumerico,
                                "porcentaje" => $porcentaje,
                                "importancia" => $importancia
                            ];

                            //metemos el proyecto en la sesion
                            array_push($_SESSION["proyects"],$newProyect);
                            
                            //lo redirigimos a index.php
                            header("Location: index.php");
                            die();
                            
                        }
                    } else {
                        //redirigimos al usuario a logearse
                        header("Location: login.php");
                        die();
                    }
                       
        }

    //peticiones por get
    if(isset($_GET["accion"])) {

        //comprobar si la accion es desconectarse
        if(strcmp($_GET["accion"],"logout") == 0) {
            session_destroy();
            header("Location: index.php");
            die();
        }

        //comprobar si la accion es borrar un proyecto
        if(strcmp($_GET["accion"],"borrarProyecto") == 0) {
            if(isset($_GET["idProyecto"])) {
                $idProducto = $_GET["idProyecto"];

                $posicion = array_search($idProducto, array_column($_SESSION["proyects"],"id"));

                if($posicion !== FALSE) {
                    array_splice($_SESSION["proyects"],$posicion,1);
                }

                //redirigimos a index.php
                header("Location: index.php");
                die();
            }
        }

        //comprobar si accion es borrar todo
        if(strcmp($_GET["accion"],"borrarTodo") == 0) {
            $_SESSION["proyects"] = [];

            //redirigimos a index.php
            header("Location: index.php");
            die();
        }
    }
?>