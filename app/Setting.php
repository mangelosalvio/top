<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function scopeAccount($query, $setting_key){
        $Setting = $query->whereSettingKey($setting_key)->first();
        return ChartOfAccount::whereAccountCode($Setting->setting_value)->first();
    }
}
