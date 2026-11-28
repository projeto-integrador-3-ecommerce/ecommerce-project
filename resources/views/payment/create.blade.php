@extends('layouts.storefront', ['title' => 'Pagamento'])

@section('content')
    @php
        $total = (float) $order->total;
        $formatMoney = fn (float $value): string => 'R$ '.number_format($value, 2, ',', '.');
        $methods = [
            'credit' => ['Cartão de crédito', 'Em até 6x sem juros', 'M2.5 6.5h19A1.5 1.5 0 0 1 23 8v9a1.5 1.5 0 0 1-1.5 1.5h-19A1.5 1.5 0 0 1 1 17V8a1.5 1.5 0 0 1 1.5-1.5ZM1 10h22', false],
            'debit' => ['Cartão de débito', 'Débito à vista', 'M2.5 6.5h19A1.5 1.5 0 0 1 23 8v9a1.5 1.5 0 0 1-1.5 1.5h-19A1.5 1.5 0 0 1 1 17V8a1.5 1.5 0 0 1 1.5-1.5ZM1 10h22M7 15h4', false],
            'pix' => ['Pix', 'Pagamento instantâneo', 'M5.283 18.36a3.505 3.505 0 0 0 2.493-1.032l3.6-3.6a.684.684 0 0 1 .946 0l3.613 3.613a3.504 3.504 0 0 0 2.493 1.032h.71l-4.56 4.56a3.647 3.647 0 0 1-5.156 0L4.85 18.36ZM18.428 5.627a3.505 3.505 0 0 0-2.493 1.032l-3.613 3.614a.67.67 0 0 1-.946 0l-3.6-3.6A3.505 3.505 0 0 0 5.283 5.64h-.434l4.573-4.572a3.646 3.646 0 0 1 5.156 0l4.559 4.559ZM1.068 9.422 3.79 6.699h1.492a2.483 2.483 0 0 1 1.744.722l3.6 3.6a1.73 1.73 0 0 0 2.443 0l3.614-3.613a2.482 2.482 0 0 1 1.744-.723h1.767l2.737 2.737a3.646 3.646 0 0 1 0 5.156l-2.736 2.736h-1.768a2.482 2.482 0 0 1-1.744-.722l-3.613-3.613a1.77 1.77 0 0 0-2.444 0l-3.6 3.6a2.483 2.483 0 0 1-1.744.722H3.791l-2.723-2.723a3.646 3.646 0 0 1 0-5.156', true],
            'mercado_pago' => ['Mercado Pago', 'Checkout seguro', 'M11.115 16.479a.93.927 0 0 1-.939-.886c-.002-.042-.006-.155-.103-.155-.04 0-.074.023-.113.059-.112.103-.254.206-.46.206a.816.814 0 0 1-.305-.066c-.535-.214-.542-.578-.521-.725.006-.038.007-.08-.02-.11l-.032-.03h-.034c-.027 0-.055.012-.093.039a.788.786 0 0 1-.454.16.7.699 0 0 1-.253-.05c-.708-.27-.65-.928-.617-1.126.005-.041-.005-.072-.03-.092l-.05-.04-.047.043a.728.726 0 0 1-.505.203.73.728 0 0 1-.732-.725c0-.4.328-.722.732-.722.364 0 .675.27.721.63l.026.195.11-.165c.01-.018.307-.46.852-.46.102 0 .21.016.316.05.434.13.508.52.519.68.008.094.075.1.09.1.037 0 .064-.024.083-.045a.746.744 0 0 1 .54-.225c.128 0 .263.03.402.09.69.293.379 1.158.374 1.167-.058.144-.061.207-.005.244l.027.013h.02c.03 0 .07-.014.134-.035.093-.032.235-.08.367-.08a.944.942 0 0 1 .94.93.936.934 0 0 1-.94.928zm7.302-4.171c-1.138-.98-3.768-3.24-4.481-3.77-.406-.302-.685-.462-.928-.533a1.559 1.554 0 0 0-.456-.07c-.182 0-.376.032-.58.095-.46.145-.918.505-1.362.854l-.023.018c-.414.324-.84.66-1.164.73a1.986 1.98 0 0 1-.43.049c-.362 0-.687-.104-.81-.258-.02-.025-.007-.066.04-.125l.008-.008 1-1.067c.783-.774 1.525-1.506 3.23-1.545h.085c1.062 0 2.12.469 2.24.524a7.03 7.03 0 0 0 3.056.724c1.076 0 2.188-.263 3.354-.795a9.135 9.11 0 0 0-.405-.317c-1.025.44-2.003.66-2.946.66-.962 0-1.925-.229-2.858-.68-.05-.022-1.22-.567-2.44-.57-.032 0-.065 0-.096.002-1.434.033-2.24.536-2.782.976-.528.013-.982.138-1.388.25-.361.1-.673.186-.979.185-.125 0-.35-.01-.37-.012-.35-.01-2.115-.437-3.518-.962-.143.1-.28.203-.415.31 1.466.593 3.25 1.053 3.812 1.089.157.01.323.027.491.027.372 0 .744-.103 1.104-.203.213-.059.446-.123.692-.17l-.196.194-1.017 1.087c-.08.08-.254.294-.14.557a.705.703 0 0 0 .268.292c.243.162.677.27 1.08.271.152 0 .297-.015.43-.044.427-.095.874-.448 1.349-.82.377-.296.913-.672 1.323-.782a1.494 1.49 0 0 1 .37-.05.611.61 0 0 1 .095.005c.27.034.533.125 1.003.472.835.62 4.531 3.815 4.566 3.846.002.002.238.203.22.537-.007.186-.11.352-.294.466a.902.9 0 0 1-.484.15.804.802 0 0 1-.428-.124c-.014-.01-1.28-1.157-1.746-1.543-.074-.06-.146-.115-.22-.115a.122.122 0 0 0-.096.045c-.073.09.01.212.105.294l1.48 1.47c.002 0 .184.17.204.395.012.244-.106.447-.35.606a.957.955 0 0 1-.526.171.766.764 0 0 1-.42-.127l-.214-.206a21.035 20.978 0 0 0-1.08-1.009c-.072-.058-.148-.112-.221-.112a.127.127 0 0 0-.094.038c-.033.037-.056.103.028.212a.698.696 0 0 0 .075.083l1.078 1.198c.01.01.222.26.024.511l-.038.048a1.18 1.178 0 0 1-.1.096c-.184.15-.43.164-.527.164a.8.798 0 0 1-.147-.012c-.106-.018-.178-.048-.212-.089l-.013-.013c-.06-.06-.602-.609-1.054-.98-.059-.05-.133-.11-.21-.11a.128.128 0 0 0-.096.042c-.09.096.044.24.1.293l.92 1.003a.204.204 0 0 1-.033.062c-.033.044-.144.155-.479.196a.91.907 0 0 1-.122.007c-.345 0-.712-.164-.902-.264a1.343 1.34 0 0 0 .13-.576 1.368 1.365 0 0 0-1.42-1.357c.024-.342-.025-.99-.697-1.274a1.455 1.452 0 0 0-.575-.125c-.146 0-.287.025-.42.075a1.153 1.15 0 0 0-.671-.564 1.52 1.515 0 0 0-.494-.085c-.28 0-.537.08-.767.242a1.168 1.165 0 0 0-.903-.43 1.173 1.17 0 0 0-.82.335c-.287-.217-1.425-.93-4.467-1.613a17.39 17.344 0 0 1-.692-.189 4.822 4.82 0 0 0-.077.494l.67.157c3.108.682 4.136 1.391 4.309 1.525a1.145 1.142 0 0 0-.09.442 1.16 1.158 0 0 0 1.378 1.132c.096.467.406.821.879 1.003a1.165 1.162 0 0 0 .415.08c.09 0 .179-.012.266-.034.086.22.282.493.722.668a1.233 1.23 0 0 0 .457.094c.122 0 .241-.022.355-.063a1.373 1.37 0 0 0 1.269.841c.37.002.726-.147.985-.41.221.121.688.341 1.163.341.06 0 .118-.002.175-.01.47-.059.689-.24.789-.382a.571.57 0 0 0 .048-.078c.11.032.234.058.373.058.255 0 .501-.086.75-.265.244-.174.418-.424.444-.637v-.01c.083.017.167.026.251.026.265 0 .527-.082.773-.242.48-.31.562-.715.554-.98a1.28 1.279 0 0 0 .978-.194 1.04 1.04 0 0 0 .502-.808 1.088 1.085 0 0 0-.16-.653c.804-.342 2.636-1.003 4.795-1.483a4.734 4.721 0 0 0-.067-.492 27.742 27.667 0 0 0-5.049 1.62zm5.123-.763c0 4.027-5.166 7.293-11.537 7.293-6.372 0-11.538-3.266-11.538-7.293 0-4.028 5.165-7.293 11.539-7.293 6.371 0 11.537 3.265 11.537 7.293zm.46.004c0-4.272-5.374-7.755-12-7.755S.002 7.277.002 11.55L0 12.004c0 4.533 4.695 8.203 11.999 8.203 7.347 0 12-3.67 12-8.204z', true],
        ];
        $cardTypes = ['credit' => 'Crédito', 'debit' => 'Débito'];
    @endphp

    <nav aria-label="Trilha de navegação" class="mb-6 flex flex-wrap items-center gap-2 text-xs text-muted">
        <a href="{{ route('orders.show', $order) }}" class="rounded-full px-2 py-1 transition hover:text-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue">Pedido #{{ $order->id }}</a>
        <span aria-hidden="true">›</span>
        <span class="font-semibold text-navy">Pagamento</span>
    </nav>

    <section class="rounded-3xl border border-aqua bg-white/80 p-6 shadow-sm sm:p-10">
        <span class="text-xs font-semibold tracking-[0.14em] text-teal uppercase">Pedido #{{ $order->id }}</span>
        <h1 class="mt-2 text-3xl font-semibold text-navy sm:text-4xl">Pagamento</h1>
        <p class="mt-3 text-sm text-muted">Escolha a forma de pagamento e finalize sua compra.</p>
    </section>

    <section class="mt-8 grid items-start gap-6 lg:grid-cols-[1fr_20rem]">
        <article data-payment class="rounded-3xl border border-aqua bg-white p-5 shadow-sm sm:p-8">
            <h2 class="text-base font-semibold text-navy">Resumo do pedido</h2>
            <dl class="mt-4 divide-y divide-aqua text-sm">
                <div class="flex items-center justify-between py-3"><dt class="text-muted">Pedido</dt><dd class="font-semibold text-navy">#{{ $order->id }}</dd></div>
                <div class="flex items-center justify-between py-3"><dt class="font-semibold text-navy">Total</dt><dd class="text-lg font-bold text-navy">{{ $formatMoney($total) }}</dd></div>
            </dl>
            @if ($errors->any())
                <p role="alert" class="mt-4 rounded-2xl bg-red/10 px-4 py-3 text-sm font-semibold text-red">{{ $errors->first() }}</p>
            @endif

            <h3 id="pay-methods-title" class="mt-8 text-base font-semibold text-navy">Forma de pagamento</h3>
            <div role="radiogroup" aria-labelledby="pay-methods-title" class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach ($methods as $value => [$label, $hint, $methodPath, $methodFilled])
                    <label class="flex min-h-20 cursor-pointer items-center gap-3 rounded-3xl border border-navy/25 bg-white p-4 transition has-[:checked]:border-blue has-[:checked]:bg-blue/5 has-[:checked]:ring-2 has-[:checked]:ring-blue/30 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue">
                        <input type="radio" name="payment_method" value="{{ $value }}" @checked(old('payment_method', 'credit') === $value) class="sr-only">
                        <span class="grid size-11 shrink-0 place-items-center rounded-full bg-mist text-navy">
                            <svg class="size-5" viewBox="0 0 24 24"
                                 @if ($methodFilled) fill="currentColor" @else fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" @endif
                                 aria-hidden="true"><path d="{{ $methodPath }}"/></svg>
                        </span>
                        <span>
                            <strong class="block text-sm font-semibold text-navy">{{ $label }}</strong>
                            <span class="mt-1 block text-xs text-muted">{{ $hint }}</span>
                        </span>
                    </label>
                @endforeach
            </div>

            @foreach ($cardTypes as $type => $typeLabel)
                <form data-pay-panel="{{ $type }}" data-card-form hidden class="mt-6">
                    <div class="rounded-3xl bg-gradient-to-br from-navy via-[#041a48] to-[#010f37] p-6 text-white shadow-lg">
                        <span class="text-xs tracking-[0.18em] text-aqua uppercase">Cartão de {{ strtolower($typeLabel) }}</span>
                        <span data-preview="number" class="mt-6 block font-mono text-lg tracking-widest sm:text-xl">•••• •••• •••• ••••</span>
                        <div class="mt-6 flex items-end justify-between gap-4 text-xs">
                            <span data-preview="name" class="truncate">Nome no cartão</span>
                            <span data-preview="expiry">MM/AA</span>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-5">
                        <x-ui.input label="Número do cartão" type="text" id="{{ $type }}-number" data-field="number" inputmode="numeric" autocomplete="cc-number" placeholder="0000 0000 0000 0000" pattern="\d{4} \d{4} \d{4} \d{4}" title="Informe os 16 dígitos do cartão" required />
                        <x-ui.input label="Nome impresso no cartão" type="text" id="{{ $type }}-name" data-field="name" autocomplete="cc-name" placeholder="Como está no cartão" maxlength="40" required />
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-ui.input label="Validade" type="text" id="{{ $type }}-expiry" data-field="expiry" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/AA" pattern="(0[1-9]|1[0-2])/\d{2}" title="Use o formato MM/AA" required />
                            <x-ui.input label="CVV" type="text" id="{{ $type }}-cvv" data-field="cvv" inputmode="numeric" autocomplete="cc-csc" placeholder="123" pattern="\d{3,4}" title="3 ou 4 dígitos" required />
                        </div>
                        @if ($type === 'credit')
                            <x-ui.select id="credit-installments" label="Parcelas">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }}x de {{ $formatMoney($total / $i) }}{{ $i === 1 ? ' (à vista)' : ' sem juros' }}</option>
                                @endfor
                            </x-ui.select>
                        @endif
                    </div>
                    <p role="status" data-pay-feedback hidden class="mt-5 rounded-2xl bg-teal/10 px-4 py-3 text-sm font-semibold text-teal"></p>
                    <x-ui.button type="submit" size="lg" class="mt-6 w-full">Pagar {{ $formatMoney($total) }}</x-ui.button>
                </form>
            @endforeach

            <div data-pay-panel="pix" hidden>
                <div class="mt-6 rounded-3xl border border-aqua bg-bg/70 p-6 text-center">
                    <p class="text-sm text-muted">Gere o QR Code, abra o app do seu banco e escolha pagar com Pix.</p>
                    <x-ui.button type="button" data-pix-generate size="lg" class="mt-5">Gerar QR Code Pix</x-ui.button>
                    <div data-pix-result hidden class="mt-6">
                        <div data-pix-qr data-seed="{{ $pixCode }}" role="img" aria-label="QR Code Pix ilustrativo" class="mx-auto grid size-56 place-items-center rounded-3xl bg-white p-3 shadow-sm"></div>
                        <p class="mt-5 flex items-center justify-between gap-3 text-sm"><strong class="text-muted">Valor</strong><strong class="text-navy">{{ $formatMoney($total) }}</strong></p>
                        <label class="mt-5 block text-left">
                            <span class="mb-2 block text-xs font-semibold text-navy">Pix copia e cola</span>
                            <span class="flex gap-2">
                                <input type="text" value="{{ $pixCode }}" readonly data-pix-code aria-label="Código Pix copia e cola" class="min-h-12 min-w-0 flex-1 rounded-full border border-navy/30 bg-white px-4 text-xs text-navy focus:border-blue focus:outline-none focus-visible:ring-2 focus-visible:ring-blue/40">
                                <x-ui.button type="button" data-pix-copy variant="outline">Copiar</x-ui.button>
                            </span>
                        </label>
                        <p class="mt-4 text-xs text-muted">QR Code ilustrativo: este projeto não está integrado a nenhum banco, então o código não gera cobrança real.</p>
                    </div>
                </div>
            </div>

            <div data-pay-panel="mercado_pago" hidden>
                <div class="mt-6 rounded-3xl border border-aqua bg-bg/70 p-6 text-center">
                    <p class="text-sm text-muted">Você será redirecionado ao ambiente seguro do Mercado Pago para concluir o pagamento.</p>
                    <form action="{{ route('payment.store', $order) }}" method="POST" class="mt-5">@csrf<x-ui.button type="submit" size="lg" class="w-full">Pagar com Mercado Pago</x-ui.button></form>
                </div>
            </div>

            <x-ui.link href="{{ route('orders.show', $order) }}" variant="ghost" size="sm" class="mt-8">Voltar ao pedido</x-ui.link>
        </article>

        <aside class="space-y-6">
            <x-ui.address-card :address="$order->address" title="Endereço de envio" :edit-route="$order->address ? route('orders.addresses', $order) : null" edit-label="Alterar endereço" />
            <div class="rounded-3xl border border-aqua bg-white p-5 text-xs text-muted shadow-sm">
                <p class="font-semibold text-navy">Pagamento seguro</p>
                <p class="mt-2">Nenhum dado de cartão é enviado ou armazenado nesta demonstração.</p>
            </div>
        </aside>
    </section>
@endsection
