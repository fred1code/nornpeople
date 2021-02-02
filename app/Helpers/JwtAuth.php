<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use Namshi\JOSE\Signer\OpenSSL\HS256;
use PhpParser\Node\Stmt\TryCatch;

class JwtAuth
{

    public $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET');
    }

    public function signup($email, $password, $getToken = null)
    {
        $user = User::where([
            'email' => $email
        ])->first();

        if (Hash::check($password,  $user->password)) {

            $singup = false;
            if (is_object($user)) {
                $singup = true;
            }
            if ($singup) {
                $token = [
                    'sub' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'iat' => time(),
                    'exp' => time() + (10 * 60)
                ];

                $jwt = JWT::encode($token, $this->key, 'HS256');
                $decode = JWT::decode($jwt, $this->key, ['HS256']);
                if (is_null($getToken)) {
                    $data = $jwt;
                } else {
                    $data = $decode;
                }
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Login incorrecto'
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Usurio o password invalido'
            ];
        }
        return $data;
    }

    public function checkToken($jwt, $getIdentity = false)
    {
        $auth = false;
        try {
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decoded;
        }


        return $auth;
    }
}
