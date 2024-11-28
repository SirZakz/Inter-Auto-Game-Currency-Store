<x-app-layout>
    @push('javascript')
        <script src="https://js.stripe.com/v3/"></script>
        <script defer src="{{ asset('/assets/js/stripe.js') }}"></script>
    @endpush

    @push('title', __('Shop'))

    <div class="col-span-12">
    <x-modals.modal-wrapper>
        <div class="w-full py-2 px-4 text-center bg-[#f68b08] text-white rounded">
            {{ __('Please make sure to read our shop') }}
            <button class="text-white underline font-bold" x-on:click="open = true">{{ __('Terms & Conditions') }}</button>
            {{ __('before making a purchase') }}
        </div>

        <x-modals.regular-modal>
            <x-slot name="title">
                <h2 class="text-2xl">
                    {{ __('Shop Terms & Conditions') }}
                </h2>
            </x-slot>

            <div class="space-y-3 p-2">
                <p>
                    {{ __('Here at :hotel Hotel we accept donations to support the hotel\'s operations. As a thank you, you will receive in-game items in return for your support.', ['hotel' => setting('hotel_name')]) }}
                </p>

                <div class="flex flex-col gap-y-2 !mt-6">
                    <p class="font-semibold">{{ __('Why are donations important?') }}</p>

                    <p>{{ __('Your donations are essential to cover the monthly expenses needed to keep the hotel running smoothly, as well as to introduce new and exciting features for the community to enjoy.') }}</p>
                </div>

                <div class="flex flex-col gap-y-2 !mt-6">
                    <p class="font-semibold">{{ __('Our terms') }}</p>

                    <p>{{ __('Once a donation has been received, it is considered final and non-refundable. The amount converted into website balance or in-game items cannot be reversed or exchanged for cash. By making a donation, you agree not to request a chargeback or dispute through your bank or card issuer.') }}</p>
                </div>

                <div class="flex flex-col gap-y-2 !mt-6">
                    <p class="font-semibold">{{ __('Refund Policy') }}</p>

                    <p>{{ __('All donations are non-refundable. In the event of accidental overpayments or technical issues, please contact our support team within 48 hours to discuss potential resolutions. However, we reserve the right to assess and determine eligibility for any refunds or adjustments at our sole discretion.') }}</p>
                </div>

                <div class="flex flex-col gap-y-2 !mt-6">
                    <p class="font-semibold">{{ __('Notice') }}</p>

                    <p>{{ __('Please consider your financial situation before making any donation. If you feel uncertain about spending, take time to reflect on your choices. Your financial well-being is important. Should you need assistance with managing your finances, seek advice from professional resources.') }}</p>
                </div>
            </div>
        </x-modals.regular-modal>
    </x-modals.modal-wrapper>
</div>


    <div class="col-span-12 md:col-span-7 lg:col-span-8 xl:col-span-9 space-y-3">
        <x-page-header>
            Inter+ Bundle Packages
        </x-page-header>

        <div class="grid grid-cols-3 gap-3">
            @foreach ($articles_bundle as $article)
                <x-shop.packages :article="$article" />
            @endforeach
        </div>
        <x-page-header>
            Ala carte (Credits & Diamonds)
        </x-page-header>
        <div class="grid grid-cols-3 gap-3">
            @foreach ($articles_alacarte as $article)
                <x-shop.packages :article="$article" />
            @endforeach
        </div>
        <x-page-header>
            Only at Inter+
        </x-page-header>
        <div class="grid grid-cols-3 gap-3">
            @foreach ($articles_onlyinterplus as $article)
                <x-shop.packages :article="$article" />
            @endforeach
        </div>
    </div>

    <div class="col-span-12 md:col-span-7 lg:col-span-3 space-y-3">
        <x-page-header>
            Cart
        </x-page-header>
        
        <div id="cart-items" class="space-y-2">
            <!-- Cart items will be dynamically added here -->
        </div>

        <button id="proceed-to-checkout-button" class="w-full py-2 px-4 bg-[#f68b08] text-white rounded">Proceed to Checkout</button>

        <form id="payment-form" method="post">
            <div class="flex flex-col space-y-4">
                <div id="payment-element">
                    <!--Stripe.js injects the Payment Element-->
                </div>

                <div id="checkout" class="flex flex-row space-x-4">
                    <!--Checkout button will be added here-->
                </div>
            </div>
        </form>
    </div>


    @push('javascript')
        <script type="module">
            tippy('.user-badge');
        </script>
    @endpush
</x-app-layout>
