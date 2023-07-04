<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct()
    {
        //Sólo los usuarios autenticados y rol admin pueden acceder a todas las rutas de este controlador
        //los usuarios autenticados y rol diferente de admin pueden acceder únicamente a la ruta index de este controlador
        $this->middleware('auth');
        $this->middleware('admin')->except('index');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('productos.index', ['productos' => $productos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        //si no existen categorias, redirigir a la vista de creación de categorias
        if ($categorias->isEmpty()) {
            return redirect()->route('categorias.create')->with('info', 'Primero debes crear una categoría');
        }
        return view('productos.create', ['categorias' => $categorias]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Producto::create($request->all());
        return redirect()->route('productos.index')->with('info', 'Producto creado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', ['producto' => $producto, 'categorias' => $categorias]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('info', 'Producto actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('info', 'Producto eliminado con éxito');
    }
}
