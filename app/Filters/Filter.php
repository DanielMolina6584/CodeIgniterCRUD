<?php

namespace App\Filters;

use App\Services\UserService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Filter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = new UserService;
        // $tokenSession = session()->get("token");
        $token = $request->getHeaderLine('token');
        $tokenDataBase = $user->validateToken($token);
       
        var_dump($tokenDataBase);
        if (!$tokenDataBase ) {     
            session()->destroy();
           
            $errors = 'Token Invalido';
            $errorMessages = [];
            $errorMessages[] = $errors;
            
                echo json_encode([
                    "error"  => 'NO AUTORIZADO',
                    "errors" => $errorMessages,                    
                ]);
            die;
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }


}