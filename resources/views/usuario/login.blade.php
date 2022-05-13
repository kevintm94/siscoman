<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LogIn Jegrill</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-form">
        <form method="POST" action="">
            @csrf
            <div class="form-name">
                <span>INICIO DE SESION</span>
            </div>
            <div class="form-group">
                <input type="text" class="form-control item" id="usuario" name="usuario" placeholder="Usuario" required value="{{ old('usuario') }}"  autofocus>
            <div class="form-group">
                <input type="password" class="form-control item" id="password" name="password" placeholder="Contraseña" required>
                @error('password')
                    <span class="text-danger" role="alert">
                        <small><strong>{{ $message }}</strong></small>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="checkbox" class="form-check-input item" id="remember" name="remember" name="remember">
                <label for="remember" class="form-check-label">Recuerda mi sesión</label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block login-account">Ingresar</button>
            </div>
        </form>
    </div>
    
    {{--<main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Login') }}</div>
        
                            <div class="card-body">
                                <form method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="usuario" class="col-md-4 col-form-label text-md-right">Usuario</label>
                                        <div class="col-md-6">
                                            <input id="usuario" class="form-control @error('usuario') is-invalid @enderror" type="text" name="usuario" required value="{{ old('usuario') }}"  autofocus>
                                            @error('usuario')
                                                <span class="text-danger" role="alert">
                                                    <small><strong>{{ $message }}</strong></small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                        <div class="col-md-6">
                                            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" required name="password" >
                                            @error('password')
                                                <span class="text-danger" role="alert">
                                                    <small><strong>{{ $message }}</strong></small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input id="remember" class="form-check-input" type="checkbox" name="remember" >
                                                <label for="remember" class="form-check-label">Recuerda mi sesión</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-info">Ingresar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </main>--}}
</body>
</html>
