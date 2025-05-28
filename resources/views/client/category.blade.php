@extends('client.layouts.app')

@section('content')
    <section class="container">
        <h2 class="section-heading">All Product Categories</h2>

        @if ($category->count() > 0)
            <div class="category-grid">
                @foreach ($category as $cat)
                    {{-- $category galing sa ClientController->category() --}}
                    <div class="category-card">
                        {{-- Optional: Category Image (kung may 'path' attribute ang Category model mo para sa image) --}}
                        {{-- <img src="{{ asset($cat->path ?? 'images/default-category.png') }}" alt="{{ $cat->name }}" style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;"> --}}
                        <h3><a href="{{ route('clientCategoryProducts', Str::slug($cat->name)) }}">{{ $cat->name }}</a>
                        </h3>
                        {{-- Pwede ring maglagay ng count ng products per category dito, hal. ({{ $cat->product->count() }} products) --}}
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="pagination" style="margin-top: 40px; text-align: center;">
                {{ $category->links() }}
            </div>
        @else
            <p style="text-align: center;">No categories available at the moment.</p>
        @endif
    </section>
@endsection
