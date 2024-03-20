<?php
namespace App\Controllers;

use App\Services\UserService;
use App\Models\LoginModel;




class Login extends BaseController
{
    protected $userService;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->userService = new UserService();
        $this->loginModel = new LoginModel();
        $session = session();
    }

    public function index()
    {
        return view("login");
    }

    /*REGISTER*/
    public function login()
    {
        /*Obtiene el json de la peticion de axios del formdata*/
        $jsonData = $this->request->getPost();
        $password = $jsonData['password'];

        /*Encripta la password*/
        $hash = password_hash($password, PASSWORD_DEFAULT);

        /*Reglas requeridas para el data*/
        $validationRules = [
            'email' => 'required|max_length[30]',
            'password' => 'required|max_length[60]|min_length[8]'

        ];

        //Guarda en Base de Datos
        if ($this->validate($validationRules)) {

            /*Generador de token y guarda el hash en la base de datos*/
            $jsonData['token'] = bin2hex(random_bytes(16));
            $jsonData['password'] = $hash;

            /*Agrega a base de datos*/
            $this->userService->LoginAgregar($jsonData);

            return $this->response->setStatusCode(200)->setJSON($jsonData);

        } else {
            /*Valida si hay errores y los manda al response*/
            $errors = $this->validator->getErrors();
            $errorMessages = [];


            foreach ($errors as $message) {
                $errorMessages[] = $message;
            }

            return $this->response->setJSON([
                'error' => 'Los datos no son válidos',
                'errors' => $errorMessages,
            ]);
        }
    }
    /*LOGIN*/
    public function IniciarSesion()
    {
        $user = new UserService();
        $jsonData = $this->request->getPost();

        $email = $jsonData['email'];
        $password = $jsonData['password'];

        /*Se crea una session llamada isLoggedIn, se pone false ya que no estara loggeado aun*/
        session()->set('isLoggedIn', false);


        $validationRules = [
            'email' => 'required|max_length[30]',
            'password' => 'required|max_length[60]|min_length[8]'
        ];

        if ($this->validate($validationRules)) {
            /*Verifica el email correspondiente trayendo la info*/
            $result = $user->IniciarSesion($email, $password);

            /*Verifica la password si es correcta*/ /*si lo es setea el logged true*/
            if (!empty($result) && password_verify($password, $result['password'])) {
                session()->set('isLoggedIn', true);

                /*Sesion del token(se puede pasar asi)*/
                session()->set('token', $result['token']);

                return $this->response->setStatusCode(200)->setJSON([
                    'token' => $result['token'],
                    'success' => true,
                    'message' => 'Usuarios Correctos'
                ]);


            } else {
                return $this->response->setStatusCode(200)->setJSON($jsonData);
            }

        } else {
            $errors = $this->validator->getErrors();
            $errorMessages = [];

            foreach ($errors as $message) {
                $errorMessages[] = $message;
            }

            return $this->response->setJSON([
                'error' => 'Los datos no son válidos',
                'errors' => $errorMessages,
            ]);
        }
    }

    public function logOut()
    {
        /*Destruye la session*/
        session()->destroy();
        return redirect()->to(base_url('login'));
    }



}