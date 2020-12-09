<?php
require_once 'functions.php';
use Illuminate\Database\Capsule\Manager as DB;
echo'

<body class="bg-img"> 
<div class="container">
';

if(!$loggedin){
    echo'
        <div class="card is-white">
        <figure class="image is-2by1">
        <img src="img/logoalta3.png">
        
            </figure>
            <header class="card-header">
            
                <p class="card-header-title">
                    Ingresa tus datos para iniciar sesión
                </p>
                <a href="#" class="card-header-icon" aria-label="more options">
                    <span class="icon">
                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content footer">
                <div class="content">
                    <form method="post" action="login.php">
                        <div class="field">
                            <label class="label">Usuario</label>
                            <div class="control">
                                <input class="input" type="text" maxlength="45" id="usuario" placeholder="Usuario">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Contraseña</label>
                            <div class="control">
                                <input class="input" type="password" maxlength="11" id="contraseña" placeholder="Contraseña">
                            </div>
                        </div>
                        <button type="button" onclick="login()" class="button is-medium is-warning">Iniciar sesión</button>
                    </form>
                </div>
                <br>
                <br>
                <br>
                <br>
            </div footer>
        </div>
    </div>
    ';
}
else{

}
echo'
<script>
        function login()
        {
            axios.post(`api/index.php/login`, {
                user: document.forms[0].usuario.value,
                pass: document.forms[0].contraseña.value
            })
            .then(resp => {
                if(resp.data.rsp)
                {
                    alert("Iniciaste sesion.");
                    location.href=`api/index.php/login/${resp.data.user}`;

                }    
                else
                {
                    alert("Usuario y/o contraseña son incorrectos.");

                }

            })
            .catch(error => {
                console.log(error);
            });
        }
    </script>


</body>

';
