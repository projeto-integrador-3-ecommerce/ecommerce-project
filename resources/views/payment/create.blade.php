@extends('layouts.storefront', ['title' => 'Pagamento'])

@section('content')
    @php
        $total = (float) $order->total;
        $formatMoney = fn (float $value): string => 'R$ '.number_format($value, 2, ',', '.');
        $methods = [
            'credit' => ['Cartão de crédito', 'Em até 6x sem juros'],
            'debit' => ['Cartão de débito', 'Débito à vista'],
            'pix' => ['Pix', 'Pagamento instantâneo'],
            'mercado_pago' => ['Mercado Pago', 'Checkout seguro'],
        ];
        $cardTypes = ['credit' => 'Crédito', 'debit' => 'Débito'];
    @endphp

    <section><div><span>Pedido #{{ $order->id }}</span><h1>Pagamento</h1><p>Escolha a forma de pagamento e finalize sua compra.</p></div></section>
    <section>
        <article data-payment>
            <h2>Resumo do pedido</h2>
            <p><span>Pedido</span><span>#{{ $order->id }}</span></p>
            <p><strong>Total</strong><strong>{{ $formatMoney($total) }}</strong></p>
            @if ($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif

            <h3 id="pay-methods-title">Forma de pagamento</h3>
            <div role="radiogroup" aria-labelledby="pay-methods-title">
                @foreach ($methods as $value => [$label, $hint])
                    <label>
                        <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'credit') === $value)>
                        <strong>{{ $label }}</strong>
                        <span>{{ $hint }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Cartão de crédito e débito: apenas visual, nenhum dado é enviado ao servidor --}}
            @foreach ($cardTypes as $type => $typeLabel)
                <form data-pay-panel="{{ $type }}" data-card-form hidden>
                    <div aria-hidden="true">
                        <span>Cartão de {{ strtolower($typeLabel) }}</span>
                        <span data-preview="number">•••• •••• •••• ••••</span>
                        <div><span data-preview="name">Nome no cartão</span><span data-preview="expiry">MM/AA</span></div>
                    </div>

                    <label><span>Número do cartão</span><input type="text" id="{{ $type }}-number" data-field="number" inputmode="numeric" autocomplete="cc-number" placeholder="0000 0000 0000 0000" pattern="\d{4} \d{4} \d{4} \d{4}" title="Informe os 16 dígitos do cartão" required></label>
                    <label><span>Nome impresso no cartão</span><input type="text" id="{{ $type }}-name" data-field="name" autocomplete="cc-name" placeholder="Como está no cartão" maxlength="40" required></label>
                    <div>
                        <label><span>Validade</span><input type="text" id="{{ $type }}-expiry" data-field="expiry" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/AA" pattern="(0[1-9]|1[0-2])/\d{2}" title="Use o formato MM/AA" required></label>
                        <label><span>CVV</span><input type="text" id="{{ $type }}-cvv" data-field="cvv" inputmode="numeric" autocomplete="cc-csc" placeholder="123" pattern="\d{3,4}" title="3 ou 4 dígitos" required></label>
                    </div>

                    @if ($type === 'credit')
                        <label><span>Parcelas</span>
                            <select id="credit-installments">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }}x de {{ $formatMoney($total / $i) }}{{ $i === 1 ? ' (à vista)' : ' sem juros' }}</option>
                                @endfor
                            </select>
                        </label>
                    @endif

                    <p role="status" data-pay-feedback hidden></p>
                    <button type="submit">Pagar {{ $formatMoney($total) }}</button>
                </form>
            @endforeach

            {{-- Pix: QR Code ilustrativo --}}
            <div data-pay-panel="pix" hidden>
                <p>Gere o QR Code, abra o app do seu banco e escolha pagar com Pix.</p>
                <button type="button" data-pix-generate>Gerar QR Code Pix</button>

                <div data-pix-result hidden>
                    <div data-pix-qr data-seed="{{ $pixCode }}" role="img" aria-label="QR Code Pix ilustrativo"></div>
                    <p><strong>Valor</strong><strong>{{ $formatMoney($total) }}</strong></p>
                    <label><span>Pix copia e cola</span>
                        <span>
                            <input type="text" value="{{ $pixCode }}" readonly data-pix-code aria-label="Código Pix copia e cola">
                            <button type="button" data-pix-copy>Copiar</button>
                        </span>
                    </label>
                    <p>QR Code ilustrativo: este projeto não está integrado a nenhum banco, então o código não gera cobrança real.</p>
                </div>
            </div>

            {{-- Mercado Pago: fluxo já existente --}}
            <div data-pay-panel="mercado_pago" hidden>
                <p>Você será redirecionado ao ambiente seguro do Mercado Pago para concluir o pagamento.</p>
                <form action="{{ route('payment.store', $order) }}" method="POST">@csrf<button type="submit">Pagar com Mercado Pago</button></form>
            </div>

            <a href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>
        </article>
    </section>
@endsection
