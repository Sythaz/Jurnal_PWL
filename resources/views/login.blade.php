@extends('layouts.template')
<?php
    session()->forget('user_id');
?>

@section('content')
    @if (session('errorAuth'))
        <div class="alert alert-danger" style="margin: auto; border-radius: 0">
            {{ session('errorAuth') }}
        </div>
    @endif

    <div class="tampilan">
        <div class="tampilan-content">
            <h1 class="welcome-text">Satu Hari,<br>Satu Cerita,<br>Satu Jejak Kehidupan.</h1>
            <p class="deskripsi">Jadikan journal harianmu sebagai cermin pertumbuhan, saksi<br>perjalanan, dan harta karun
                kenangan yang tak ternilai.</p>
            <div class="btn btn-dark">
                <a href="login" class="text-white">Get started</a>
            </div>
        </div>

        <!-- /.login-logo -->
        <div class="card bg-black" style="width: 40vw; min-height: 50vh; margin: auto 0 auto auto; border-radius: 10px">
            <div class="card-body bg-dark login-card-body d-flex justify-content-center flex-column"
                style="border-radius: 10px ">
                <div class="login-logo font-weight-bold"><b>Sign In</b></div>
                <p class="login-box-msg">Sign in untuk memulai jurnaling mu</p>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <label for="username" class="font-weight-normal">Masukan username anda</label>
                    <div class="input-group mb-3">
                        <input name="username" type="text" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <label name="password" for="password" class="font-weight-normal">Masukan password anda</label>
                    <div class="input-group mb-3">
                        <input name="password" type="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Continue</button>
                </form>
            </div>
            <!-- /.login-card-body -->
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
