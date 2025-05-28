@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 40px 0;">
        <h2 class="section-heading">Your Shopping Cart</h2>

        @php
            $cart = session()->get('cart');
            $total = 0;
        @endphp

        @if ($cart && count($cart) > 0)
            <div class="cart-table-container" style="overflow-x: auto;">
                <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Product</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Price</th>
                            <th style="padding: 12px 15px; text-align: center; border-bottom: 1px solid #ddd;">Quantity</th>
                            <th style="padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;">Subtotal</th>
                            <th style="padding: 12px 15px; text-align: center; border-bottom: 1px solid #ddd;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $id => $details)
                            @php
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr data-id="{{ $id }}">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd;">{{ $details['title'] }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd;">
                                    ₱{{ number_format($details['price'], 2) }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: center;">
                                    {{-- Ensure product_stock is correctly passed when adding to cart --}}
                                    <input type="number" value="{{ $details['quantity'] }}" min="1"
                                        max="{{ $details['product_stock'] ?? 9999 }}" class="quantity-input"
                                        {{-- Added a default max if product_stock is missing --}}
                                        style="width: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
                                </td>
                                <td class="item-subtotal" style="padding: 12px 15px; border-bottom: 1px solid #ddd;">
                                    ₱{{ number_format($subtotal, 2) }}</td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #ddd; text-align: center;">
                                    <button class="remove-from-cart-btn" data-id="{{ $id }}"
                                        style="background-color: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">Remove</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-summary"
                style="display: flex; justify-content: flex-end; align-items: center; gap: 30px; margin-top: 30px;">
                <h3 style="font-size: 1.5em; color: #333;">Total: <span id="cart-total"
                        style="color: #5b21b6;">₱{{ number_format($total, 2) }}</span></h3>
                <a href="{{ route('login') }}" class="btn btn-primary">Login to Checkout / Register</a>
                <p class="mt-2 text-muted">Please login or register to proceed with your order.</p>
            </div>
            <div style="text-align: right; margin-top: 15px;">
                <a href="{{ route('clientProducts') }}" class="btn-continue-shopping"
                    style="text-decoration: none; color: #5b21b6; font-weight: bold;">← Continue Shopping</a>
            </div>
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Your cart is empty.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('clientProducts') }}" class="btn-more">Start Shopping Now!</a>
            </div>
        @endif
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Function to update cart total on the page
                function refreshCartTotal(newTotal, newCartCount) {
                    $('#cart-total').text('₱' + newTotal.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    updateCartCount(newCartCount); // This calls the global updateCartCount from app.blade.php
                }

                // Update Cart Quantity
                $('.quantity-input').on('change', function() {
                    let $input = $(this); // Cache the input element
                    let productId = $input.closest('tr').data('id');
                    let newQuantity = parseInt($input.val()); // Ensure it's an integer
                    let maxStock = parseInt($input.attr('max'));

                    if (isNaN(newQuantity) || newQuantity < 1) { // Handle invalid input
                        newQuantity = 1;
                        $input.val(1);
                    }

                    if (newQuantity > maxStock) {
                        alert('Maximum stock for this product is ' + maxStock + '.');
                        newQuantity = maxStock;
                        $input.val(maxStock);
                    }

                    // Only proceed with AJAX if quantity changed and is valid
                    if (newQuantity !== parseInt($input.data('old-quantity'))) {
                        $.ajax({
                            url: "{{ route('clientUpdateCart') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                product_id: productId,
                                quantity: newQuantity
                            },
                            success: function(response) {
                                // Update subtotal for the specific row
                                let price = parseFloat($input.closest('tr').find('td:nth-child(2)')
                                    .text().replace('₱', '').replace(/,/g, '')
                                ); // Handle commas in price
                                let newSubtotal = price * newQuantity;
                                $input.closest('tr').find('.item-subtotal').text('₱' + newSubtotal
                                    .toLocaleString('en-PH', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }));

                                refreshCartTotal(response.total, response
                                    .cartCount); // Update global total and cart count
                            },
                            error: function(xhr) {
                                console.error("AJAX Error:", xhr.responseText);
                                alert('Error updating cart. Please check console for details.');
                                // Revert quantity if error
                                $input.val($input.data('old-quantity'));
                            }
                        });
                    }
                }).on('focus', function() {
                    $(this).data('old-quantity', $(this).val()); // Store current quantity on focus
                });


                // Remove from Cart
                $('.remove-from-cart-btn').on('click', function() {
                    let productId = $(this).data('id');
                    if (confirm('Are you sure you want to remove this item from your cart?')) {
                        $.ajax({
                            url: "{{ route('clientDeleteCart') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: productId
                            },
                            success: function(response) {
                                $('[data-id="' + productId + '"]')
                                    .remove(); // Remove the row from the table
                                refreshCartTotal(response.total, response
                                    .cartCount); // Update total and cart count

                                if (response.cartCount === 0) {
                                    // Redirect or show empty cart message
                                    $('.cart-table-container').html(
                                        '<p style="text-align: center; font-size: 1.2em; color: #888;">Your cart is empty.</p><div style="text-align: center; margin-top: 30px;"><a href="{{ route('clientProducts') }}" class="btn-more">Start Shopping Now!</a></div>'
                                    );
                                    $('.cart-summary').remove(); // Remove summary section
                                    $('.btn-continue-shopping')
                                        .remove(); // Remove continue shopping link
                                }
                            },
                            error: function(xhr) {
                                console.error("AJAX Error:", xhr.responseText);
                                alert(
                                    'Error removing from cart. Please check console for details.'
                                );
                            }
                        });
                    }
                });


            });
        </script>
    @endpush
@endsection
