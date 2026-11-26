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

    <section class="page-head"><div class="container"><span class="eyebrow">Pedido #{{ $order->id }}</span><h1>Pagamento</h1><p>Escolha a forma de pagamento e finalize sua compra.</p></div></section>
    <section class="container auth auth--wide pb-20">
        <article class="summary" data-payment>
            <h2>Resumo do pedido</h2>
            <p class="summary__row"><span>Pedido</span><span>#{{ $order->id }}</span></p>
            <p class="summary__row summary__row--total"><strong>Total</strong><strong>{{ $formatMoney($total) }}</strong></p>
            @if ($errors->any())<p class="form-error" role="alert">{{ $errors->first() }}</p>@endif

            <h3 class="pay-title" id="pay-methods-title">Forma de pagamento</h3>
            <div class="pay-methods" role="radiogroup" aria-labelledby="pay-methods-title">
                @foreach ($methods as $value => [$label, $hint])
                    <label class="pay-method">
                        <input class="sr-only" type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'credit') === $value)>
                        <strong>{{ $label }}</strong>
                        <span>{{ $hint }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Cartão de crédito e débito: apenas visual, nenhum dado é enviado ao servidor --}}
            @foreach ($cardTypes as $type => $typeLabel)
                <form class="pay-panel" data-pay-panel="{{ $type }}" data-card-form hidden>
                    <div class="card-preview" aria-hidden="true">
                        <span class="card-preview__type">Cartão de {{ strtolower($typeLabel) }}</span>
                        <span class="card-preview__number" data-preview="number">•••• •••• •••• ••••</span>
                        <div class="card-preview__foot"><span data-preview="name">Nome no cartão</span><span data-preview="expiry">MM/AA</span></div>
                    </div>

                    <label class="field"><span>Número do cartão</span><input class="input" type="text" id="{{ $type }}-number" data-field="number" inputmode="numeric" autocomplete="cc-number" placeholder="0000 0000 0000 0000" pattern="\d{4} \d{4} \d{4} \d{4}" title="Informe os 16 dígitos do cartão" required></label>
                    <label class="field"><span>Nome impresso no cartão</span><input class="input" type="text" id="{{ $type }}-name" data-field="name" autocomplete="cc-name" placeholder="Como está no cartão" maxlength="40" required></label>
                    <div class="field-row">
                        <label class="field"><span>Validade</span><input class="input" type="text" id="{{ $type }}-expiry" data-field="expiry" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/AA" pattern="(0[1-9]|1[0-2])/\d{2}" title="Use o formato MM/AA" required></label>
                        <label class="field"><span>CVV</span><input class="input" type="text" id="{{ $type }}-cvv" data-field="cvv" inputmode="numeric" autocomplete="cc-csc" placeholder="123" pattern="\d{3,4}" title="3 ou 4 dígitos" required></label>
                    </div>

                    @if ($type === 'credit')
                        <label class="field"><span>Parcelas</span>
                            <select class="input" id="credit-installments">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }}x de {{ $formatMoney($total / $i) }}{{ $i === 1 ? ' (à vista)' : ' sem juros' }}</option>
                                @endfor
                            </select>
                        </label>
                    @endif

                    <p class="status-pill pay-feedback" role="status" data-pay-feedback hidden></p>
                    <button class="btn btn--primary btn--block" type="submit">Pagar {{ $formatMoney($total) }}</button>
                </form>
            @endforeach

            {{-- Pix: QR Code ilustrativo --}}
            <div class="pay-panel pix" data-pay-panel="pix" hidden>
                <p class="text-muted text-[13px]">Gere o QR Code, abra o app do seu banco e escolha pagar com Pix.</p>
                <button class="btn btn--primary btn--block mt-4" type="button" data-pix-generate>Gerar QR Code Pix</button>

                <div data-pix-result hidden>
                    <div class="pix__qr" data-pix-qr data-seed="{{ $pixCode }}" role="img" aria-label="QR Code Pix ilustrativo"></div>
                    <p class="summary__row summary__row--total pix__total"><strong>Valor</strong><strong>{{ $formatMoney($total) }}</strong></p>
                    <label class="field"><span>Pix copia e cola</span>
                        <span class="pix__code">
                            <input class="input" type="text" value="{{ $pixCode }}" readonly data-pix-code aria-label="Código Pix copia e cola">
                            <button class="btn btn--outline" type="button" data-pix-copy>Copiar</button>
                        </span>
                    </label>
                    <p class="form-note">QR Code ilustrativo: este projeto não está integrado a nenhum banco, então o código não gera cobrança real.</p>
                </div>
            </div>

            {{-- Mercado Pago: fluxo já existente --}}
            <div class="pay-panel" data-pay-panel="mercado_pago" hidden>
                <p class="text-muted text-[13px]">Você será redirecionado ao ambiente seguro do Mercado Pago para concluir o pagamento.</p>
                <form action="{{ route('payment.store', $order) }}" method="POST" class="mt-4">@csrf<button class="btn btn--primary btn--block" type="submit">Pagar com Mercado Pago</button></form>
            </div>

            <a class="btn btn--ghost btn--block mt-5" href="{{ route('orders.show', $order) }}">Voltar ao pedido</a>
        </article>
    </section>
@endsection
