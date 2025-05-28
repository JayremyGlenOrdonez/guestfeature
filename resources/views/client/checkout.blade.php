@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 40px 0;">
        <h2 class="section-heading">Checkout</h2>

        @php
            $cart = session()->get('cart');
            $total = 0;
            if ($cart) {
                foreach ($cart as $id => $details) {
                    $total += $details['price'] * $details['quantity'];
                }
            }
        @endphp

        @if ($cart && count($cart) > 0)
            <div class="checkout-layout" style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: center;">
                {{-- Checkout Form --}}
                <div class="checkout-form"
                    style="flex: 2; min-width: 400px; max-width: 700px; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 1.8em; color: #5b21b6; margin-bottom: 25px;">Shipping Information</h3>

                    {{-- Display Validation Errors from ClientController --}}
                    @if (isset($errors) && $errors->any())
                        <div
                            style="color: red; margin-bottom: 20px; border: 1px solid red; padding: 10px; border-radius: 5px;">
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>&bullet; {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Display other session messages like 'error' --}}
                    @if (session('error'))
                        <div
                            style="color: red; margin-bottom: 20px; border: 1px solid red; padding: 10px; border-radius: 5px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('clientCheckoutSave') }}" method="POST">
                        @csrf {{-- CSRF token for security --}}

                        <div style="margin-bottom: 20px;">
                            <label for="name" style="display: block; font-weight: bold; margin-bottom: 8px;">Full
                                Name:</label>
                            {{-- Pre-fill with user->name or old('name') --}}
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                                required
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                        </div>

                        {{-- If you have email in your User model and want to show it --}}
                        {{-- <div style="margin-bottom: 20px;">
                            <label for="email" style="display: block; font-weight: bold; margin-bottom: 8px;">Email Address:</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                        </div> --}}

                        <div style="margin-bottom: 20px;">
                            <label for="phone" style="display: block; font-weight: bold; margin-bottom: 8px;">Phone
                                Number:</label>
                            {{-- Pre-fill with user->phone or old('phone') --}}
                            <input type="text" id="phone" name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}" required
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="address" style="display: block; font-weight: bold; margin-bottom: 8px;">Full
                                Address:</label>
                            {{-- Pre-fill with user->address or old('address') --}}
                            <textarea id="address" name="address" required
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em; min-height: 80px;">{{ old('address', $user->address ?? '') }}</textarea>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <label for="note" style="display: block; font-weight: bold; margin-bottom: 8px;">Order Note
                                (Optional):</label>
                            <textarea id="note" name="note"
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em; min-height: 60px;">{{ old('note') }}</textarea>
                        </div>

                        {{-- Payment Method (Optional, for now, assumed COD based on controller) --}}
                        <div style="margin-bottom: 30px;">
                            <h3 style="font-size: 1.4em; margin-bottom: 15px; color: #333;">Payment Method</h3>
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod" style="font-size: 1.1em; font-weight: bold;">Cash on Delivery
                                (COD)</label>
                            {{-- Other payment options can be added here --}}
                        </div>

                        <button type="submit"
                            style="width: 100%; padding: 15px; background-color: #5b21b6; color: white; border: none; border-radius: 30px; cursor: pointer; font-size: 1.2em; font-weight: bold; transition: background-color 0.3s;">Place
                            Order</button>
                    </form>
                </div>

                {{-- Order Summary --}}
                <div class="order-summary"
                    style="flex: 1; min-width: 300px; max-width: 400px; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); align-self: flex-start;">
                    <h3 style="font-size: 1.8em; color: #5b21b6; margin-bottom: 25px;">Order Summary</h3>
                    <ul
                        style="list-style: none; padding: 0; margin: 0; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                        @foreach ($cart as $item)
                            <li style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="color: #555;">{{ $item['title'] }} (x{{ $item['quantity'] }})</span>
                                <span
                                    style="font-weight: bold;">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="font-size: 1.4em; color: #333;">Grand Total:</h4>
                        <span
                            style="font-size: 1.8em; color: #5b21b6; font-weight: bold;">₱{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Your cart is empty. Please add items to your cart
                before checking out.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('clientProducts') }}" class="btn-more">Go to Products</a>
            </div>
        @endif
    </section>
@endsection
