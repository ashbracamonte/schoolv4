<?php
require_once 'functions.php';
use Illuminate\Database\Capsule\Manager as DB;

echo'
<body onload="students(), calif()">

';

$error = "";

if($loggedin){
    require_once 'header.php';
    echo'
    <div class="container">
    ';

    if($access == 'maestro')
    {
        if(!empty($_POST['alumn']))
        {
           
            $id_alumno = sanitizeString($_POST['alumn']);
            $calculo = sanitizeString($_POST['calculo']);
            $frances = sanitizeString($_POST['frances']);
            $fisica = sanitizeString($_POST['fisica']);

            
            
            $validar = DB::table('data_materias')->where('data_usuarios_id_data_usuarios',$id_alumno)
            ->first();

            
            if(!$validar)
            {
               
                
                $calificaciones = DB::table('data_materias')->insertOrIgnore(
                    ['data_usuarios_id_data_usuarios' => $id_alumno, 'calculo' => $calculo, 'frances' => $frances, 'fisica' => $fisica]
                );
                die("
                    <div class='is-size-4'>
                        <meta http-equiv='Refresh' content='3;url=index.php'>
                        <h1>Calificaciones agregadas<h1>
                    </div>
                    </div></body></html>
                ");
            }
            else{
                $error = "Ese alumno ya tiene calificaciones";
            }
        }


   
        $users = DB::table('data_usuarios')->where('id_data_usuarios',"<>",1)->orderBy('apellidos')->get();

        
        echo'

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
                    <form method="post" action="index.php" >
                    <label></label>
                     
                    <span class="error is-size-4"><h4 class="mt-3 mb-3">'.$error.'</h4></span>
                    <div class="field">
                        <label class="label letra is-size-4" for="alumn">Alumno</label>
                        <div class="control">
                            <div class="select is-medium is-warning">
                                <select id="alumn" name="alumn" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label  letra is-size-4" for="calculo">Calculo</label>
                        <div class="control">
                            <input class="input  letra is-size-4" type="number" id="calculo" name="calculo" min="1" max="10"  placeholder="Calculo">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label  letra is-size-4" for="frances">Frances</label>
                        <div class="control">
                            <input class="input letra is-size-4" type="number" id="frances" name="frances" min="1" max="10"  placeholder="Frances">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label letra is-size-4" for="fisica">Fisica</label>
                        <div class="control">
                            <input class="input  letra is-size-4" type="number" id="fisica" name="fisica" min="1" max="10"  placeholder="Fisica">
                        </div>
                    </div>
                    <button class="button is-medium is-warning "onclick="añadir()" type="button">Agregar</button>
                    <br>
                    <br>
                    <br>
                    <br>
    
                </form>
                    </div>
                </div>
            </div>
        </div>
      

        

        

            
        ';
    }
    else
    {
        $calificaciones = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $id)->first();

        echo'
        <div class="card mt-6 mb-6 is-light">
            <header class="card-header">
                <p class="card-header-title">
                    Calificaciones y promedio general
                </p>
                <a href="#" class="card-header-icon" aria-label="more options">
                    <span class="icon">
                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content">
                <div class="content">
                        <form>
                                <input type="hidden" value="'.$id.'" id="id"> 
                                <label class="label mt-4">calculo</label>
                                <div class="control">
                                    <input class="input" type="number" id="calculo" readonly>
                                </div>
                                <label class="label mt-4">Frances</label>
                                <div class="control">
                                    <input class="input" type="number" id="frances" readonly>
                                </div>
                                <label class="label mt-4">Fisica</label>
                                <div class="control">
                                    <input class="input" type="number" id="fisica" readonly>
                                </div>
                                <label class="label mt-6">Promedio General</label>
                                <div class="control">
                                    <input class="input" type="number" id="promedio" readonly>
                                </div>
                            </div>
                           
                        </form>         
                </div>
            </div>
        </div>
    </div>
    ';
    }
}
else{
    echo'<meta http-equiv="Refresh" content="0;url=login.php">';
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
        function añadir()
        {
            axios.post(`api/index.php/añadir`, {
                id_user: document.forms[0].alumn.value,
                calc: document.forms[0].calculo.value,
                fran: document.forms[0].frances.value,
                fis: document.forms[0].fisica.value
            })
            .then(resp => {
                alert(resp.data.respuesta)
            })
            .catch(error => {
                console.log(error);
            });
        }

        function eliminar()
        {
            axios.post(`api/index.php/eliminar`, {
                id: document.getElementById("alumn").value
            })
            .then(resp => {
                alert(resp.data.respuesta)
            })
            .catch(error => {
                console.log(error);
            });
        }
        
        function calif()
        {
            axios.post(`api/index.php/calif`, {
                id: document.getElementById("id").value
            })
            .then(resp => {
                if(resp.data.user)
                {
                    document.getElementById("calculo").value = resp.data.calc;
                    document.getElementById("frances").value = resp.data.fran;
                    document.getElementById("fisica").value = resp.data.fis;
                    document.getElementById("promedio").value = resp.data.prom;
                }
                else
                {
                    alert(resp.data.respuesta)
                }
            })
            .catch(error => {
                console.log(error);
            });
        }


    </script>

    
    </body>
</html>
';