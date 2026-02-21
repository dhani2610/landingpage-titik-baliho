<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Petani Besi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">

    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">
    <style>
        body { background-color: #F8F9FA; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Inter', sans-serif;}
        .login-card { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border-top: 4px solid #D90429; }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="fw-bold text-center mb-4">ADMIN <span style="color: #D90429;">LOGIN</span></h3>

        @if ($errors->any())
            <div class="alert alert-danger" style="font-size: 14px;">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size: 14px;">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold" style="font-size: 14px;">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn w-100 text-white fw-bold" style="background-color: #D90429;">Masuk Panel</button>
        </form>
        <div class="text-center mt-3"><a href="{{ route('landing') }}" class="text-muted text-decoration-none" style="font-size: 14px;">&larr; Kembali ke Beranda</a></div>
    </div>
</body>
</html>
