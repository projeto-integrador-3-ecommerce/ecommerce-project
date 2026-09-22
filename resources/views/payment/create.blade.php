@extends('layouts.app')

@section('content')

    <h1>Adicionar Pagamento</h1>

    <form action="{{ route('payment.store') }}" method="POST">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">

        <div>
            <label>Método de Pagamento</label>
            <select name="method" id="method">
                <option value="">Selecione uma opção</option>
                <option value="boleto">Boleto</option>
                <option value="cartao">Cartão de Crédito/Débito</option>
                <option value="pix">PIX</option>
            </select>
        </div>
        
        <!-- cartão -->
         <div id="cartao-fields" style="display: none;">
            <h3>Dados do cartão</h3>

            <div>
                <label for="card_number">Número do cartão</label>
                <input type="text" name="card_number" id="card_number" placeholder="0000 0000 0000 0000" required>
            </div>
            <div>
                <label for="card_date">Dt. Validade</label>
                <input type="date" name="card_date" id="card_date" required>
            </div>
            <div>
                <label for="card_cvv">CVV</label>
                <input type="text" name="card_cvv" id="card_cvv" placeholder="000" required>
            </div>
         </div>

         <!-- boleto -->
          <div id="boleto-fields" style="display: none;">
            <h3>Dados do boleto</h3>

            <p>Baixar Boleto</p>

            <a href="" target="_blank">Download</a>
          </div>

          <!-- pix -->
          <div id="pix-fields" style="display: none;"> 
            <h3>Pagamento via PIX</h3> 
            <p>Escaneie o QR Code para realizar o pagamento:</p> 
            <img src="{{ asset('images/qrcode-pix.png') }}" alt="QR Code PIX" width="250"> 
        </div>

        <button type="submit" id="finish-order" style="display: none;">Finalizar Pedido</button>

    </form> 

    <script>
        const method = document.getElementById('method');
        const cartaoFields = document.getElementById('cartao-fields');
        const boletoFields = document.getElementById('boleto-fields');
        const pixFields = document.getElementById('pix-fields');
        const finishOrder = document.getElementById('finish-order');
        const cardInputs = [
            document.getElementById('card_number'),
            document.getElementById('card_date'),
            document.getElementById('card_cvv'),
        ];

        const updatePaymentForm = () => {
            const isCardSelected = method.value === 'cartao';
            const areCardFieldsFilled = cardInputs.every((input) => input.value.trim() !== '');
            const canFinishOrder = method.value !== '' && (!isCardSelected || areCardFieldsFilled);

            cartaoFields.style.display = isCardSelected ? 'block' : 'none';
            boletoFields.style.display = method.value === 'boleto' ? 'block' : 'none';
            pixFields.style.display = method.value === 'pix' ? 'block' : 'none';
            finishOrder.style.display = canFinishOrder ? 'inline-block' : 'none';

            cardInputs.forEach((input) => {
                input.required = isCardSelected;
            });
        };

        method.addEventListener('change', updatePaymentForm);
        cardInputs.forEach((input) => {
            input.addEventListener('input', updatePaymentForm);
        });

        updatePaymentForm();
    </script>