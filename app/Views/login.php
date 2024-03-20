<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body>
    <div>
        <div class="col-md-6 offset-md-3">
            <h1>Registrate</h1>
            <form id="LoginForm">

                <div class="form-group">
                    <label for="nombre">Email:</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="<?php echo set_value('email'); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido">Password:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        value="<?php echo set_value('password'); ?>"> <br>
                </div>


                <button type="submit" class="btn btn-primary" id="btnRegister">Registrar</button>
            </form>

            <br>


            <!-- Recuperar los datos del username y de la password, validar si son correctos y mostrar mensaje dependiendo de la respuesta(recordatorio) -->
            <h1>Inicia sesion con tu cuenta</h1>
            <form id="IniciarForm">
                <div class="form-group">
                    <label for="nombre">Email:</label>
                    <input type="type" class="form-control" id="email" name="email"
                        value="<?php echo set_value('email'); ?>">
                </div>

                <div class="form-group">
                    <label for="apellido">Password:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        value="<?php echo set_value('password'); ?>"> <br>
                </div>

                <button type="submit" class="btn btn-success" id="btnLogin">Iniciar Sesion</button>
            </form>


        </div>

</body>

</html>
<script>
    /*Register*/
    jQuery('#btnRegister').on("click", function (event) {
        event.preventDefault();

        let form = document.getElementById('LoginForm')
        const formData = new FormData(form);
        axios.post('<?= base_url('logged') ?>', formData, {

        })

            .then(response => {
                if (response.data.errors) {

                    console.log(response)
                    const errorMessages = Object.values(response.data.errors).join('<br>');

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: errorMessages
                    });

                } else {


                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Tu usuario ha sido añadido",
                        showConfirmButton: false,
                        timer: 1500

                    });
                    setTimeout(function () {
                        $('#LoginForm').trigger("reset")
                    }, 1500)
                }
            });
    });
    /*Iniciar Sesion*/
    jQuery('#btnLogin').on("click", function (event) {
        event.preventDefault();

        let form = document.getElementById('IniciarForm')
        const formData = new FormData(form);
        axios.post('<?= base_url('iniciar') ?>', formData, {

        })

            .then(response => {
                if (response.data.errors) {

                    const errorMessages = Object.values(response.data.errors).join('<br>');

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        html: errorMessages
                    });

                } else if (response.data.success) {
                    const token = response.data.token
                    localStorage.setItem('token', token)
                    console.log(token)
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Tu usuario es correcto",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function () {
                        location.href = '<?= base_url('admin/usuario') ?>'
                    }, 1000)

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Datos Incorrectos."

                    });
                }
            });
    });

</script>