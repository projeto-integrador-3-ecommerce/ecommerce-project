@extends('layouts.app')

@section('content')

    <h1>Editar Endereço</h1>

    <form action="{{ route('addresses.update', $address) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>CEP:</label>
            <input type="text" name="cep" id="cep" value="{{ $address->cep }}" required>
        </div>
        <div>
            <label>Logradouro::</label>
            <input type="text" name="street" id="street" value="{{ $address->street }}">
        </div>
        <div>
            <label>Bairro:</label>
            <input type="text" name="neighborhood" id="neighborhood" value="{{ $address->neighborhood }}">
        </div>
        <div>
            <label>Cidade:</label>
            <input type="text" name="city" id="city" value="{{ $address->city }}">
        </div>
        <div>
            <label>UF:</label>
            <select name="state" id="state">
                <option value="">Select your state</option>
                <option value="SP" {{ $address->state === 'SP' ? 'selected' : ''}}>SP</option>
                <option value="RJ" {{ $address->state === 'RJ' ? 'selected' : ''}}>RJ</option>
                <option value="MG" {{ $address->state === 'MG' ? 'selected' : ''}}>MG</option>
                <option value="PA" {{ $address->state === 'PA' ? 'selected' : ''}}>PARÁ</option>
                <option value="BA" {{ $address->state === 'BA' ? 'selected' : ''}}>BAHIA</option>
                <option value="RS" {{ $address->state === 'RS' ? 'selected' : ''}}>RIO GRANDE DO SUL</option>
            </select>
        </div>
        <div>
            <label>País:</label>
            <select name="country" id="country" required>
                <option value="">Select your country</option>
                <option value="br">Brasil</option>
                <option value="eua">Estados Unidos</option>
                <option value="uk">Reino Unido</option>
                <option value="kr">Coréia do Sul</option>
                <option value="cn">China</option>
                <option value="jp">Japão</option>
            </select>
        </div>
        <div>
            <label>Número:</label>
            <input type="text" name="number" id="number" value="{{ $address->number }}" required>
        </div>
        <div>
            <label>Complemento:</label>
            <input type="text" name="complement" id="complement" value="{{ $address->complement }}" placeholder="ex: Near the bakery">
        </div>

        <button type="submit">Atualizar</button>
        <form action="{{ route('addresses.destroy', $address) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Remover Endereço</button>
        </form>
    </form>

    <script>
        const cep = document.getElementById('cep');
        cep.addEventListener('blur', () => {
            const logradouro = document.getElementById('street');
            const bairro = document.getElementById('neighborhood');
            const cidade = document.getElementById('city');
            const estado = document.getElementById('state');
            const pais = document.getElementById('country');

            let cepNumerico = cep.value.replace(/\D/g, '');

            if(cepNumerico.length !== 8){
                alert("CEP inválido");
                return;
            }

            fetch(`https://viacep.com.br/ws/${cepNumerico}/json/`)
                .then(response => response.json())
                .then(data => {
                    if(data.erro){
                        alert("CEP não encontrado");
                        return;
                    }

                    logradouro.value = data.logradouro;
                    bairro.value = data.bairro;
                    cidade.value = data.localidade;
                    estado.value = data.uf;
                    
                })
                .catch(error => {
                    console.error("Erro ao consultar cep!", error);
                });
        });
        
    </script>
    </form>

    @if($errors->any())
        <div>
            {{$errors->first()}}
        </div>
    @endif

@endsection