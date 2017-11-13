<?php

namespace App\Http\Controllers;

use App\ChartOfAccount;
use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function __construct(){

        $chart_of_accounts = ChartOfAccount::orderBy('account_code')->get()->pluck('label','account_code');

        view()->share(compact(['chart_of_accounts']));
    }

    public function index(Request $request)
    {
        $settings = Setting::all();

        return view('settings.settings',compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $ids           = $input['setting_id'];
        $setting_value = $input['setting_value'];

        foreach( $ids as $i => $id ) {
            $Setting                = Setting::find($id);
            $Setting->setting_value = $setting_value[$i];
            $Setting->save();
        }

        return Redirect::to("settings")->with('flash_message',"Information Saved");
    }
}
