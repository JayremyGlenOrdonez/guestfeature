@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 60px 0; text-align: center;">
        <div
            style="background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <i class="fas fa-check-circle" style="font-size: 5em; color: #28a745; margin-bottom: 25px;"></i>
            {{-- Green check icon --}}
            <h2 style="font-size: 2.5em; color: #333; margin-bottom: 20px;">Order Placed Successfully!</h2>
            <p style="font-size: 1.2em; color: #555; margin-bottom: 30px;">Thank you for your purchase! Your order has been
                received and is being processed.</p>



            <div style="margin-top: 40px; display: flex; justify-content: center; gap: 20px;">

                <a href="{{ route('clientHome') }}" class="btn-more"
                    style="background-color: #5b21b6; /* Changed to primary color */">Continue Shopping</a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    {{-- Para lumabas ang Font Awesome icons kung hindi pa nalagay sa app.blade.php --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
