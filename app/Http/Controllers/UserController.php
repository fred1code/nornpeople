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
                'name' => 'required|string',
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
            } else {

                $params_array['password'] = bcrypt($params_array['password']);
                //crear usuario
                $params_array = array_merge(['enable' => 1], $params_array);
                $resp = User::create($params_array);
                $response = [
                    'status' => 'create',
                    'code' => 201,
                    'message' => $resp,
                ];
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Usurio no creado",
            ];
        }
        return response()->json($response, $response['code']);
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
                'message' => "datos incorrectos",
                'array' => $params_array
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
    public function list(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {

            $all = User::all(['id', 'name', 'email', 'birthday', 'phone', 'sex', 'profile']);
            $data = [
                'status' => 'success',
                'code' => 200,
                'body' => $all,
            ];
        } else {
            $data = [
                'status' => 'fail',
                'code' => 401,
                'message' => "Permiso Denegado",
            ];
        }


        return response()->json($data, $data['code']);
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
        //recojer los datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            //usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            // validar datos
            if (!empty($params_array)) {
                $validate = \Validator::make($params_array, [
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email,' . $user->sub,
                    'birthday' => 'required',
                    'phone' => 'required',
                    'sex' => 'required|alpha',
                    'profile' => 'required|alpha',
                ]);

                if ($validate->fails()) {
                    $data = [
                        'status' => 'fail',
                        'code' => 400,
                        'message' => "Usurio no creado",
                        'errors' => $validate->errors()
                    ];
                } else {
                    // quitar los campos
                    unset($params_array['id']);
                    unset($params_array['profile']);
                    unset($params_array['password']);
                    unset($params_array['email']);
                    unset($params_array['enable']);

                    //actulisar

                    $update_user = User::where('id', $user->sub)->update($params_array);

                    //devolver el array
                    $data = [
                        'status' => 'success',
                        'code' => 200,
                        'message' => $update_user,
                    ];
                }
            } else {
                $data = [
                    'status' => 'fail',
                    'code' => 401,
                    'message' => "Usuario no identificado",
                ];
            }
        } else {
            $data = [
                'status' => 'fail',
                'code' => 401,
                'message' => "Usuario no identificado",
            ];
        }
        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            $user = $jwtAuth->checkToken($token, true);
            $update_user = User::where('id', $user->sub)->update(['enable' => 0]);

            //devolver el array
            $data = [
                'status' => 'success',
                'code' => 200,
                'message' => $update_user,
            ];
        } else {
            $data = [
                'status' => 'fail',
                'code' => 401,
                'message' => "Usuario no identificado",
            ];
        }
        return response()->json($data, $data['code']);
    }
}
