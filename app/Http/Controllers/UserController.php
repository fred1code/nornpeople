<?php

namespace App\Http\Controllers;

use App\Helpers\JwtAuth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //recojer datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array)) {
            ///validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'birthday' => 'required',
                'phone' => 'required',
                'sex' => 'required|alpha',
                'profile' => 'required|alpha',
                'password' => 'required',
            ]);
            if ($validate->fails()) {
                $response = [
                    'status' => 'fails',
                    'code' => 400,
                    'message' => "Usurio no creado",
                    'errors' => $validate->errors()
                ];
                return response()->json($response, $response['code']);
            } else {

                $params_array['password'] = bcrypt($params_array['password']);
                //crear usuario

                return  User::create($params_array);
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Usurio no creado",
            ];
            return response()->json($response, $response['code']);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //recibir datos 
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        //validar datos

        if (!empty($params_array)) {
            $validate = \Validator::make($params_array, [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validate->fails()) {
                $response = [
                    'status' => 'fails',
                    'code' => 400,
                    'message' => "Email o Password incorrecta",
                ];
            } else {
                $jwt = new JwtAuth();
                $response = response()->json($jwt->signup($params_array['email'], $params_array['password']), 200);
                if (!empty($params_array['getToken'])) {
                    $response = response()->json($jwt->signup($params_array['email'], $params_array['password'], true), 200);
                }
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "error",
            ];
            $response = response()->json($response, $response['code']);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            //actulisar el usuario
        } else {
            echo "error";
        }
        die();


        /*   //recojer datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if (!empty($params_array)) {
            ///validar datos
            $validate = \Validator::make($params_array, [
                'name' => 'required|alpha',
                'email' => 'required|email|unique:users',
                'birthday' => 'required',
                'phone' => 'required',
                'sex' => 'required|alpha',
                'profile' => 'required|alpha',
                'password' => 'required',
            ]);
            if ($validate->fails()) {
                $response = [
                    'status' => 'fails',
                    'code' => 400,
                    'message' => "Usurio no creado",
                    'errors' => $validate->errors()
                ];
                return response()->json($response, $response['code']);
            } else {

                $params_array['password'] = bcrypt($params_array['password']);
                //crear usuario

               // return  User::u($params_array);
                user::update('update users set votes = 100 where name = ?', ['John']);

            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Usurio no creado",
            ];
            return response()->json($response, $response['code']);
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
