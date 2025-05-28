@extends('client.layouts.app')

@section('content')
    <section class="container">
        <h2 class="section-heading">Our Products</h2>

        {{-- Optional: Category Filter (Pwede mo itong i-design depende sa gusto mo) --}}
        <div class="category-filter" style="margin-bottom: 30px; text-align: center;">
            <a href="{{ route('clientProducts') }}"
                style="margin: 0 10px; text-decoration: none; color: #5b21b6; font-weight: bold;">All Categories</a>
            @foreach ($category as $cat)
                {{-- $category galing sa ClientController->products() --}}
                <a href="{{ route('clientCategoryProducts', Str::slug($cat->name)) }}"
                    style="margin: 0 10px; text-decoration: none; color: #333;">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if ($product->count() > 0)
            <div class="product-grid">
                @foreach ($product as $item)
                    {{-- $product galing sa ClientController->products() na naka-paginate --}}
                    <div class="product-card">
                        {{-- Product Image --}}
                        <img src="{{ asset($item->productImage->first()->path ?? 'images/default-product.png') }}"
                            alt="{{ $item->title }}">
                        <h3><a href="{{ route('clientProductDetail', Str::slug($item->title)) }}">{{ $item->title }}</a>
                        </h3>
                        <p>₱{{ number_format($item->price, 2) }}</p>
                        {{-- Add to Cart button --}}
                        <button class="add-to-cart-btn" data-product-id="{{ $item->id }}"
                            data-product-stock="{{ $item->stock }}" data-product-title="{{ $item->title }}">Add to
                            Cart</button>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="pagination" style="margin-top: 40px; text-align: center;">
                {{ $product->links() }}
            </div>
        @else
            <p style="text-align: center;">No products available at the moment.</p>
        @endif
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> {{-- For easier AJAX --}}
        <script>
            $(document).ready(function() {
                $('.add-to-cart-btn').on('click', function() {
                    let productId = $(this).data('product-id');
                    let productStock = $(this).data('product-stock');
                    let productTitle = $(this).data('product-title');
                    let quantity = 1; // Default quantity for adding from product listing

                    if (productStock <= 0) {
                        alert(productTitle + ' is out of stock!');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('clientAddToCart') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: productId,
                            quantity: quantity
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(productTitle + ' added to cart!');
                                updateCartCount(response.cartCount); // Update cart count in header
                            } else if (response.status === 'failed' && response.code === 202) {
                                alert('Failed to add ' + productTitle +
                                    ' to cart. Requested quantity exceeds stock.');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        },
                        error: function(xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            alert('Error adding to cart. Please check console for details.');
                        }
                    });
                });

                // Re-define updateCartCount if it's not globally accessible from app.blade.php
                // Or ensure it's in a global scope if you're using plain JS.
                // If using jQuery, you can just directly update the element:
                function updateCartCount(count) {
                    $('#cart-count').text(count);
                }
            });
        </script>
    @endpush
@endsection
