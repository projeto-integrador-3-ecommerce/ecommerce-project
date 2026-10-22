<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'cnpj' => ['required', 'string', 'max:18'],
            'email' => ['required', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
        ]);

        $setting = Setting::query()->orderBy('id')->first();

        if ($setting === null) {
            Setting::create($data);
        } else {
            $setting->update($data);
        }

        return redirect()->route('setting.create');
    }

    public function edit(Setting $setting): View
    {
        return view('settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'cnpj' => ['required', 'string', 'max:18'],
            'email' => ['required', 'email', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
        ]);

        $setting->update($data);

        return redirect()->route('setting.create');
    }

    public function create(): View
    {
        $setting = Setting::query()->orderBy('id')->first();

        return view('settings.create', [
            'setting' => $setting,
        ]);
    }
}
