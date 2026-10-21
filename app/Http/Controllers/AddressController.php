<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AddressController extends Controller
{
    // mostra todos os endereços cadastrados
    public function index(Request $request): View
    {
        $query = Address::query()->with('user')->latest();

        if (! $request->user()->is_admin) {
            $query->where('user_id', $request->user()->id);
        }

        $addresses = $query->paginate(20);

        return view('addresses.index', [
            'addresses' => $addresses,
        ]);
    }

    public function selectForOrder(Order $order): View
    {
        Gate::authorize('addAddress', $order);

        $addresses = Address::where('user_id', auth()->id())->get();

        return view('orders.addresses', compact('addresses', 'order'));
    }

    // retorna view com o forms de criar endereço
    public function create(Request $request): View
    {
        $order = null;

        if ($request->filled('order_id')) {
            $order = Order::findOrFail($request->integer('order_id'));
            Gate::authorize('addAddress', $order);
        }

        return view('addresses.create', compact('order'));
    }

    // cria um endereço no banco
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'cep' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        $order = null;
        if (! empty($data['order_id'])) {
            $order = Order::findOrFail($data['order_id']);
            Gate::authorize('addAddress', $order);
        }

        // verifica se o usuario está logado
        $data['user_id'] = $request->user()->id;
        unset($data['order_id']);

        Address::create($data);

        if ($order) {
            return redirect()->route('orders.addresses', $order)->with('success', 'Endereço cadastrado. Selecione-o para continuar o pedido.');
        }

        return redirect()->route('addresses.index');
    }

    // retorna view com o forms de editar endereço
    public function edit(Address $address): View
    {
        Gate::authorize('view', $address);

        return view('addresses.edit', [
            'address' => $address,
        ]);
    }

    // atualiza o endereço
    public function update(Address $address, Request $request): RedirectResponse
    {
        Gate::authorize('update', $address);

        $data = $request->validate([
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

        return redirect()->route('addresses.index');
    }

    // exclui o endereço
    public function destroy(Address $address): RedirectResponse
    {
        Gate::authorize('delete', $address);

        $address->delete();

        return redirect()->route('addresses.index');
    }
}
