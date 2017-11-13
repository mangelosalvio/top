<?php

namespace App\Http\Controllers;

use App\CollateralClass;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class CollateralClassesController extends Controller
{
    public function __construct(){
        $this->route = "collateral_classes";
        $this->arr_rules  = [
            'class_code' => 'required',
            'class_desc' => 'required'
        ];

        $search_data = [ 'search_url' => str_replace("_","-",$this->route) ];

        return view()->share(compact(['search_data']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $collateral_classes = CollateralClass::where('class_code','like',"%$keyword%")
                ->orWhere('class_desc','like',"%$keyword%")
                ->paginate();
        } else {
            $collateral_classes = CollateralClass::paginate();
        }


        return view("{$this->route}.search_{$this->route}",compact([
            'collateral_classes'
        ]));
    }

    public function create(){

        return view("{$this->route}.{$this->route}",compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $CollateralClass = CollateralClass::create($request->all());

        return Redirect::to("/".str_replace("_","-",$this->route)."/{$CollateralClass->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(CollateralClass $collateral_class){

        return view("{$this->route}.{$this->route}", compact([
            'collateral_class'
        ]));

    }

    public function update(Request $request, CollateralClass $collateral_class){

        $this->validate($request,$this->arr_rules);
        $collateral_class->update($request->all());
        return Redirect::to("/".str_replace("_","-",$this->route)."/{$collateral_class->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(CollateralClass $collateral_class){
        $collateral_class->delete();
        return Redirect::to("/".str_replace("_","-",$this->route))->with('flash_message','Collateral Class Deleted');
    }
}
