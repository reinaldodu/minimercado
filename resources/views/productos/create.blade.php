@extends('layouts.app')
@section('titulo', 'Crear Producto')
@section('cabecera', 'Crear Producto')

@section('contenido') 
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <form action="{{route('productos.store')}}" method="POST">
                    @csrf
                    {{-- Categoria --}}
                    <div class="form-control">
                        <label class="label" for="categoria_id">
                            <span class="label-text">Categorías</span>
                        </label>
                        <select name="categoria_id" class="select select-bordered">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Nombre --}}
                    <div class="form-control">
                        <label class="label" for="nombre">
                            <span class="label-text">Nombre</span>
                        </label>
                        <input type="text" name="nombre" placeholder="Nombre del producto" maxlength="100" class="input input-bordered" required />
                    </div>
                    {{-- Descripcion --}}
                    <div class="form-control">
                        <label class="label" for="descripcion">
                            <span class="label-text">Descripción</span>
                        </label>
                        <input type="text" name="descripcion" placeholder="Escriba la descripción" maxlength="255" class="input input-bordered" />
                    </div>
                    {{-- Precio --}}
                    <div class="form-control">
                        <label class="label" for="precio">
                            <span class="label-text">Precio</span>
                        </label>
                        <input type="number" name="precio" placeholder="Escriba el precio" class="input input-bordered" required />
                    </div>
                    {{-- Stock --}}
                    <div class="form-control">
                        <label class="label" for="stock">
                            <span class="label-text">Stock</span>
                        </label>
                        <input type="number" name="stock" placeholder="Escriba el stock" class="input input-bordered" required />
                    </div>
                    
                    <div class="form-control mt-6">
                        <button class="btn btn-primary">Crear Producto</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection