<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function store(Request $req){
        $info = $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
        ]);

        Setting::create($info);

        return redirect()->route('setting.create');
    }

    public function edit(Setting $setting){
        return view('settings.edit', [
            'setting' => $setting
        ]);
    }

    public function update(Request $req, Setting $setting){
        $info = $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
        ]);

        $lojaUpdate->update($info);

        return redirect()->route('settings.create');
    }

    public function create(Setting $setting){
        $setting = Setting::first();
        
        return view('settings.create', [
            'setting' => $setting
        ]);
    }
}
