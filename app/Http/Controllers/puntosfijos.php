<?php

namespace App\Http\Controllers;
use DB;
use App\registro;
use App\userlevels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class puntosfijos extends Controller
{
    public function solounaves(){
        $levelUSR = Auth::user()->profile;
        $lv = userlevels::find($levelUSR);
        $query = DB::table('municipio')->select('idmunicipio','nombreM')->get();
        return view('puntosfijos.onlyone',[
            'level' => $lv,
            'Municipios' => []
        ]);
    }
    public function Municipios(Request $request){
        $query = DB::table('municipio')->select('idmunicipio','nombreM')->get();
        return response()->json([
            'Municipios' => view('selects.municipios',compact('query'))->render()
        ]);
    }
    public function Localidades(Request $request){
        $query = DB::table('localidades')->select('idlocalidades','nombreL')->where('idmunicipio','=',"$request->idmunicipio")->get();
        return response()->json([
            'Localidades' => view('selects.localidades',compact('query'))->render()
        ]);
    }
    public function Direccion(Request $request){
        $query = DB::table('registro')->select('idregistro','folio','domicilio')->where('idlocalidades','=',"$request->idlocalidades")->where('status','=','0')->whereRaw("yearweek(f,1) = YEARWEEK(current_date,1)")->get();
        return response()->json([
            'Direcciones' => view('selects.direccion',compact('query'))->render()
        ]);
    }
    public function SaveSolounavez(Request $request){
        $update=[
            'fecha' => $request->Fecha,
            'hora' => $request->Hora,
            'valor' => $request->Valor,
            'sin_servicio' => $request->has('Servicio')? 1 : 0,
            'causas' => $request->Causas,
            'acciones' => $request->Acciones,
            'muestra' => $request->has('Bacteriologico')? 1: 0,
            'user_id' => Auth::user()->id,
            'status' => 1
        ];
        if(DB::table('registro')->where('idregistro',$request->Direccion)->update($update)){
            return response()->json([
                'message' => 'Se guardó correctamente'
            ]);
        }else{
            return response()->json([
                'message' => 'No hay nada que actualizar'
            ]);
        }
    }

    public function MoreThanOnce(Request $request){
        $Registro = new registro;
        $levelUSR = Auth::user()->profile;
        $lv = userlevels::find($levelUSR);
        return view('puntosfijos.morethanonce',[
            'level' => $lv,
            'Registros' => $Registro->MoreThanOnce()
        ]);
    }
    public function SaveMoreThanOnce(Request $request){
        foreach ($request->all() as $R) {
            $toUpdate =[
                'fecha' => $R['Fecha'],
                'hora' => $R['Hora'],
                'valor' => $R['Valor'],
                'sin_servicio' => array_key_exists('SinServicio',$R) ? 1 : 0,
                'causas' => $R['Causas'],
                'acciones' => $R['Acciones'],
                'muestra' => array_key_exists('MuestraBacteriologica', $R) ? 1 : 0,
                'user_id' => Auth::user()->id,
                'status' => 1
            ];
            $R['Fecha']? registro::where('idregistro',$R['idregistro'])->update($toUpdate):'';
        }
        return response()->json([
            'message' => 'Se guardaron los registros'
        ]);
    }
}