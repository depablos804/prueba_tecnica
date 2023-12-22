<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class reservationController extends Controller
{
    /** prueba conexion */
    public function mostrar(Request $request){
        $a=Room::all();
        return response()->json($a);
    }

    public function reserveRequest(Request $request){
        $info="Por favor Aguarde Informacion Enviada se confirmara por Email";
        try {
            $validator= Validator::make($request->all(),[
                'email'=>'required|email',
                'name' => 'required',
                'dni' => 'required|numeric',
                'phone' => 'required',
                'dateinit' => 'required|date',
                'dateout' => 'required|date',

            ]);
            if ($validator->fails()) {
                return json_decode($validator->errors());
            }
            $request->validate([]);
            $data= json_decode($request->getContent());
            return response()->json( $info);

        } catch (\Exception $e) {
            return json_decode($e);
        }

    }

}
