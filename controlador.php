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
        }

    //peticiones por get
    if($_GET["accion"]) {
        if(strcmp($_GET["accion"],"logout") == 0) {
            session_destroy();
            header("Location: index.php");
            die();
        }
    }
?>