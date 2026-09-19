<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // mostra todos os endereços cadastrados
    public function index()
    {
        // me traga todos os endereços desse usuario
        $addresses = Address::where('user_id', auth()->id())->get();

        return view('addresses.index', [
            'addresses' => $addresses,
        ]);
    }

    public function create()
    {
        return view('addresses.create');
    }

    // cria um endereço no banco
    public function store(Request $req)
    {
        $data = $req->validate([
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        // verifica se o usuario está logado
        $data['user_id'] = auth()->id();

        $address = Address::create($data);

        return redirect()->route('addresses.index');
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', [
            'address' => $address,
        ]);
    }

    // atualiza o endereço
    public function update(Address $address, Request $req)
    {
        $data = $req->validate([
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        $address->update($data);

        return redirect()->route('addresses.index', [
            'address' => $address,
        ]);
    }

    // exclui o endereço
    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->route('addresses.index');
    }
}
