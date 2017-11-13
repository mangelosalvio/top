<?php

namespace App\Http\Controllers;

use App\Collateral;
use App\CollateralClass;
use App\Customer;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CollateralsREMController extends Controller
{
    public $arr_rules;

    /**
     *
     */
    public function __construct(){

        $this->arr_rules  = [
            'collateral_class_id' => 'required',
            'customer_id' => 'required',
            'date' => 'required|date',
            'appraised_date' => 'date',
            'date_issued' => 'date',

            'land_market_value' => 'numeric',
            'land_appraised_value' => 'numeric',

            'building_market_value' => 'numeric',
            'building_appraised_value' => 'numeric',

            'other_improvements_market_value' => 'numeric',
            'other_improvements_appraised_value' => 'numeric',

            'total_market_value' => 'numeric',
            'total_appraised_value' => 'numeric',

            'bir_zonal_value' => 'numeric',
            'appraisers_association_value' => 'numeric',
            'market_value_of_neighborhood' => 'numeric',
            'reproduction_cost_of_building' => 'numeric',
            'assessed_value' => 'numeric',

        ];

        $search_data = [ 'search_url' => 'collaterals-rem' ];

        $customers = Customer::all()->pluck('name','id');
        $collateral_classes = CollateralClass::select(DB::raw("id, concat(class_code,'-',class_desc) name"))->get()->pluck('name','id');

        return view()->share(compact(['search_data','customers','collateral_classes']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $collaterals = Collateral::whereHas('collateralClass', function($query) use ($keyword){
                $query->orWhere('class_code','like',"%$keyword%");
            })->where('collateral_type','REM')
                ->paginate();
        } else {
            $collaterals = Collateral::whereCollateralType('REM')->paginate();
        }


        return view('collaterals.search_collaterals_rem',compact([
            'collaterals'
        ]));
    }

    public function create(){
        return view('collaterals.collaterals_rem',compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $Collateral = Collateral::create($request->all());
        $Collateral->collateral_type = "REM";
        $Collateral->save();

        return Redirect::to("/collaterals-rem/{$Collateral->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Collateral $collaterals_rem){
        $collateral = $collaterals_rem;
        return view('collaterals.collaterals_rem', compact([
            'collateral'
        ]));

    }

    public function update(Request $request, Collateral $collaterals_rem){
        $collateral = $collaterals_rem;
        $this->validate($request,$this->arr_rules);

        $collateral->update($request->all());
        return Redirect::to("/collaterals-rem/{$collateral->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Collateral $collateral){
        $collateral->delete();
        return Redirect::to("/collaterals-rem")->with('flash_message','Collateral Deleted');
    }
}
