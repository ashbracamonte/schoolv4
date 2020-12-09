<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as DB;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';

// Instantiate app
$app = AppFactory::create();
$app->setBasePath("/schoolv4/api/index.php");

// Add Error Handling Middleware
$app->addErrorMiddleware(true, false, false);


$app->post('/login', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $consulta = DB::table('data_usuarios')->where('usuario', $data->user)->first();

    $msg = new stdClass();
    if($consulta)
    {
        if($consulta->password==$data->pass)
        {
            $msg->rsp=true;
            $msg->user=$data->user;

        }
        else
        {
            $msg->rsp=false;
        }
    }


    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->get('/login/{usuario}', function (Request $request, Response $response, array $args) {

    $msg = "Procesando...";

    require_once '../functions.php';

    $_SESSION['user'] = $args['usuario'];
    echo'<meta http-equiv="Refresh" content="0;url=../../../index.php">';

    $response->getBody()->write($msg);
    return $response;
});

$app->post('/students', function (Request $request, Response $response, array $args) {

    //! Consulta a la base de datos, llama alumnos
    $users = DB::table('data_usuarios')->where('id_data_usuarios',"<>",1)->orderBy('apellidos')->get();

    $msg = new stdClass();

    if($users)
    {
        $msg->validar = true;
        $msg->alumnos = $users;
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/añadir', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $msg = new stdClass();

    if($data->calc == "" || $data->fran == "" || $data->fis == "")
    {
        $msg->respuesta = "Se requieren datos.";
    }
    else {

        $id = $data->id_user;

        $valid = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $id)->first();

        if(!$valid)
        {
            $inser = DB::table('data_materias')->insert(
                ['data_usuarios_id_data_usuarios' => $id, 'calculo' => $data->calc, 'frances' => $data->fran, 'fisica' => $data->fis]
            );

            if($inser)
            {
                $msg->respuesta = 'Las calificaciones han sido añadidas.';
            }
        }
        else {
            $msg->respuesta = 'Este alumno ya cuenta con calificaciones.';
        }
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});
$app->post('/modificar', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $msg = new stdClass();

    if($data->calc == "" || $data->fran == "" || $data->fis== "")
    {
        $msg->respuesta = "Se requieren datos.";
    }
    else {
        $user = $data->id_user;

        $mod = DB::table('data_materias')
        ->where('data_usuarios_id_data_usuarios', $user)
        ->update(
            ['calculo' => $data->calc, 'frances' => $data->fran, 'fisica' => $data->fis]
        );

        if($mod)
        {
            $msg->respuesta = 'Las calificaciones del alumno han sido modificadas.';
        }
        else {
            $msg->respuesta = 'El alumno ya tenía esas calificaciones, se requieren unas distintas';
        }
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/eliminar', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $msg = new stdClass();

    $elim = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $data->id)->first();

    if($elim)
    {
        DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $data->id)->delete();
        $msg->respuesta = "Las calificaciones han sido eliminadas.";
    }
    else {
        $msg->respuesta = "Ese alumno no cuenta con calificaciones.";
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/singup', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $consul = DB::table('data_usuarios')->where('usuario', $data->user)->first();

    $msg = new stdClass();

    if($consul)
    {
        $msg->respuesta = "El usuario ya existe, escoja otro";
    }
    else
    {
        $insert = DB::table('data_usuarios')->insertGetId(
            ['usuario' => $data->user, 'password' => $data->pass, 'tipo_usuario' => 'alumno', 'nombre' => $data->nombre, 'apellidos'=> $data->apellido]
        );

        if($insert)
        {
            $msg->respuesta = "Registro exitoso.";
        }
        else {
            $msg->respuesta = "Algo ha ido mal, intenta de nuevo.";
        }
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});

$app->post('/calif', function (Request $request, Response $response, array $args) {

    $data = json_decode($request->getBody()->getContents(), false);

    $users = DB::table('data_materias')->where('data_usuarios_id_data_usuarios', $data->id)->first();

    $msg = new stdClass();

    if($users)
    {
        $msg->user = true;
        $msg->calc = $users->calculo;
        $msg->fran = $users->frances;
        $msg->fis = $users->fisica;
        $msg->prom = ($users->calculo+ $users->frances+ $users->fisica)/3;
    }
    else
    {
        $msg->user = false;
        $msg->respuesta = "No tienes calificaciones";
    }

    $response->getBody()->write(json_encode($msg));
    return $response;
});
// Run application
$app->run();