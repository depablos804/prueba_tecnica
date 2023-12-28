<?php

namespace App\Http\Controllers;

use App\Events\NewReservation;
use App\Models\request as ModelsRequest;
use App\Models\Reservation;
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
                'dateinit' => 'required|date'
                //'dateout' => 'required|date',

            ]);
            if ($validator->fails()) {
                return json_decode($validator->errors());
            }

            $data= json_decode($request->getContent());
            $newrequest=$this->saveRequest($data);
            if($newrequest==0){// guardar solicitud
                return response()->json('Disculpe no pudimos procesar su solicitud');
            }
            //** evento  */
           $modelrequest= ModelsRequest::find($newrequest);
           event(new NewReservation($modelrequest));
           return response()->json( $info);
        } catch (\Exception $e) {
            return json_decode($e);
        }

    }

    /** Funcion  Guardar solicitud de reserva
     *  response type bool
     */
    public function saveRequest($data){

        $request2= new  ModelsRequest();
        $request2->name=$data->name;
        $request2->email=$data->email;
        $request2->phone=$data->phone;
        $request2->dni=$data->dni;
        $request2->dateinit=$data->dateinit;
        //$request2->dateout=$data->dateout;
        if(!$request2->save()){// guardar solicitud
            return  0; //response()->json('Disculpe no pudimos procesar su solicitud');
        }else {
            return $request2->id;

        }
    }

}
