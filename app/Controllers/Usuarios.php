<?php
namespace App\Controllers;

use App\Services\UserService;

class Usuarios extends BaseController
{

    protected $helpers = ['form'];

    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
        helper('filesystem');
    }
    

    public function index()
    {
        $datos = $this->userService->listarUsuarios();
        $data = ["datos" => $datos];
        return view('usuario', $data);
    }
    public function listarUsuarios(){
        $datos = $this->userService->listarUsuarios();
        return $this->response->setJSON($datos);
    }

    public function crear()
    {
        $jsonData = $this->request->getPost();

        $validationRules = [
            'apellido' => 'required|max_length[60]',
            'nombre' => 'required|max_length[30]',
            'email' => 'required|valid_email',
            'cel' => 'required|numeric',
            'image' => 'uploaded[image]'
        ];


        //Guarda en Base de Datos
        if ($this->validate($validationRules)) {

            $img =  $this->request->getFile('image');
            if ($img->isValid() &&!$img->hasMoved()) {
                $imageName = $img->getName();
                    write_file(FCPATH .'uploads/' . $imageName, file_get_contents($img));

                $imageUrl ='uploads/' . $imageName;
                
                var_dump(''. $imageUrl .'');
            $jsonData['image'] = $imageUrl;
            
        }
            $newUserId = $this->userService->agregarUsuario($jsonData);

            $jsonData['id'] = $newUserId;

            return $this->response->setStatusCode(200)->setJSON($jsonData);

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


    public function actualizar()
    {
        $jsonData = $this->request->getPost();

        $validationRules = [
            'id' => 'required|numeric',
            'nombre' => 'required|max_length[30]',
            'apellido' => 'required|max_length[60]',
            'email' => 'required|valid_email',
            'cel' => 'required|numeric',
            'image' => 'permit_empty'
        ];


        // Actualiza en Base de Datos
        if ($this->validate($validationRules)) {

            $img = $this->request->getFile('image');
            if ($img->isValid() && !$img->hasMoved()) {
                $imageName = $img->getName();
                write_file(FCPATH . 'uploads/' . $imageName, file_get_contents($img));
    
                $imageUrl = 'uploads/' . $imageName;
                var_dump(''. $imageUrl .'');

                $jsonData['image'] = $imageUrl;
            }
                $this->userService->actualizarUsuario($jsonData["id"], $jsonData);
            return $this->response->setStatusCode(200);

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

    public function ObtenerId()
    {
        $request = request();
        $id = $request->getGet('id');
        $datos = $this->userService->obtenerUsuarioPorId($id);

        return view('actualizar', ['datos' => $datos]);
    }
    public function ListarId()
    {
        $request = request();
        $id = $request->getGet('id');
        $datos = $this->userService->obtenerUsuarioPorId($id);

        return $this->response->setJSON($datos);
    }


    public function eliminar()
    {
        $request = request();
        $id = $request->getGet('id');
        
        if ($this->userService->eliminarUsuario($id)) {
            return $this->response->setStatusCode(200);
        } else {
            return $this->response->setStatusCode(500);
        }
    }

    public function validarToken(){
        $request = request();
        $token = $request->getPost('token');
        $tok = $this->userService->validateToken($token);

        if($tok) {
            return $this->response->setJSON([
                'status' => $tok,
            ]);
        }else{
            return $this->response->setJSON([
                'status'=> $tok,
            ]);
        }
    }


}





