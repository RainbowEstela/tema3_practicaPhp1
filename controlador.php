<?php
    session_start();
    //funciones

    //funcion que añade un proyecto a la sesion y que crea la sesion si no existe
    function addProyectToSession($id, $nombre, $fechIni, $fechaFin, $porcentaje , $importancia) {
        //si no existe sesion de proyectos la creamos
        if(!isset($_SESSION["proyects"])) {
            $_SESSION["proyects"] = array();
        }

        //calculamos los dias trascurridos desde la fecha de inicio ya que cambia cada día
        $diasPasados = date_diff(new DateTime($fechIni), new DateTime("now"));
            //no se como funciona esta flecha pero en la pagina de php dice que es la forma de pasar un date diff a dias
        $diasPasadosNumerico = $diasPasados -> format("%a");

        //creamos el array asociativo proyecto
        $newProyect = [
            "id" => $id,
            "name" => $nombre,
            "fechaIni" => $fechIni,
            "fechaFin" => $fechaFin,
            "trascurrido" => $diasPasadosNumerico,
            "porcentaje" => $porcentaje,
            "importancia" => $importancia
        ];

        //metemos el proyecto en la sesion
        array_push($_SESSION["proyects"],$newProyect);

    }

    //funcion que carga los proyectos del array asociativo
    function loadProyects() {
        $proyects = [
            [
                "id" => 1,
                "name" => "pelador de patatas",
                "fechaIni" => "2018-01-12",
                "fechaFin" => "2015-01-13",
                "porcentaje" => "50",
                "importancia" => 5
            ],

            [
                "id" => 3,
                "name" => "gato robot",
                "fechaIni" => "2020-06-20",
                "fechaFin" => "2024-02-14",
                "porcentaje" => "0",
                "importancia" => 1
            ],

            [
                "id" => 4,
                "name" => "cafe de chocolate",
                "fechaIni" => "2023-04-23",
                "fechaFin" => "2050-12-12",
                "porcentaje" => "99",
                "importancia" => 4
            ],

            [
                "id" => 6,
                "name" => "mi nuevo juego",
                "fechaIni" => "2023-10-15",
                "fechaFin" => "2200-10-15",
                "porcentaje" => "0",
                "importancia" => 1
            ],
        ];

        foreach($proyects as $proyect) {
            addProyectToSession($proyect["id"],$proyect["name"],$proyect["fechaIni"],$proyect["fechaFin"],$proyect["porcentaje"],$proyect["importancia"]);
        }
    }



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

                    //cargar los proyectos en la sesion
                    loadProyects();

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

                        //cargar los proyectos en la sesion
                        loadProyects();

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

                            //creamos una id
                            $id = 0;
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
                            
                            //metemos el proyecto en la sesion con la funcion
                            addProyectToSession($id, $_POST["proyectName"], $_POST["startDate"], $_POST["endDate"], $_POST["completePercent"], $_POST["impotancia"]);
                            
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