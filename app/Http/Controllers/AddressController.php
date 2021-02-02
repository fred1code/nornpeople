<?php

namespace App\Http\Controllers;


use App\Address;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\User;

class AddressController extends Controller
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
                'user_id' => 'required|numeric',
                'del' => 'required',
                'col' => 'required',
                'numIn' => 'required',
                'street' => 'required',
            ]);
            if ($validate->fails()) {
                $response = [
                    'status' => 'fails',
                    'code' => 400,
                    'message' => "Usurio no creado",
                    'errors' => $validate->errors()
                ];
            } else {


                //crear usuario
                $params_array = array_merge(['enable' => 1], $params_array);
                $responses = Address::create($params_array);
                $response = [
                    'status' => 'Created',
                    'code' => 201,
                    'message' => $responses
                ];
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Address no creado",
            ];
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
      

            $all = Address::all(['id', 'user_id', 'del', 'col', 'numIn', 'numEx', 'street']);
            $data = [
                'status' => 'success',
                'code' => 200,
                'body' => $all,
            ];
 


        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userAddress(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            $checkToken = $jwtAuth->checkToken($token, true);
            //  $update_address = Address::where('id_user', $checkToken->sub)->first();;
            $t = address::with('user')
                ->whereId($checkToken->sub)
                ->first();

            $data = [
                'status' => 'success',
                'code' => 200,
                'body' => $t,
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //recojer datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);


        if (!empty($params_array)) {

            ///validar datos
            $validate = \Validator::make($params_array, [
                'user_id' => 'required|numeric',
                'del' => 'required',
                'col' => 'required',
                'numIn' => 'required',
                'street' => 'required',
            ]);
            if ($validate->fails()) {
                $response = [
                    'status' => 'fails',
                    'code' => 400,
                    'message' => "Usurio no creado",
                    'errors' => $validate->errors()
                ];
            } else {

                unset($params_array['enable']);

                $t =  Address::where('id', $params_array['id'])
                    ->where('user_id', $params_array['user_id'])
                    ->update($params_array);

                $response = [
                    'status' => 'update',
                    'code' => 201,
                    'message' => $t
                ];
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Address no creado",
            ];
        }
        return response()->json($response, $response['code']);
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
            $update_user = Address::where('id', $user->sub)->update(['enable' => 0]);

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
