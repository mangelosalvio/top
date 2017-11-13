<?php

namespace App\Http\Controllers;

use App\Bank;
use App\ChartOfAccount;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class BanksController extends Controller
{
    public function __construct(){
        $route = "banks";
        $this->route = $route;
        $this->arr_rules  = [
            'bank_desc' => 'required',
            'chart_of_account_id' => 'required'
        ];

        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','id');

        $search_data = [ 'search_url' => str_replace("_","-",$this->route) ];

        return view()->share(compact(['search_data','route','chart_of_accounts']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        if ( $keyword ) {
            $banks = Bank::where('class_code','like',"%$keyword%")
                ->orWhere('class_desc','like',"%$keyword%")
                ->paginate();
        } else {
            $banks = Bank::paginate();
        }


        return view("{$this->route}.search_{$this->route}",compact([
            'banks'
        ]));
    }

    public function create(){

        return view("{$this->route}.{$this->route}",compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $Bank = Bank::create($request->all());

        return Redirect::to("/".str_replace("_","-",$this->route)."/{$Bank->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Bank $bank){

        return view("{$this->route}.{$this->route}", compact([
            'bank'
        ]));

    }

    public function update(Request $request, Bank $bank){

        $this->validate($request,$this->arr_rules);
        $bank->update($request->all());
        return Redirect::to("/".str_replace("_","-",$this->route)."/{$bank->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Bank $bank){
        $bank->delete();
        return Redirect::to("/".str_replace("_","-",$this->route))->with('flash_message','Collateral Class Deleted');
    }
}
