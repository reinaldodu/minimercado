@extends('layouts.app')
@section('titulo', 'Editar Producto')
@section('cabecera', 'Editar Producto - ' . $producto->nombre)

@section('contenido')
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <form action="{{route('productos.update', $producto)}}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Categoria --}}
                    <div class="form-control">
                        <label class="label" for="categoria_id">
                            <span class="label-text">Categorías</span>
                        </label>
                        <select name="categoria_id" class="select select-bordered">
                            @foreach ($categorias as $categoria)
                                @if ($categoria->id == $producto->categoria_id)
                                    <option value="{{$categoria->id}}" selected>{{$categoria->nombre}}</option>
                                @else
                                    <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{-- Nombre --}}
                    <div class="form-control">
                    <label class="label" for="nombre">
                        <span class="label-text">Nombre</span>
                    </label>
                    <input type="text" name="nombre" placeholder="Nombre del producto" class="input input-bordered" maxlength="100" value="{{$producto->nombre}}" required />
                    </div>
                    {{-- Descripcion --}}
                    <div class="form-control">
                        <label class="label" for="descripcion">
                            <span class="label-text">Descripción</span>
                        </label>
                        <input type="text" name="descripcion" placeholder="Escriba la descripción" class="input input-bordered" maxlength="255" value="{{$producto->descripcion}}" />
                    </div>
                    {{-- Precio --}}
                    <div class="form-control">
                        <label class="label" for="precio">
                            <span class="label-text">Precio</span>
                        </label>
                        <input type="number" name="precio" placeholder="Escriba el precio" class="input input-bordered" value="{{$producto->precio}}" required />
                    </div>
                    {{-- Stock --}}
                    <div class="form-control">
                        <label class="label" for="stock">
                            <span class="label-text">Stock</span>
                        </label>
                        <input type="number" name="stock" placeholder="Escriba el stock" class="input input-bordered" value="{{$producto->stock}}" required />
                    </div>

                    <div class="form-control mt-6">
                        <button class="btn btn-primary">Actualizar Producto</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection