<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingreso al sistema</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-green-100 my-4 text-center">
        <h1 class="text-lg font-semibold m-4 uppercase">Ingreso al sistema</h1>
    </div>
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                {{-- Mostrar mensajes de error --}}
                <div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="badge badge-warning">{{$error}}</div>
                        @endforeach
                    @endif
                </div>
                <form action="{{route('login.store')}}" method="POST">
                    @csrf
                    {{-- Email --}}
                    <div class="form-control">
                        <label class="label" for="email">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" placeholder="Escriba su email" maxlength="255" class="input input-sm input-bordered" required value="{{old('email')}}" />
                    </div>
                    {{-- Contraseña --}}
                    <div class="form-control">
                        <label class="label" for="password">
                            <span class="label-text">Password</span>
                        </label>
                        <input type="password" name="password" placeholder="Mínimo 5 caracteres" maxlength="45" class="input input-sm input-bordered" required />
                    </div>
                    {{-- Botón Ingresar --}}
                    <div class="form-control mt-6">
                        <button class="btn btn-sm btn-primary">Ingresar</button>
                        <a href="{{ route('inicio') }}" class="btn btn-sm btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
