@extends('layouts.app')

@section('content')

    <h1>Cadastrar endereço</h1>

    <form action="{{ route('addresses.store') }}" method="POST">
        @csrf

        <div>
            <label>CEP:</label>
            <input type="text" name="cep" id="cep" placeholder="ex: 00000-000" required>
        </div>
        <div>
            <label>Logradouro::</label>
            <input type="text" name="street" id="street" placeholder="ex: Maple Street, Springfield, IL 62704">
        </div>
        <div>
            <label>Bairro:</label>
            <input type="text" name="neighborhood" id="neighborhood" placeholder="ex: Springfield">
        </div>
        <div>
            <label>Cidade:</label>
            <input type="text" name="city" id="city" placeholder="ex: São Paulo">
        </div>
        <div>
            <label>UF:</label>
            <select name="state" id="state">
                <option value="">Select your state</option>
                <option value="SP">SP</option>
                <option value="RJ">RJ</option>
                <option value="MG">MG</option>
                <option value="PA">PARÁ</option>
                <option value="BA">BAHIA</option>
                <option value="RS">RIO GRANDE DO SUL</option>
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
            <input type="text" name="number" id="number" placeholder="ex: 216" required>
        </div>
        <div>
            <label>Complemento:</label>
            <input type="text" name="complement" id="complement" placeholder="ex: Near the bakery">
        </div>

        <button type="submit">Cadastrar</button>
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