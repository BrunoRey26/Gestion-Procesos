<?php

namespace it\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class MiscController extends Controller
{
	/*
		Retorna un listado  con los elementos correspondiente a un grupo especifico.
	*/

    public function getMiscGroup(Request $request){

    	if (is_numeric(addslashes($request->input('group')))) {

	    	$tiposOrigen = DB::table('misc')->where('group', $request->input('group'))->get();
	    	if (count($tiposOrigen) > 0) {
		    	return $tiposOrigen;
	    	}
    	}
    }

    /*
		Retorna un objeto con un unico elemento, basandose en el ID.
    */

    public function getMiscByID(Request $request){
    	if (is_numeric(addslashes($request->input('id')))) {

	    	$tiposOrigen = DB::table('misc')->where('id', $request->input('id'))->get();
	    	//if (count($tiposOrigen) > 0) {
		    	return $tiposOrigen;
	    	//}
    	}
    }

}
