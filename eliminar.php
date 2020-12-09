<?php
require_once 'functions.php';
use Illuminate\Database\Capsule\Manager as DB;

$aviso = $esp = $mate = $hist = "";

echo'
<body>
';


if($loggedin)
{
    require_once 'header.php';
    if($access == "maestro")
    {
        if(isset($_GET['id_eliminar']))
        {
            $id_eliminar = $_GET['id_eliminar'];

            $alumno = DB::table('data_usuarios')->where('id_data_usuarios', $id_eliminar)->first();

            $nombre_alumno = $alumno->nombre;

            $eliminar = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $id_eliminar)->delete();

            if($eliminar)
            {
                $aviso = '<p class="is-size-4 is-center mb-4">Haz eliminado las calificaciones de '.$nombre_alumno.'</p>';
            }
        }

        $alumnos = DB::table('data_materias')
        ->where('id_data_usuarios','<>',1)
        ->leftJoin('data_usuarios', 'data_materias.data_usuarios_id_data_usuarios', '=', 'data_usuarios.id_data_usuarios')
        ->orderBy('apellidos')
        ->get();

        echo'
    <div class="container">
        <div class="card mt-6 mb-6">
            <header class="card-header">
                <p class="card-header-title">
                    Elige un alumno para eliminar sus calificaciones
                </p>
                <a href="#" class="card-header-icon" aria-label="more options">
                    <span class="icon">
                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                    '.$aviso.'
                    <table class="table">
                        <thead>
                            <tr>
                                <th><abbr title="Num">No. Lista</abbr></th>
                                <th><abbr title="Nombre">Nombre</abbr></th>
                                <th><abbr title="Apellido">apellido</abbr></th>
                                <th><abbr title="Cali1">Calculo</abbr></th>
                                <th><abbr title="Cali2">Frances</abbr></th>
                                <th><abbr title="Cali3">Fisica</abbr></th>
                                <th><abbr title="Botón">Acción</abbr></th>
                            </tr>
                        </thead>
                        
                        <tbody>';
                        $lista=0;
                        foreach($alumnos as $z)
                        {
                            $lista+=1;
                            echo'
                            <tr>
                                <th>'.$lista.'</th>
                                <td>'.$z->nombre.'</td>
                                <td>'.$z->apellidos.'</td>
                                <td>'.$z->calculo.'</td>
                                <td>'.$z->frances.'</td>
                                <td>'.$z->fisica.'</td>
                                <td><a class="button is-link is-warning" href="eliminar.php?id_eliminar='.$z->id_data_usuarios.'">Eliminar</a></td>
                            </tr>

                            <figure class="image is-128x128">
                            <img src="img/logoalta.jpg">
                          </figure>
                            ';
                        }
                        echo'
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    ';
    }
    else
    {
        echo"<p class='is-size-5 is-center mt-6'>No tienes permisos para estar aquí <a href='index.php'>click aquí para regresar al inicio</a></p>";
    }
}
else{
    echo"<p class='is-size-5 is-center mt-6'>Necesitas una cuenta para usar este sistema <a href='login.php'>click aquí para regresar al login</a></p>";
}

echo'

    </body>
</html>
';
?>