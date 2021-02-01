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

        $jwt = new JwtAuth();
        $email = 'lesly317@8gmail.com';
        $password = '123456';


        return response()->json($jwt->signup($email, $password, true), 200);
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
    public function update(Request $request, $id)
    {
        //$flight = User::update('update users set votes = 100 where name = ?', ['John']);
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
