@extends('client.layouts.app')

@section('content')
    <section class="container" style="padding: 60px 0;">
        <h2 class="section-heading">About Our Shop</h2>

        <div
            style="background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); line-height: 1.8; margin-bottom: 50px;">
            <p style="font-size: 1.1em; color: #555; margin-bottom: 20px;">
                Welcome to **{{ $shop->name_shop ?? 'Your Shop Name' }}LAGAY DITO NG ABOUT US DETAIL OR ABOUT US NATEN
            </p>
            <p style="font-size: 1.1em; color: #555; margin-bottom: 20px;">
                MORE DETAIL PA DITO NO IDEA SA EXACT APP NATIN EH </p>
            <p style="font-size: 1.1em; color: #555;">
                You can reach us at:
                <br>
                <strong>Phone:</strong> {{ $shop->phone ?? 'N/A' }}
                <br>
                <strong>Address:</strong> {{ $shop->address ?? 'N/A' }}
                <br>
                <strong>Email:</strong> {{ $shop->email ?? 'info@yourshop.com' }} {{-- Assuming shop has email, if not, add it to Shop model/migration --}}
            </p>
        </div>

        {{-- NEW SECTION: Our Team --}}
        <h2 class="section-heading" style="margin-top: 50px;">Our Team</h2>

        <div class="team-grid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; justify-content: center;">
            {{-- Team Member 1 --}}
            <div class="team-member-card"
                style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); text-align: center;">
                <img src="{{ asset('images/team-member-1.jpg') }}" alt="Team Member 1"
                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #5b21b6;">
                <h3 style="font-size: 1.5em; color: #333; margin-bottom: 5px;">Rhenz</h3>
                <p style="font-size: 1em; color: #777; margin-bottom: 10px;">Role: Admin Task</p>
                <p style="font-size: 1em; color: #5b21b6; font-weight: bold;">rhenz..gmail.com</p>
            </div>

            {{-- Team Member 2 --}}
            <div class="team-member-card"
                style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); text-align: center;">
                <img src="{{ asset('images/team-member-2.jpg') }}" alt="Team Member 2"
                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #5b21b6;">
                <h3 style="font-size: 1.5em; color: #333; margin-bottom: 5px;">Jayremy </h3>
                <p style="font-size: 1em; color: #777; margin-bottom: 10px;">Role: GuestFeature</p>
                <p style="font-size: 1em; color: #5b21b6; font-weight: bold;">Jayremy@gmail.com</p>
            </div>

            {{-- Team Member 3 --}}
            <div class="team-member-card"
                style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); text-align: center;">
                <img src="{{ asset('images/team-member-3.jpg') }}" alt="Team Member 3"
                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #5b21b6;">
                <h3 style="font-size: 1.5em; color: #333; margin-bottom: 5px;">Jelan</h3>
                <p style="font-size: 1em; color: #777; margin-bottom: 10px;">Role: User Feature</p>
                <p style="font-size: 1em; color: #5b21b6; font-weight: bold;">jelan@gmail.com</p>
            </div>

            {{-- Team Member 4 --}}
            <div class="team-member-card"
                style="background-color: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); text-align: center;">
                <img src="{{ asset('images/team-member-4.jpg') }}" alt="Team Member 4"
                    style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 3px solid #5b21b6;">
                <h3 style="font-size: 1.5em; color: #333; margin-bottom: 5px;">Gian</h3>
                <p style="font-size: 1em; color: #777; margin-bottom: 10px;">Role: User Feature</p>
                <p style="font-size: 1em; color: #5b21b6; font-weight: bold;">sgian@gmail.com</p>
            </div>
        </div>
    </section>
@endsection
