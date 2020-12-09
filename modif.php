<?php
require_once 'functions.php';
use Illuminate\Database\Capsule\Manager as DB;

echo'
<body onload="students()">
';


if($loggedin)
{
    require_once 'header.php';
    if($access== 'maestro')
    {

        if(isset($_POST['alumno']))
        {
            $alumno = sanitizeString($_POST['alumno']);
            $calc = sanitizeString($_POST['calc']);
            $fis = sanitizeString($_POST['fis']);
            $french = sanitizeString($_POST['french']);

            if($calc == "" || $fis == "" || $french == "")
            {
                $error = '<p class="is-size-5">Falta algún dato</p>';
            }
            else
            {
                $verificar_alumno = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $alumno)->first();

                if($verificar_alumno)
                {
                    $nuevacalf = DB::table('data_materias')
                    ->where('data_usuarios_id_data_usuarios', $alumno)
                    ->update(['calculo' => $calc, 'frances' => $french, 'fisica' => $fis]);

                    if($nuevacalf)
                    {
                        die('<p class="is-size-4 is-center mt-6">Haz modificado las calificaciones de un alumno, <a href="modif.php">click aquí para regresar al sistema</a></p>
                        </div></body></html>');
                    }
                    else
                    {
                        $error = "Ese alumno ya tiene calificaciones";
                    }
                }
                else
                {
                    $nuevacalf = DB::table('data_materias')->insert(
                        ['data_usuarios_id_data_usuarios' => $alumno, 'calculo'=>$calc, 'fisica'=>$fis, 'frances'=>$french]
                    );

                    if($nuevacalf)
                    {
                        die('<p class="is-size-4 is-center mt-6">Ha agregado las calificaciones de un alumno, <a href="modif.php">click aquí para regresar al sistema</a></p>
                        </div></body></html>');
                    }
                }
            }
        }

        $students = DB::table('data_usuarios')
        ->leftJoin('data_materias', 'data_usuarios.id_data_usuarios', '=', 'data_materias.data_usuarios_id_data_usuarios')
        ->where('data_usuarios.id_data_usuarios','<>',1)
        ->orderBy('apellidos')
        ->get();

        echo'
        <div class="container">
            <div class="card mt-6 mb-6">
                <header class="card-header">
                    <p class="card-header-title is-size-5">
                        Selecciona a un alumno
                    </p>
                    <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-content">
                    <div class="content">
                        <form method="post" action="modif.php">
                            <div class="field">
                                <label class="label letra is-size-4">Alumno</label>
                                <div class="control">
                                    <div class="select is-medium is-warning">
                                        <select id="alumn">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <label class="label mt-4 is-size-4">Calculo</label>
                            <div class="control">
                                <input class="input letra is-size-4" type="number" max="10" id="calc" placeholder="Calculo">
                            </div>
                            <label class="label mt-4 is-size-4">Frances</label>
                            <div class="control">
                                <input class="input letra is-size-4" type="number" max="10" id="french" placeholder="Frances">
                            </div>
                            <label class="label mt-4 is-size-4">Fisica</label>
                            <div class="control">
                                <input class="input letra is-size-4" type="number" max="10" id="fis" placeholder="Fisica" >
                            </div>
                            <br>

                            <button class="button is-medium is-warning " onclick="modificar()" type="button">Actualizar</button>
                    </div>
                        </form>
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

<script>
function students()

{
   
    axios.post(`api/index.php/students`, {

    })
    .then(resp => {
        if(resp.data.validar)
        {
            const alumnos = resp.data.alumnos;
            alumnos.forEach(alumnos => {
                var select = document.getElementById("alumn");

                var option=document.createElement("option");

                option.setAttribute("value",alumnos.id_data_usuarios);
                option.setAttribute("label",alumnos.nombre + " " + alumnos.apellidos);

                // Añadimos el option al select
                select.appendChild(option);
            });
        }
        else
        {
        }
    })
    .catch(error => {
        console.log(error);
    });

}
function modificar()
        {
            axios.post(`api/index.php/modificar`, {
                id_user: document.forms[0].alumn.value,
                calc: document.forms[0].calc.value,
                fran: document.forms[0].french.value,
                fis: document.forms[0].fis.value
            })
            .then(resp => {
                alert(resp.data.respuesta)
            })
            .catch(error => {
                console.log(error);
            });
        }

</script>
    </body>
</html>
';
?>