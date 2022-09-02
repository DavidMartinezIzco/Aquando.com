<?php

//comprueba el estado de la session interna
session_start();

if (isset($_SESSION['nombre'])) {
    echo $_SESSION['nombre'];
} else {
    echo "fail";
}