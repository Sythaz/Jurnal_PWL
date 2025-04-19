@extends('layouts.template')

{{-- @section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Sistem Pencatat Jurnal Kegiatan!</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            Jadikan journal harianmu sebagai cermin pertumbuhan, saksi perjalanan, dan harta karun kenangan yang tak ternilai.
        </div>
    </div>
@endsection --}}
@section('content')
    <div class="tampilan">
        <div class="tampilan-content">
            <h1 class="welcome-text">Satu Hari,<br>Satu Cerita,<br>Satu Jejak Kehidupan.</h1>
            <p class="deskripsi">Jadikan journal harianmu sebagai cermin pertumbuhan, saksi<br>perjalanan, dan harta karun kenangan yang tak ternilai.</p>
            <div class="btn btn-dark">
                <a href="kegiatan" class="text-white">Get started</a>
            </div>
        </div>
    </div>
@endsection

<style>
    body {
        /* Agar tidak bisa di scroll */
        overflow: hidden;
    }

    .tampilan {
        background-image: url('{{ asset('images/background-welcome-page.jpg') }}');
        background-size: cover;
        background-position: center;
        height: 100vh;
        display: flex;
        align-items: center;
        /* justify-content: center; */
        padding: 0 5rem;
        color: white;
    }

    .welcome-text {
        font-family: 'Montserrat', sans-serif;
        font-size: 3rem;
        font-weight: bold;
    }

    .deskripsi {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        margin: 1rem 0;
    }
</style>



