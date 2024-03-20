<?php
namespace App\Services;

use App\Models\UserModel;
use App\Models\LoginModel;

class UserService
{
    protected $userModel;
    protected $loginModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->loginModel = new LoginModel();
    }

    public function listarUsuarios()
    {
        return $this->userModel->findAll();
    }

    public function agregarUsuario($data)
    {
        return $this->userModel->insert($data);
    }

    public function actualizarUsuario($id, $data)
    {
        return $this->userModel->update($id, $data);
    }

    public function obtenerUsuarioPorId($id)
    {
        return $this->userModel->select('id, nombre, apellido, email, cel')
            ->where('id', $id)->first();
    }

    public function eliminarUsuario($id)
    {
        return $this->userModel->where('id', $id)->delete();
    }

/*Login*/
    public function LoginAgregar($data)
    {
        return $this->loginModel->insert($data);
    }   
    public function IniciarSesion($email, $password){
        return $this->loginModel->where('email' , $email)->first();
    }

/*Token*/
    public function validateToken($token){
    {
       $tok =  $this->loginModel->where('token', $token)->first();
        if(!empty($tok)){
            
            return true;
        }
        else{
            return false;
        }
    }
    
} 

}