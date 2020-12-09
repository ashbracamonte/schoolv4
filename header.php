<?php
require_once 'functions.php';

if($access == "maestro")
{
echo <<<_head
<nav class="navbar is-light is-size-4 " role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <div class="ml-3 mt-3 mb-3 mr-4 is-unselectable">
            <img src="img/logoalta2.png" width="90" height="90">
        </div>

        <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="true">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>
    <div class="navbar-menu" id="navMenu">
        <div class="navbar-start">
            <span class="navbar-item is-unselectable">
                Bienvenido profesor: <h6 class="user ml-2">$name $lastname</h6>
            </span>

            <a href="index.php" class="navbar-item linknav">
                Inicio
            </a>

            <a href="modif.php" class="navbar-item linknav">
                Actualizar
            </a>

            <a href="eliminar.php" class="navbar-item linknav">
                Borrar
            </a>

            <a href="logout.php" class="navbar-item linknav">
                Cerrar sesión
            </a>

        </div>
    </div>
</nav>
_head;
}
else{
echo <<<_head
    <nav class="navbar is-light is-size-4 " role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <div class="ml-3 mt-3 mb-3 mr-4 is-unselectable">
                <img src="img/logoalta2.png" width="90" height="90">
            </div>

            <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="true">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div class="navbar-menu" id="navMenu">
            <div class="navbar-start">
                <span class="navbar-item is-unselectable">
                    Bienvenido: <h6 class="user ml-2">$name $lastname</h6>
                </span>

                <a href="index.php" class="navbar-item linknav">
                    Inicio
                </a>

                <a href="logout.php" class="navbar-item linknav">
                    Cerrar sesión
                </a>

            </div>
        </div>
    </nav>
_head;
}
?>
