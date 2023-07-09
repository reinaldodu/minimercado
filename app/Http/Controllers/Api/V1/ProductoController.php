<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct()
    {
        //Sólo los usuarios autenticados y rol admin pueden acceder a todas las rutas de este controlador
        //los usuarios autenticados y rol diferente de admin pueden acceder únicamente a la ruta index de este controlador
        $this->middleware('auth:sanctum'); //Tipo de autenticación: sanctum (token)
        $this->middleware('admin')->except('index');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Producto::all(), 200); //200: OK
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validar los datos de entrada
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'precio' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id'], //exists valida que el id exista en la tabla categorias
            'imagen' => ['nullable', 'image', 'max:4096']
        ]);
        //Crear el producto
        $producto = Producto::create($datos);
        //Guardar la imagen si existe
        if ($request->hasFile('imagen')) {
            $request->file('imagen')->move(public_path('images/productos'), 'producto_'.$producto->id.'.jpg');
        }
        return response()->json(['success' => true, 'message' => 'Producto creado'], 201); //201: Created
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return response()->json($producto, 200); //200: OK
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //Validar los datos de entrada
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'precio' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'categoria_id' => ['required', 'integer', 'exists:categorias,id']
        ]);
        //Actualizar el producto
        $producto->update($datos);
        return response()->json(['success' => true, 'message' => 'Producto actualizado'], 200); //200: OK
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //si el producto tiene pedidos asociados, no se puede eliminar
        if ($producto->pedidos->count() > 0) {
            return response()->json(['success' => false, 'message' => 'No se puede eliminar el producto porque tiene pedidos asociados'], 409); //409: Conflict
        }
        $producto->delete();
        //eliminar imagen si existe
        if (file_exists(public_path('images/productos/producto_'.$producto->id.'.jpg'))) {
            unlink(public_path('images/productos/producto_'.$producto->id.'.jpg'));
        }
        return response()->json(['success' => true, 'message' => 'Producto eliminado'], 204); //204: No content
    }
}
