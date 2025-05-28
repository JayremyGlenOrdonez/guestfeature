@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 40px 0;">
        @if ($product)
            <div class="product-detail-container" style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: center;">
                {{-- Product Image Gallery --}}
                <div class="product-images" style="flex: 1; min-width: 300px; max-width: 500px; text-align: center;">
                    @if ($product->productImage->count() > 0)
                        {{-- Main Image --}}
                        <img id="main-product-image" src="{{ asset($product->productImage->first()->path) }}"
                            alt="{{ $product->title }}"
                            style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

                        {{-- Thumbnails --}}
                        <div class="thumbnail-images"
                            style="display: flex; gap: 10px; justify-content: center; margin-top: 15px;">
                            @foreach ($product->productImage as $image)
                                <img src="{{ asset($image->path) }}" alt="{{ $product->title }} thumbnail" class="thumbnail"
                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; cursor: pointer; border: 1px solid #ddd; transition: border 0.2s;"
                                    onclick="changeMainImage(this.src)">
                            @endforeach
                        </div>
                    @else
                        <img src="{{ asset('images/default-product.png') }}" alt="Default Product Image"
                            style="max-width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    @endif
                </div>

                {{-- Product Details --}}
                <div class="product-info" style="flex: 1; min-width: 300px; max-width: 600px;">
                    <h1 style="font-size: 2.5em; color: #333; margin-bottom: 10px;">{{ $product->title }}</h1>
                    <p style="font-size: 1.8em; color: #5b21b6; font-weight: bold; margin-bottom: 20px;">
                        ₱{{ number_format($product->price, 2) }}</p>
                    <p style="font-size: 1.1em; color: #555; line-height: 1.6; margin-bottom: 25px;">{{ $product->desc }}
                    </p>

                    <div class="stock-info" style="font-size: 1.1em; margin-bottom: 25px;">
                        @if ($product->stock > 0)
                            <span style="color: green; font-weight: bold;">In Stock ({{ $product->stock }} available)</span>
                        @else
                            <span style="color: red; font-weight: bold;">Out of Stock</span>
                        @endif
                    </div>

                    <div class="add-to-cart-section" style="display: flex; align-items: center; gap: 20px;">
                        <label for="quantity" style="font-weight: bold; font-size: 1.1em;">Quantity:</label>
                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                            style="width: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 1em;">
                        <button id="add-to-cart-btn-detail" class="add-to-cart-btn" data-product-id="{{ $product->id }}"
                            data-product-stock="{{ $product->stock }}" data-product-title="{{ $product->title }}"
                            style="padding: 12px 25px; background-color: #5b21b6; color: white; border: none; border-radius: 30px; cursor: pointer; font-size: 1.1em; font-weight: bold; transition: background-color 0.3s;"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            {{-- Recommendation Products Section --}}
            <section class="container" style="margin-top: 80px;">
                <h2 class="section-heading">You might also like</h2>
                <div class="product-grid">
                    @forelse($recomendationProducts as $recProduct)
                        <div class="product-card">
                            <img src="{{ asset($recProduct->productImage->first()->path ?? 'images/default-product.png') }}"
                                alt="{{ $recProduct->title }}">
                            <h3><a
                                    href="{{ route('clientProductDetail', Str::slug($recProduct->title)) }}">{{ $recProduct->title }}</a>
                            </h3>
                            <p>₱{{ number_format($recProduct->price, 2) }}</p>
                        </div>
                    @empty
                        <p style="text-align: center; grid-column: 1 / -1;">No recommendations available.</p>
                    @endforelse
                </div>
            </section>
        @else
            <p style="text-align: center; font-size: 1.2em; color: #888;">Product not found.</p>
        @endif
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Function to change the main product image when a thumbnail is clicked
            function changeMainImage(src) {
                document.getElementById('main-product-image').src = src;
            }

            $(document).ready(function() {
                // Highlight current thumbnail (optional)
                $('.thumbnail').on('click', function() {
                    $('.thumbnail').css('border', '1px solid #ddd'); // Reset all
                    $(this).css('border', '1px solid #5b21b6'); // Highlight clicked
                });

                $('#add-to-cart-btn-detail').on('click', function() {
                    let productId = $(this).data('product-id');
                    let productStock = $(this).data('product-stock');
                    let productTitle = $(this).data('product-title');
                    let quantity = parseInt($('#quantity').val());

                    if (isNaN(quantity) || quantity < 1) {
                        alert('Please enter a valid quantity.');
                        return;
                    }

                    if (quantity > productStock) {
                        alert('You cannot add more than ' + productStock + ' of ' + productTitle +
                            ' to your cart.');
                        return;
                    }
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
                                alert(productTitle + ' (x' + quantity + ') added to cart!');
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

                function updateCartCount(count) {
                    $('#cart-count').text(count);
                }
            });
        </script>
    @endpush
@endsection
