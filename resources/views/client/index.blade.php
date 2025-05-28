@extends('client.layouts.app') {{-- Ito ang nag-e-extend ng master layout --}}

@section('content')
    {{-- Dito ilalagay ang content na papasok sa @yield('content') sa master layout --}}

    {{-- Hero Section --}}
    <section class="hero-section">
        <h1>{{ $shop->desc ?? 'Sustainable yet stylish lifestyle' }}</h1> {{-- Kung may shop description, gamitin to --}}
        <p>Autem eveniet eum delectus pariatur. Et maiores et sed placeat quas voluptatum qui. Suscipit veritatis est
            provident illum commodi. Voluptas quos culpa. Nobis ex nihil laudantium.</p>
        <a href="{{ route('clientProducts') }}" class="btn-more">Shop Now <span class="arrow">&rarr;</span></a>
    </section>

    {{-- "Why Choose Us" Section --}}
    <section class="container">
        <h2 class="section-heading">Why Choose Us</h2>
        <div style="text-align: center; padding: 30px;">
            <p>Dito ilalagay ang content tungkol sa "Why Choose Us" section mo.</p>
            <p>Pwedeng mga benefits, unique selling points, etc.</p>
        </div>
    </section>

    {{-- Products Section Heading (New addition) --}}
    <section class="container"> {{-- Added a new container for the Products section heading and button --}}
        <h2 class="section-heading">Our Products</h2> {{-- Changed heading to "Our Products" --}}
        {{-- "More Products" button - Placed here to be under its own heading --}}
        <a href="{{ route('clientProducts') }}" class="btn-more">View All Products <span class="arrow">&rarr;</span></a>
        {{-- Changed button text to be more descriptive --}}
    </section>

    {{-- Categories Section --}}
    <section class="container">
        <h2 class="section-heading">Categories</h2> {{-- Changed heading to "Categories" for consistency --}}
        <div class="category-grid">
            @forelse($category as $cat)
                <div class="category-card">
                    <h3><a href="{{ route('clientCategoryProducts', Str::slug($cat->name)) }}">{{ $cat->name }}</a></h3>
                </div>
            @empty
                <p style="text-align: center; grid-column: 1 / -1;">No categories found.</p>
            @endforelse
        </div>
        <a href="{{ route('clientCategory') }}" class="btn-more">More Categories <span class="arrow">&rarr;</span></a>
        {{-- Changed button text --}}
    </section>
@endsection
