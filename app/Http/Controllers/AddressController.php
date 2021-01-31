<?php

namespace App\Http\Controllers;


use App\Address;
use Illuminate\Http\Request;

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
                return response()->json($response, $response['code']);
            } else {
             

                //crear usuario
                $json = [
                    'status' => 'Created',
                    'code' => 201,
                    'message' => 'User created'
                ];

                $response = Address::create($params_array);
                return $response;
            }
        } else {
            $response = [
                'status' => 'fails',
                'code' => 400,
                'message' => "Address no creado",
            ];
            return response()->json($response, $response['code']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
