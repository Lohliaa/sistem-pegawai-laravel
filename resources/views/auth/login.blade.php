@extends('layouts.app')

@section('title', 'Login - Sistem Pegawai Permata')

@section('content')
<style>
    body {
        background: #f8f9fa !important;
        display: flex;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }
    .login-container {
        width: 100%;
        padding: 15px;
    }
    .login-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .login-header {
        background: #001f3f !important; /* Navy */
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
    }
    .login-header h3 {
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .login-header p {
        color: #a0c4ff;
        margin-bottom: 0;
    }
    .btn-login {
        background-color: #001f3f;
        border: none;
        padding: 0.75rem;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    .btn-login:hover {
        background-color: #003366;
    }
    .form-control:focus {
        border-color: #001f3f;
        box-shadow: 0 0 0 0.2rem rgba(0, 31, 63, 0.25);
    }
</style>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <div class="login-header">
                    <h3 class="mb-1">LOGIN</h3>
                    <p class="small">Sistem Kepegawaian Permata</p>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 bg-danger-subtle text-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold text-secondary small">USERNAME</label>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="form-control form-control-lg @error('username') is-invalid @enderror" 
                                   value="{{ old('username') }}"
                                   placeholder="Masukkan username"
                                   required 
                                   autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-secondary small">PASSWORD</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   placeholder="Masukkan password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-login">MASUK</button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4 text-muted small">
                &copy; {{ date('Y') }} Yayasan Permata Mojokerto
            </div>
        </div>
    </div>
</div>
@endsection
