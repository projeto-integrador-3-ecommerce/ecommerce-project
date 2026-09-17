<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return view('addresses.index', [
            'addresses' => $addresses
        ]);
    }

    public function create(){
        return view('addresses.create');
    }

    public function store(Request $req, Address $address){
        $data = $req->validate([
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'complement' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:255'],
        ]);

        $address = Address::create($data);

        return redirect()->route('addresses.index');
    }
}