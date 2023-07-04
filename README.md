# Proyecto MiniMercado Laravel

El desarrollo de la aplicación web **Minimercado** tiene como objetivo principal, conocer y aplicar las operaciones básicas (CRUD) en una base de datos relacional (Mysql), usando el patrón **MVC** (Modelo-Vista-Controlador) a través del framework [Laravel](https://laravel.com/). 

> *Recuerde el significado del acrónimo **CRUD**: (Create - Read - Update - Delete) que son las operaciones básicas en una base de datos para crear, leer, actualizar y eliminar registros.*
> 

**Tabla de contenidos**

## Descripción general

La aplicación **Minimercado** será de tipo "**full stack**", lo que implica el desarrollo tanto del **frontend** (la interfaz de usuario, usando vistas) como del **backend** (la lógica del servidor, usando modelos y controladores).

El sistema se desarrollará a partir de **dos fases**:

En la **primera fase** se realizarán las configuraciones iniciales de la aplicación web y se creará el sistema de administración de categorías y productos, y en la **segunda fase** se creará el sistema de autenticación de usuarios (registro y login) y el sistema para generar los pedidos.

Se contará con dos tipos de usuarios (roles): el **administrador** (rol: **admin**), que tiene todos los permisos sobre el sistema para crear, consultar y actualizar productos, categorías y usuarios; y el **cliente** (rol: **user**)**,** que puede acceder al sistema para generar un nuevo pedido.

## Fase No.1: Administración de categorías y productos

En esta fase estaremos usando las operaciones básicas (CRUD) de una base de datos, para administrar las categorías y productos. También se crearán las rutas necesarias para ejecutar dichas operaciones (usando el  archivo **`routes/web.php`**).  Adicionalmente estaremos creando las vistas (frontend) apoyándonos con otro framework de última generación llamado [Tailwindcss](https://tailwindcss.com).

### Configuración de las variables de entorno

Para iniciar configuraremos algunas variables globales que utilizará nuestro sistema, como el nombre, url y la configuración de la base de datos. 

Para ello abrimos el archivo **.env** y ****modificamos las siguientes variables:

| Variable | Descripción |
| --- | --- |
| APP_NAME=Minimercado | Contiene el nombre de nuestra aplicación. Si el nombre tiene más de una palabra con espacios se debe escribir usando comillas (Ej: “Mini Mercado”) |
| APP_URL=http://minimercado.test | La URL raíz de nuestra aplicación.  Es recomendable dejar habilitada en las preferencias de Laragon la creación de host virtuales de tipo .test |
| DB_CONNECTION=mysql | Tipo de base de datos a usar. En nuestro caso mysql |
| DB_HOST=127.0.0.1 | Host del servidor de la base de datos.  Se deja igual. |
| DB_PORT=3306 | Puerto del servidor de la base de datos.  En el caso de mysql el puerto por defecto es el 3306. |
| DB_DATABASE=minimercado | Nombre de la base de datos a usar.  Llamaremos a nuestra base de datos minimercado. |
| DB_USERNAME=root | Usuario de la base de datos.  Si estamos usando Laragon dejamos el usuario como root. |
| DB_PASSWORD= | Password del usuario de la base de datos. Si estamos usando Laragon dejamos el campo en blanco. |


### Instalación y configuración de Tailwindcss y DaisyUI

Tailwindcss es un framework de CSS de última generación que permite crear estilos personalizados en una aplicación web de manera fácil y rápida. Proporciona una gran variedad de clases predefinidas que se pueden utilizar para crear diseños CSS personalizados.

La instalación de Tailwindcss en Laravel es muy sencilla.  Puede seguir la siguiente guía del sitio oficial (a partir del punto 2).

[Install Tailwind CSS with Laravel - Tailwind CSS](https://tailwindcss.com/docs/guides/laravel)

Ahora instalaremos **[DaisyUI](https://daisyui.com/)** que es un conjunto de componentes de diseño enriquecido para Tailwind CSS, ayudando a crear diseños modernos y limpios de manera rápida y sencilla.   Para realizar la instalación ingrese al siguiente enlace:

[Install daisyUI as a Tailwind CSS plugin — Tailwind CSS Components](https://daisyui.com/docs/install/)

Al finalizar la instalación de Tailwindcss y DaisyUI ejecutamos el comando **`npm run dev`** para iniciar el proyecto en modo desarrollo y ver en tiempo real desde navegador los cambios realizados en el proyecto.

> ***Recuerda** la próxima vez que abra el proyecto ejecutar el comando **`npm run dev`** para iniciar el proyecto en modo desarrollo y ver los cambios en tiempo real.*
> 

### Creando un layout para nuestra aplicación

Para definir una estructura común de las páginas de nuestra aplicación, crearemos la plantilla **app.blade.php** dentro del directorio **resources/views/layouts** (se debe crear primero la carpeta layouts), con el fin de tener un código más limpio en nuestras vistas (frontend).  La plantilla tendrá el siguiente código HTML:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titulo', 'Minimercado')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <header>
        {{-- navbar --}}
        @include('partials.navigation')
    </header>
    <main>
        {{-- Título Cabecera --}}
        <div class="bg-green-100 my-4 text-center">
            <h1 class="text-lg font-semibold m-4 uppercase">@yield('cabecera')</h1>
        </div>
        {{-- Mensajes informativos --}}
        @if (session('info'))
            <div class="flex justify-end m-4">
                <div class="alert alert-info w-96">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{session('info')}}</span>
                </div>
            </div>
        @endif
        {{-- Contenido --}}
        @yield('contenido')
    </main>
    <footer class="footer footer-center p-4 bg-base-300 text-base-content mt-12">
        <div>
          <p>Copyright © 2023 - MiniMercado</p>
        </div>
    </footer>
</body>
</html>
```

Como se observa en el código anterior, la plantilla **app.blade.php** está estructurada en 3 partes: 

1. **Header:** contiene la barra de navegación, la cual tiene el logo, nombre de la aplicación y el menú principal.  Esta navbar se guardará en un archivo externo con el nombre **navigation.blade.php** en la siguiente ruta: `resources/views/partials/navigation.blade.php` El código de la barra de navegación es el siguiente:

```html
<div class="navbar bg-orange-200">
  <div class="flex-1 ml-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25" />
    </svg>
    
    <a href="{{route('inicio')}}" class="btn btn-ghost btn-sm normal-case text-sm">MiniMercado</a>
  </div>
  <div class="flex-none">
    <ul class="menu menu-horizontal px-1 mr-6 space-x-2">
      <li><a href="{{ route('productos.index') }}">Productos</a></li>
      <li><a href="{{ route('categorias.index') }}">Categorías</a></li>
    </ul>
  </div>
</div>
```

1. **Main:** es la sección principal de la aplicación, y estará formada por un título principal, mensajes informativos para el usuario y el contendio general (en donde se muestra el contenido de las acciones CRUD).
2. **Footer:** es el pie de página de la aplicación, y podrá contener información de contacto y redes sociales.

### Creación de los modelos, migraciones y controladores

A través del comando `php artisan` de Laravel se puede crear rápidamente la estructura inicial para los modelos, migraciones y controladores necesarios. Iniciemos creando el modelo, migración y controlador para la **tabla categorias**:

```bash
php artisan make:model Categoria -mc --resource
```

Se debe tener en cuenta que el nombre del modelo usa la notación PascalCase (la primera letra de cada palabra se escribe en mayúscula) y se escribe en singular.  La opción **-mc** lo que hace es crear los archivos de migración y controlador para el modelo, y la opción **--resource** se usa para crear la estructura inicial de los métodos para el CRUD en el controlador.

A continuación realizamos algunas configuraciones en el modelo **Categoria** que se encuentra en `**app/Models/Categoria.php**`

```php
class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion'];

    public function productos()
    {
        return $this->hasMany(Producto::class);  // Una categoria tiene muchos productos
    }
}
```

La opción **protected $fillable** le dice al modelo los campos “autorizados” a la hora de crear o editar una categoría.  Luego se crea el método público **productos()** para generar la relación de Eloquent **hasMany** (una categoría tiene muchos productos).

Ver más sobre las relaciones en Eloquent…

[Laravel - The PHP Framework For Web Artisans](https://laravel.com/docs/10.x/eloquent-relationships)

Ahora hacemos lo mismo para crear la estructura inicial del modelo, migración y controlador para la **tabla productos**.

```bash
php artisan make:model Producto -mc --resource
```

De igual forma hacemos las configuraciones iniciales en el modelo **Producto** que se encuentra en `**app/Models/Producto.php**`

```php
class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);  // Un producto pertenece a una categoria
    }
}
```

En este caso la relación de eloquent  **belongsTo** le dice al modelo que un producto pertenece a una categoría.  Las relaciones de eloquent creadas en los modelos, nos facilitarán a futuro las consultas que realicemos a la base de datos.

**Migraciones: Configurando los campos de las tablas**

Ahora crearemos la estructura de los campos de las tablas a través de los archivos de migraciones que se encuentran en el directorio `database/migrations`

**Campos para la tabla categorias:**


```php
public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }
```

**Campos para la tabla productos:**


```php
public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('descripcion')->nullable(); //El campo puede ser nulo
            $table->integer('precio');
            $table->integer('stock');
            $table->foreignId('categoria_id')->constrained();
            $table->timestamps();
        });
    }
```

Después de configurar los campos necesarios, ejecutaremos el comando `**php artisan migrate**` en la terminal, para crear nuestra base de datos:

> ***Tenga en cuenta**:  Antes de ejecutar el comando, verificar que se encuentra dentro del directorio de la aplicación minimercado.*
> 


El sistema mostrará una advertencia, diciendo que no existe la base de datos ‘minimercado’ y nos preguntará si deseamos crearla.  Escribimos **yes** para confirmar, y el sistema procederá a crear la base de datos y las tablas (por defecto laravel tiene configurada algunas migraciones adicionales y creará también estas tablas).


Si desea verificar la creación de la base de datos, puede ingresar a cualquier cliente de base de datos mysql (Ej: Phpmyadmin, Mysql Workbench).

### Creación de las rutas

Las rutas forman parte de los componentes principales de nuestra aplicación y es  la puerta de entrada para interactuar con los usuarios a través del navegador.

Para crear las rutas de las categorías y productos, debemos editar el archivo `routes/web.php` y agregar las rutas para las categorias y productos, que serán de tipo **resource**, ****las cuales generan automáticamente 7 rutas para realizar las operaciones básicas con la base de datos (CRUD).  También es importante agregar en el encabezado del archivo los controladores que vamos a usar con las rutas (CategoriaController y ProductoController).

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/','welcome')->name('inicio');

Route::resource('/categorias',CategoriaController::class);  //Crea 7 rutas para el CRUD de categorias
Route::resource('/productos',ProductoController::class); //Crea 7 rutas para el CRUD de productos
```

Con el comando `php artisan route:list` podemos verificar las 7 rutas creadas por Laravel para manipular las categorias y los productos.


### Iniciando con el CRUD de Categorías:

**1. Creando una categoría:**

Si observamos con detalle las rutas creadas anteriormente, existe una ruta específica para cada acción del CRUD.  

Para el caso de la creación de nuevas categorías existe la ruta: **categorias/create** de tipo GET (ver imagen de rutas costado izquierdo).  El nombre asignado a esta ruta es **categorias.create** (ver imagen de rutas costado derecho)  y el controlador y método que responderá a dicha ruta es **CategoriaController@create** (el nombre después del @ es el método usado por el controlador).  Teniendo en cuenta lo anterior, es necesario editar el archivo **CategoriaController.php**  (específicamente en el método create) que se encuentra en el directorio `app/Http/Controllers` 

```php
public function create()
    {
        return view('categorias.create');
    }
```

Se debe tener en cuenta que cuando vamos a crear una nueva categoría debemos mostrar por pantalla un formulario para la creación de categorías.  Como vemos en el código anterior, le estamos diciendo al método create que retorne una vista llamada **create** que se encuentra dentro del directorio **categorias** (la notación de punto se usa para separar el nombre de la carpeta del nombre del archivo), la cual contendrá el formulario para crear una categoría.  

**Vista create: Formulario para la creación de categorías**

Ahora debemos crear la carpeta **categorias** dentro del directorio **resources/views** y en esta carpeta crearemos la vista con nombre **create.blade.php** la cual contendrá el formulario para la creación de categorias.

```html
@extends('layouts.app')
@section('titulo', 'Crear Categoría')
@section('cabecera', 'Crear Categoría')

@section('contenido') 
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <form action="{{route('categorias.store')}}" method="POST">
                    @csrf
                    <div class="form-control">
	                    <label class="label" for="nombre">
	                        <span class="label-text">Nombre</span>
	                    </label>
	                    <input type="text" name="nombre" placeholder="Nombre categoría" maxlength="100" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label" for="descripcion">
                            <span class="label-text">Descripción</span>
                        </label>
                        <input type="text" name="descripcion" placeholder="Escriba la descripción" maxlength="255" class="input input-bordered" />
                    </div>
                    <div class="form-control mt-6">
                        <button class="btn btn-primary">Crear Categoría</button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
```

El código anterior está utilizando la plantilla **app.blade.php,** para estructurar y reducir el código de la página de creación de una categoría.  También en el HTML estamos utilizando tailwindcss (con componentes de DaisyUI) para darle estilos al formulario.

Adicionalmente se está utilizando la opción **@csrf** para proteger nuestro formulario de un ataque Cross Site Request Forgery (CSRF).  Esto hace que Laravel cree automáticamente un token en el formulario para proteger la sesión a la hora de enviar un formulario.

La ruta utilizada al enviar el formulario (action) es **categorias.store** que es la encargada de almacenar la nueva categoría en la base de datos. En este caso debemos configurar el método **store** del controlador **CategoriaController** 

```php
public function store(Request $request)
    {
        Categoria::create($request->all());
        return to_route('categorias.index')->with('info', 'Categoría creada con éxito');
    }
```

La opción **create** del modelo **Categoría** se encarga de guadar todos los campos enviados en el formulario ($request→all()).  Después de guardar la categoría se redirige al usuario a la página **categorias.index** en donde aparece la lista de todas las categorías guardadas en el sistema. Adicionalmente se envía un mensaje al usuario: “Categoría creada con éxito”.

**2. Listando categorías:**

Si ejecutamos nuevamente el comando **`php artisan route:list`** encontraremos una ruta con nombre **categorias.index** que es la encargada de listar las categorías existentes. También observaremos que el controlador y método encargado de atender esta rutas es **CategoriaController@index.** En este caso editamos nuevamente el controlador **CategoriaController** y agregamos lo siguiente en el método **index.** 

> ***Recuerde** que los controladores se encuentran en la ruta: `app/Http/Controllers`*
> 

```php
public function index()
    {
        $categorias = Categoria::all();  //Se consultan todas las categorias
        return view('categorias.index', ['categorias' => $categorias]);
    }
```

El código anterior, del método index, está realizando una consulta de eloquent a la base de datos, por medio del modelo **Categoria**, para traer todos los registros de la tabla categorias (`Categoria::all();`) y guardarlos en la variable **$categorias**, ****para luego retornar una vista (**categorias.index.blade.php**) a la que se le pasan los registros de la consulta, por medio de la variable $categorias (en el arreglo [’categorias’ ⇒ $categorias]).

**Vista index: Listar las categorías existentes**

Ahora vamos a crear la vista **index.blade.php (**dentro de la carpeta `resources/views/categorias`**)** que será la encargada de mostrar la lista de todas las categorías guardadas en el sistema. Para ello crearemos una tabla que tendra las siguientes columnas: nombre de la categoría, descripción y acciones (para editar o eliminar una categoría).

```html
@extends('layouts.app')
@section('titulo', 'Listar Categorías')
@section('cabecera', 'Listar Categorías')

@section('contenido')
    <div class="flex justify-end m-4">
        <a href="{{ route('categorias.create') }}" class="btn btn-outline btn-sm">Crear Categoría</a>
    </div>
    <div class="flex justify-center">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->descripcion }}</td>
                        <td class="flex space-x-2">
                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-xs">Editar</a>
                            {{-- si la categoria no tiene productos asociados, se puede eliminar --}}
                            @if($categoria->productos->count() == 0)
                              <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" onclick="return confirm('¿Desea eliminar la categoría {{ $categoria->nombre }}?')" class="btn btn-error btn-xs">Eliminar</button>
                              </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
    </div>
@endsection
```

En el código anterior se puede observar que para recorrer todas las categorias se usa la directiva de blade `@foreach ($categorias as $categoria)` También se puede notar que la acción de editar una categoría se hace llamando la ruta **categorias.edit** y pasándole el id de la categoria ($categoria→id) y para eliminar una categoría se llama a la ruta **categorias.destroy** a través de un formulario que usa el método **DELETE** (`@method ('DELETE')`) y también pasandole como parámetro a la ruta el id de la categoría. Es importante recordar que al enviar información a través del formulario es necesario protegerlo contra ataques CSRF, usando la directiva **@csrf**.  Adicionalmente en esta vista estamos mostrando un botón al inicio, si deseamos agregar una nueva categoría, usando la ruta `categorias.create`

**3. Actualizando una categoría:**

Para editar una categoría necesitamos un formulario que nos muestre la información de la categoría que deseamos actualizar. Este formulario se mostrará al ingresar a la ruta de tipo GET **/categorias/{categoria}/edit** en donde {categoria} es un parámetro en la ruta que representa el id de la categoría que editaremos.

Iniciamos configuranto el método **edit()** en el controlador **CategoriaController**: 

```php
public function edit(Categoria $categoria)
    {
        return view('categorias.edit', ['categoria' => $categoria]);
    }
```

el método **edit** tiene como parámetro los datos de la categoría a editar (guardados en la variable $categoria) y después retorna la vista **categorias.edit.blade.php** con los datos de la categoría.

Luego creamos la vista **edit.blade.php** dentro de la carpeta **`resources/views/categorias`**

```html
@extends('layouts.app')
@section('titulo', 'Editar Categoría')
@section('cabecera', 'Editar Categoría ' . $categoria->nombre)

@section('contenido')
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <form action="{{route('categorias.update', $categoria->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-control">
                    <label class="label" for="nombre">
                        <span class="label-text">Nombre</span>
                    </label>
                    <input type="text" name="nombre" placeholder="Nombre categoría" class="input input-bordered" maxlength="100" value="{{$categoria->nombre}}" required />
                    </div>
                    <div class="form-control">
                        <label class="label" for="descripcion">
                            <span class="label-text">Descripción</span>
                        </label>
                        <input type="text" name="descripcion" placeholder="Escriba la descripción" class="input input-bordered" maxlength="255" value="{{$categoria->descripcion}}" />
                    </div>
                    <div class="form-control mt-6">
                        <button class="btn btn-primary">Actualizar Categoría</button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
```

En la vista **edit.blade.php** creada anteriormente podemos observar que estamos creando un formulario que llama a la ruta **categorias.update**  para guardar los datos actualizados al enviar el formulario.  Se debe tener en cuenta enviarle a la ruta el id de la categoría a editar (`'categorias.update', $categoria->id`). El método usado para enviar los datos actualizados es el método PUT (`@method ('PUT')`)

Para finalizar con el proceso de actualización debemos crear el método **update()** en **CategoriaController** que será el encargado de actualizar la información de la categoría en la base de datos.

```php
public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($request->all());
        return to_route('categorias.index')->with('info', 'Categoría actualizada con éxito');
    }
```

**4. Eliminando una categoría**

En la vista **index.blade.php** creada anteriormente, generamos el formulario para eliminar una categoría, usando la ruta **categorias.destroy** y utilizando el método DELETE.  Sólo nos resta configurar el método **destroy** en el controlador **CategoriaController** para que el sistema pueda eliminar una categoría de la base de datos.

```php
public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return to_route('categorias.index')->with('info', 'Categoría eliminada con éxito');
    }
```

Como se ve en el código anterior para eliminar una categoría solamente se llama a la función **delete()** a través de la variable $categoria y luego se redirecciona a la ruta **categorias.index** para mostrar las categorías existentes.

Ya  hemos finalizado el proceso de **CRUD** para la entidad **categorías**, ahora haremos lo mismo para los **productos**.

### CRUD para Productos

Del mismo modo como se realizó el CRUD para las categorías, realizamos los cambios en cada uno de los métodos del controlador **ProductoController** y creamos las vistas para crear, listar y editar un producto.

El código del controlador **`app/Http/Controllers/ProductoController.php`** es el siguiente:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
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
        //si no existen categorias, se redirige a la vista de creación de categorias
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
				$categorias = Categoria::orderBy('nombre')->get();
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
```

**Vista create: Crear un nuevo producto**

Para crear las vistas de productos, primero creamos una nueva carpeta con el nombre **productos** dentro de **`resources/views`** y luego iniciamos creando la vista **create.blade.php** dentro de la nueva carpeta productos (**`resources/views/productos/create.blade.php`)**

```html
@extends('layouts.app')
@section('titulo', 'Crear Producto')
@section('cabecera', 'Crear Producto')

@section('contenido') 
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <form action="{{route('productos.store')}}" method="POST">
                    @csrf
                    {{-- Lista de Categorías --}}
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
```

**Vista index: Listar los productos**

Ahora creamos el archivo **`resources/views/productos/index.blade.php`** con el siguinte código:

```html
@extends('layouts.app')
@section('titulo', 'Listar Productos')
@section('cabecera', 'Listar Productos')

@section('contenido')
    <div class="flex justify-end m-4">
        <a href="{{ route('productos.create') }}" class="btn btn-outline btn-sm">Crear Producto</a>
    </div>
    <div class="flex justify-center mx-6">
        <div class="grid grid-cols-4 gap-6">
            @foreach ($productos as $producto)
                <div class="card w-64 bg-base-100 shadow-xl">
                    <figure><img src="https://source.unsplash.com/random/400x200/?{{$producto->categoria->nombre}}&sig={{$producto->id}}" alt="{{$producto->nombre}}"></figure>
                    <div class="card-body">
                        <h2 class="card-title">{{$producto->nombre}}</h2>
                        <div class="badge badge-success badge-outline">Categoría: {{$producto->categoria->nombre}}</div>
                        <p>{{Str::limit($producto->descripcion, 80)}}</p>

                        {{-- precio y stock--}}
                        <div class="flex space-x-4">
                            <div class="badge badge-neutral">${{number_format($producto->precio, 0, ',', '.')}}</div>
                            <div class="badge badge-ghost">{{$producto->stock}} en stock</div>
                        </div>
                    
                        <div class="card-actions justify-end mt-5">
                            {{-- Editar --}}
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-xs">Editar</a>
                            {{-- Eliminar --}}
                            <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Desea eliminar el producto {{ $producto->nombre }}?')" class="btn btn-error btn-xs">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
```

Para listar los productos estamos usando el componente **card** de DaisyUI para ver la información de cada producto dentro de una card (tarjeta), con las opciones de editar y eliminar.  En la lista de productos también se usa la clase **grid-cols-4** de tailwindcss para organizar los productos en un grid de 4 columnas.

Así se ve la vista de los productos en 4 columas…


**Vista edit: Editar un producto**

```html
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
                    {{-- Lista de Categorías --}}
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
```

## Fase 2: Sistema de autenticación y administración de pedidos

Durante la fase 2 estaremos creando el sistema de autenticación de usuarios (registro y login) y el sistema para administrar los pedidos.

### Registro, login y perfil de usuario

Ahora iniciaremos con la creación del sistema de autenticación, para el **registro de usuarios** e **inicio de sesión** en el sistema.

Se debe tener en cuenta que al hacer la instalación de Laravel, ya contamos con un modelo para usuarios (`**app/Models/User.php**`) y también el archivo de migración ****de la tabla users **(`database/migrations/xxx_xx_xx_xxxxx_create_users_table.php`** las “x” al inicio del archivo representan la fecha y el id asignado por laravel**).**

Teniendo en cuenta lo anterior y de acuerdo a nuestro modelo de base de datos, necesitamos agregar dos campos adicionales (**address** y **rol**) al archivo de migración de usuarios, quedando de la siguiente manera:

```php
public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string("address");
            $table->string("rol");
            $table->rememberToken();
            $table->timestamps();
        });
    }
```

**Factories y Seeders: Creación de usuarios de prueba**

Los **factories** en Laravel son una característica muy potente, ya que nos ayudan a generar datos de prueba de manera automática.  Adicionalmente los **Seeders** utilizan a los factories para poblar nuestra base de datos con la cantidad de registros de prueba necesarios.

**UserFactory:** Laravel crea por nosotros el factory para usuarios  UserFactory (ubicado en **`database/factories/UserFactory.php`**), con el fin de tener una estructura base para crear usuarios de prueba.  Le haremos unos pequeños ajustes para agregarle los campos address y rol:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'address' => fake()->address(),
            'rol' => 'user',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

Ahora editamos el archivo **`database/seeders/DatabaseSeeder.php`** para la creación de un usuario administrador y 10 usuarios clientes de prueba:

```php
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        //Crear un usuario administrador
        \App\Models\User::factory()->create([
            'name' => 'Administrador del sistema',
            'email' => 'admin@email.co',
            'rol' => 'admin'
        ]);

        //Crear 10 clientes 
        \App\Models\User::factory(10)->create();
    }
}
```

En este punto es necesario volver a generar las migraciones para actualizar los campos de la **tabla users** y generar los registros de prueba.  Para ello debemos ejecutar el comando `**php artisan migrate:fresh --seed`** El comando anterior elimina todas las tablas y las crea nuevamente, adicionalmente la opción **--seed** genera los datos falsos en nuestra base de datos.

**Controlador AutenticaController:**

Es el momento de crear el controlador (**AutenticaController**) para realizar el registro, el login y logout en el sistema. También el controlador se encargará de actualizar el perfil y contraseña del usuario.  Ejecutamos el siguiente comando para crear el controlador:

```bash
**php artisan make:controller AutenticaController**
```

El controlador **AutenticaController** tendrá los siguientes métodos:

```php
<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AutenticaController extends Controller
{
    public function registro(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'address' => 'required',
            'password' => 'required|min:5|confirmed'
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.unique' => 'El email ya está registrado',
            'address.required' => 'La dirección es obligatoria',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden']
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'rol' => 'user'
        ]);

        return to_route('login')->with('info', 'Registro exitoso');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:5'
        ],
        [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres'
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('productos')->with('info', 'Bienvenido, ' . auth()->user()->name);
        }

        return back()->withErrors([
            'email' => 'Datos de acceso incorrectos',
        ]);
        
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('inicio')->with('info', 'Sesión cerrada correctamente');
    }

    public function perfil(){
        $user = auth()->user();
        return view('autenticacion.perfil', compact('user'));
    }

    public function perfilUpdate(Request $request, User $user){
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'required'
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no es válido',
            'email.unique' => 'El email ya está registrado',
            'address.required' => 'La dirección es obligatoria'
        ]);

        $user->update($request->all());
        return back()->with('info', 'Perfil actualizado correctamente');
    }

    public function passwordUpdate(Request $request, User $user){
        $request->validate([
            'password_old' => 'required',
            'password' => 'required|min:5|confirmed'
        ],
        [
            'password_old.required' => 'La contraseña actual es obligatoria',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden'
        ]);
        //Validar si la contraseña actual es correcta
        if(!password_verify($request->password_old, $user->password)){
            return back()->withErrors([
                'password_old' => 'La contraseña actual no es correcta'
            ]);
        }
        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return back()->with('info', 'Contraseña actualizada correctamente');
    }

}
```

Llegó la hora de crear las rutas para el registro, login, logout, perfil de usuario y cambio de contraseña.  Editamos el archivo **`routes/web.php`** y agregamos las siguientes líneas:

```php
//Ruta de login de usuarios
route::view('/login', 'autenticacion.login')->name('login');
route::post('/login', [AutenticaController::class, 'login'])->name('login.store');
//Ruta de logout del usuario
route::post('/logout', [AutenticaController::class, 'logout'])->name('logout');

//Ruta para editar el perfil de usuario
Route::get('/perfil', [AutenticaController::class, 'perfil'])->name('perfil');
Route::put('/perfil/{user}', [AutenticaController::class, 'perfilUpdate'])->name('perfil.update');
//Ruta para cambiar la contraseña de usuario
Route::put('/perfil/password/{user}', [AutenticaController::class, 'passwordUpdate'])->name('password.update');
```

De acuerdo a las nuevas rutas creadas y  al controlador de autenticación (AutenticaController), necesitamos crear las vistas encargadas de mostrar los formularios de registro, login y actualización del perfil de usuario.  Estas tres vistas las crearemos dentro de la **carpeta  autenticacion** (deberá crearla primero en **`resources/views/autenticacion`**)

**Creación de la vista registro (`resources/views/autenticacion/registro.blade.php)`**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro de usuario</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-green-100 my-4 text-center">
        <h1 class="text-lg font-semibold m-4 uppercase">Registro de nuevo usuario</h1>
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
                <form action="{{route('registro.store')}}" method="POST">
                    @csrf
                    {{-- Nombre --}}
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text">Nombre</span>
                        </label>
                        <input type="text" name="name" placeholder="Escriba su nombre" maxlength="255" class="input input-sm input-bordered" required autofocus value="{{old('name')}}" />
                    </div>
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
                    {{-- Confirmar Contraseña --}}
                    <div class="form-control">
                        <label class="label" for="password_confirmation">
                            <span class="label-text">Confirmar password</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Confirmar password" maxlength="45" class="input input-sm input-bordered" required />
                    </div>
                    {{-- Direccion --}}
                    <div class="form-control">
                        <label class="label" for="address">
                            <span class="label-text">Dirección</span>
                        </label>
                        <input type="text" name="address" placeholder="Escriba la dirección para envíos" maxlength="255" class="input input-sm input-bordered" required value="{{old('address')}}" />
                    </div>
                    {{-- Botones --}}
                    <div class="form-control mt-6">
                        <button class="btn btn-sm btn-primary">Crear cuenta</button>
                        <a href="{{ route('inicio') }}" class="btn btn-sm btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
```

**Creación de la vista login (`resources/views/autenticacion/login.blade.php)`**

```html
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
```

**Creación de la vista perfil (`resources/views/autenticacion/perfil.blade.php)`**

```
@extends('layouts.app')
@section('titulo', 'Mi perfil')
@section('cabecera', 'Mi perfil')

@section('contenido')
    <div class="flex flex-col items-center">
        {{-- Información del perfil de usuario --}}
        <div class="card w-1/2  shadow-2xl bg-base-100 mt-6">
            <div class="card-body">
                <h2 class="text-xl font-semibold">Información de usuario</h2>
                {{-- Mostrar mensajes de error --}}
                <div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="badge badge-warning">{{$error}}</div>
                        @endforeach
                    @endif
                </div>                
                <form action="{{route('perfil.update', $user)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text">Nombre</span>
                        </label>
                        <input type="text" name="name" placeholder="Nombre" class="input input-bordered" maxlength="255" value="{{$user->name}}" required />
                    </div>
                    <div class="form-control">
                        <label class="label" for="email">
                            <span class="label-text">Email</span>
                        </label>
                        <input type="email" name="email" placeholder="Email" class="input input-bordered" maxlength="255" value="{{$user->email}}" />
                    </div>
                    <div class="form-control">
                        <label class="label" for="address">
                            <span class="label-text">Dirección de envíos</span>
                        </label>
                        <input type="text" name="address" placeholder="Dirección para envíos" class="input input-bordered" maxlength="255" value="{{$user->address}}" />
                    </div>
                    <div class="form-control mt-6 w-1/2">
                        <button class="btn btn-sm btn-neutral normal-case">Actualizar perfil</button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Actualizar contraseña --}}
        <div class="card w-1/2 shadow-2xl bg-base-100 mt-6">
            <div class="card-body">
                <h2 class="text-xl font-semibold">Cambiar contraseña</h2>
                <form action="{{route('password.update', $user)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-control">
                        <label class="label" for="password_old">
                            <span class="label-text">Contraseña actual</span>
                        </label>
                        <input type="password" name="password_old" placeholder="Ingrese la contraseña actual" class="input input-bordered" maxlength="45" required />
                    </div>
                    <div class="form-control">
                        <label class="label" for="password">
                            <span class="label-text">Nueva contraseña</span>
                        </label>
                        <input type="password" name="password" placeholder="Ingrese la nueva contraseña" class="input input-bordered" maxlength="45" required />
                    </div>
                    <div class="form-control">
                        <label class="label" for="password_confirmation">
                            <span class="label-text">Confirmar nueva contraseña</span>
                        </label>
                        <input type="password" name="password_confirmation" placeholder="Confirme la nueva contraseña" class="input input-bordered" maxlength="45" required />
                    </div>
                    <div class="form-control mt-6 w-1/2">
                        <button class="btn btn-sm btn-neutral normal-case">Cambiar contraseña</button>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
```

### Middleware: Protegiendo las rutas

Un **middleware** es una capa intermedia entre las solicitudes entrantes y las respuestas salientes de una aplicación. Actúa como un filtro o un puente entre el cliente y el servidor, permitiendo realizar acciones antes o después de que una solicitud llegue al controlador correspondiente.  

Para nuestra aplicación estaremos usando dos middlware:  el **middleware auth**, que ya viene por defecto en Laravel, y se encarga de verificar si un usuario está autenticado antes de ingresar a determinada ruta (si no está autenticado lo redirige por defecto a la vista de login);  y el **middleware admin,** que crearemos para verificar si el usuario que inicio sesión es administrador, con el fin de bloquearle o habilitarle algunas rutas en el sistema (listar, crear, editar, eliminar).

Los middleware los podemos usar en el archivo de configuración de rutas (web.php) o en el controlador a través del método contructor (construct).

Para crear el middleware admin ejecutamos el siguiente comando:

```html
**php artisan make:middleware CheckAdmin**
```

El sistema creará el archivo **`app/Http/Middleware/CheckAdmin.php`** y lo editamos para que quede de la siguiente manera:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //verificar que el rol del usuario sea admin
        if(auth()->user()->rol !== 'admin'){
            abort(403);
        }
        return $next($request);
    }
    
}
```

Lo que estamos haciendo en el código anterior, es validar si el usuario que ingresa al sistema tiene un rol diferente a admin, le enviamos el [código de respuesta HTTP 403](https://developer.mozilla.org/es/docs/Web/HTTP/Status/403) para denegar el acceso.

Ahora debemos registrar el nuevo middleware, ingresando al archivo **`app/Http/Kernel.php`** y agregando la línea `'admin' => \App\Http\Middleware\CheckAdmin::class,` dentro de la opción de **middlewareAliases** de la siguiente manera:

```php
protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\CheckAdmin::class,
    ];
```

Ya tenemos habilitado el **middleware admin** y ahora podemos proteger nuestras rutas ingresando a los controladores **CategoriaController** y **PedidoController**, y creando un nuevo método constructor para ejecutar los middleware auth y admin.

**Controlador CategoriaController** (**`app/Http/Controllers/CategoriaController.php`**):

```php
<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    //Protegemos las rutas de este controlador con el middleware auth y admin (autenticado y rol de admin)
    public function __construct()
    {
        //Sólo los usuarios autenticados y con rol de admin pueden acceder a todas las rutas de este controlador
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', ['categorias' => $categorias]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Categoria::create($request->all());
        return to_route('categorias.index')->with('info', 'Categoría creada con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', ['categoria' => $categoria]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $categoria->update($request->all());
        return to_route('categorias.index')->with('info', 'Categoría actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return to_route('categorias.index')->with('info', 'Categoría eliminada con éxito');
    }
}
```

**Controlador ProductoController** (**`app/Http/Controllers/ProductoController.php`**):

```php
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
```

### Creación de la página de inicio

Crearemos una página sencilla de **inicio** para reemplazar la página que trae por defecto Laravel (welcome).  Solamente debemos crear el archivo **`resources/views/inicio.blade.php`** con el siguiente contenido:

```html
@extends('layouts.app')
@section('titulo', 'Minimercado')
@section('cabecera', 'Minimercado - El mejor lugar para comprar')

@section('contenido')
    <div class="hero min-h-screen" style="background-image: url(https://source.unsplash.com/random/400x200/?market);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
        <div class="max-w-md">
            <h1 class="mb-5 text-5xl font-bold">Aquí encontrará los mejores productos!</h1>
            <p class="mb-5">Estamos comprometidos con nuestros clientes entregándoles lo mejor.  Nuestros envíos no tienen costo y llegan el mismo día de realizado su pedido.</p>
            <a href="{{route('productos.index')}}" class="btn btn-primary">Iniciar experiencia</a>
        </div>
        </div>
    </div>
@endsection
```

También nos debemos asegurar que en el archivo de configuración de rutas **`web.php`** tengamos activa la vista de inicio agregando la siguiente línea y eliminando la anterior (welcome):

**`Route::view('/','inicio')->name('inicio');`**

**Actualizando el Navbar:** 

De igual ****forma actualizaremos el archivo **`resoureces/views/partials/navigation.blade.php`** que es el contiene el menú de navegación, ya que el menú cambiará de acuerdo a si estamos logueados en el sistema o si tenemos rol de administrador (admin) o cliente (user).

Blade (administrador de plantillas de Laravel) utiliza la directiva **@auth** para verificar si el usuario está autenticado o no.  También podemos validar si el usuario autenticado es administrador a través de  **@if (auth()->user()->rol == 'admin')**.  El código actualizado del navbar sería el siguiente:

```html
<div class="navbar bg-orange-200">
  <div class="flex-1 ml-2">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25" />
    </svg>
    
    <a href="{{route('inicio')}}" class="btn btn-ghost btn-sm normal-case text-sm">MiniMercado</a>
  </div>
  <div class="flex-none">
    @auth
      <ul class="menu menu-horizontal px-1 mr-6 space-x-2">
        <li><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
        <li><a href="{{ route('productos.index') }}">Productos</a></li>
        @if (auth()->user()->rol == 'admin')
          <li><a href="{{ route('categorias.index') }}">Categorías</a></li>
        @endif
      </ul>
      {{-- Menú del usuario --}}
      <div class="dropdown dropdown-end mr-4">
        <label tabindex="0" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img src="https://source.unsplash.com/random/100x100/?face" />
          </div>
        </label>
        <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
          <li class="font-semibold">
            {{ auth()->user()->name }}
          </li>
          @if (auth()->user()->rol == 'admin')
            <li><a href="#" class="link link-hover">Usuarios del sistema</a></li>
          @endif
          <li><a href="{{ route('perfil') }}" class="link link-hover">Mi perfil</a></li>
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="link link-hover">Cerrar sesión</button>
            </form>
          </li>
        </ul>
      </div>
    @else
      <ul class="menu menu-horizontal px-1 mr-6 space-x-4">
        <li><a href="{{ route('login') }}" class="btn btn-sm btn-outline normal-case">Iniciar sesión</a></li>
        <li><a href="{{ route('registro') }}" class="btn btn-sm btn-outline normal-case">Registrarse</a></li>
      </ul>
    @endauth
  </div>
</div>
```

### Administración de pedidos

Iniciaremos con el proceso de administración de pedidos.  Teniendo en cuenta nuestro modelo de base de datos, necesitamos crear la tabla **pedidos** y la tabla pivote (**pedido_producto**) que maneja la relación muchos a muchos.  

Se debe tener en cuenta que el estado de un pedido podrá ser: pendiente, enviado o entregado, el cual se guardará en el campo estado de la tabla pedidos. 

**Creación del modelo, migración y controlador de Pedidos**

Para crear el modelo, migración y controlador de los pedidos, ejecutamos el comando:

```bash
**php artisan make:model Pedido -mc --resource**
```

A continuación realizamos algunas configuraciones en el modelo **Pedido** que se encuentra en `**app/Models/Pedido.php**`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected $fillable = ['fecha', 'estado', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class); // Un pedido pertenece a un usuario
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad', 'precio'); // Un pedido puede tener varios productos
    }
}
```

la función **user()** crea la relación inversa **belongsTo** (un pedido pertenece a un usuario) y la función **productos()** crea relación **belongsToMany** (un pedido puede tener varios productos).  Se debe tener en cuenta que para minimizar un poco el proceso de nuestra aplicación “Minimercado” usaremos solamente un producto por pedido.

También agregamos la relación **hasMany** (un usuario puede tener varios pedidos) de la función pedidos() en el modelo **User** `**app/Models/User.php`** quedando:

```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'rol'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pedidos(){
        return $this->hasMany(Pedido::class); //Un usuario puede tener varios pedidos
    }
}
```

De igual forma actualizamos el modelo **Producto `app/Models/Producto.php`** para agregar la relación belongsToMany (un producto puede estar en varios pedidos), a través de la función pedidos(), quedando de la siguiente manera:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);  // Un producto pertenece a una categoria
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class)->withPivot('cantidad', 'precio');  // Un producto puede estar en varios pedidos
    }
}
```

**Migración para la tabla pedidos:**

Ahora crearemos la estructura de los campos de la tabla pedidos a través del archivo de migración que se encuentra en el directorio `database/migrations/xxxx_xx_xx_xx_create_pedidos_table.php` (las “x” se reemplazan por la fecha y id asignado por Laravel).

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('estado', 50);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
```

Creamos la tabla pivote **pedido_producto** con el siguiente comando

```bash
**php artisan make:migration create_pedido_producto_table**
```

Ahora editamos el archivo de migración creado anteriormente, quedando así:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained();
            $table->foreignId('pedido_id')->constrained();
            $table->integer('cantidad');
            $table->integer('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};
```

Para crear la nuevas tablas de pedidos en la base de datos ejecutamos nuevamente el comando:

```bash
**php artisan make:migrate**
```

**Rutas para pedidos:**

Necesitamos habilitar las rutas para administrar los pedidos. Para tal fin agregamos las siguientes líneas al archivo de configuración de rutas (web.php):

```php
//Rutas para pedidos
Route::resource('/pedidos', PedidoController::class)->except(['create']);
Route::get('/pedidos/create/{producto}', [PedidoController::class, 'create'])->name('pedidos.create');
```

**Configurando el controlador PedidoController:**

El controlador **`app/Http/Controllers/PedidoController.php`**  quedaría así:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // Protegemos las rutas de este controlador con el middleware auth y admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //si el usuario es admin, mostrar todos los pedidos (con paginación) sino mostrar solo los pedidos del usuario logueado
        if (auth()->user()->rol === 'admin') {
            $pedidos = Pedido::orderBy('fecha', 'desc')->paginate(10);
        } else {
            $pedidos = Pedido::where('user_id', auth()->user()->id)->orderBy('fecha', 'desc')->paginate(10);
        }
        return view('pedidos.index', ['pedidos' => $pedidos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Producto $producto)
    {
        return view('pedidos.create', ['producto' => $producto]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $producto = Producto::find($request->producto_id);
        //Guardar el pedido en la tabla pedidos
        $pedido = Pedido::create([
            'fecha' => now(),
            'estado' => 'pendiente',
            'user_id' => auth()->user()->id,
        ]);
        //Guardar los productos en la tabla pivote (pedido_producto)
        $pedido->productos()->attach($producto->id, [
            'cantidad' => $request->cantidad,
            'precio' => $producto->precio,
        ]);

        //Restamos la cantidad de productos pedidos al stock
        $producto->stock -= $request->cantidad;
        $producto->save();

        return to_route('productos.index')->with('info', 'Pedido realizado con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        return view('pedidos.edit', ['pedido' => $pedido]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $pedido->estado = $request->estado;
        $pedido->save();
        return to_route('pedidos.index')->with('info', 'Se cambió el estado del pedido');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        //Eliminar el pedido de la tabla pivote (pedido_producto)
        $pedido->productos()->detach();
        //Eliminar el pedido de la tabla pedidos
        $pedido->delete();
        return to_route('pedidos.index')->with('info', 'Pedido eliminado con éxito');
    }
}
```

**Vistas para la administración de pedidos:**

Creamos una nueva carpeta de nombre **pedidos** dentro de **** **`resources/views/`** para guardas las tres vistas: create (crear un nuevo pedido), edit (cambiar el estado del pedido) e index (listar pedidos).

**Vista create (`resources/views/pedidos/create.blade.php`)**

```html
@extends('layouts.app')
@section('titulo', 'Ordenar Producto')
@section('cabecera', $producto->nombre)

@section('contenido') 
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <figure><img class="rounded-xl" src="https://source.unsplash.com/random/400x200/?{{$producto->categoria->nombre}}&sig={{$producto->id}}" alt="{{$producto->nombre}}"></figure>
                    <div class="card-body">
                        <p class="text-sm">{{$producto->descripcion}}</p>

                        {{-- precio y stock--}}
                        <div class="flex space-x-4">
                            <div class="badge badge-neutral">${{number_format($producto->precio, 0, ',', '.')}}</div>
                            <div class="badge badge-ghost">{{$producto->stock}} en stock</div>
                        </div>
                <form action="{{route('pedidos.store')}}" method="POST">
                    @csrf
                        <input type="hidden" name="producto_id" value="{{$producto->id}}">
                        <input type="hidden" name="precio" value="{{$producto->precio}}">
                    {{-- Cantidad --}}
                    <div class="form-control">
                        <label class="label" for="cantidad">
                            <span class="label-text">Cantidad</span>
                        </label>
                        <select name="cantidad" class="select select-bordered">
                            <option value="1">1</option>
                            @if ($producto->stock >= 2)
                                <option value="2">2</option>
                            @endif
                            @if ($producto->stock >= 3)
                                <option value="3">3</option>
                            @endif
                        </select>
                    </div>
                    {{-- Dirección de envío --}}
                    <p class="mt-2">
                        <span class="font-semibold">Dirección de envío</span><br>
                        {{auth()->user()->address}}

                    </p>
                    
                    <div class="form-control mt-6">
                        <button class="btn btn-primary">Ordenar</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
```

**Vista edit (`resources/views/pedidos/edit.blade.php`)**

```html
@extends('layouts.app')
@section('titulo', 'Estado Pedido')
@section('cabecera', 'Estado Pedido # ' . $pedido->id . ' - ' . $pedido->productos[0]->nombre)

@section('contenido') 
    <div class="flex justify-center">
        <div class="card w-96 shadow-2xl bg-base-100">
            <div class="card-body">
                <div class="card-body">
                    <p>Cantidad: {{$pedido->productos[0]->pivot->cantidad}}</p>
                    <p>Precio: {{ '$'.number_format($pedido->productos[0]->pivot->precio, 0, ',', '.') }}</p>
                    <p>Total: {{ '$'.number_format($pedido->productos[0]->pivot->precio * $pedido->productos[0]->pivot->cantidad, 0, ',', '.') }}</p>
                    
                    <form action="{{route('pedidos.update', $pedido)}}" method="POST">
                        @csrf
                        @method('put')
                        
                        <div class="form-control mb-2">
                            <label for="estado">Estado del pedido:</label>
                            <select name="estado" id="estado" class="select select-bordered">
                                @if ($pedido->estado == 'pendiente')
                                    <option value="pendiente" selected>Pendiente</option>
                                    <option value="enviado">Enviado</option>
                                    <option value="entregado">Entregado</option>
                                @elseif ($pedido->estado == 'enviado')
                                    <option value="pendiente">Pendiente</option>
                                    <option value="enviado" selected>Enviado</option>
                                    <option value="entregado">Entregado</option>
                                @else
                                    <option value="pendiente">Pendiente</option>
                                    <option value="enviado">Enviado</option>
                                    <option value="entregado" selected>Entregado</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-control">
                            <button type="submit" class="btn btn-primary mt-2">Actualizar</button>
                            <a href="{{ route('pedidos.index') }}" class="btn btn-outline btn-primary mt-4">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
```

**Vista index (`resources/views/pedidos/index.blade.php`)**

```html
@extends('layouts.app')
@section('titulo', 'Listar Pedidos')
@section('cabecera', 'Listar Pedidos')

@section('contenido')
    <div class="flex justify-center">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
              <thead>
                <tr>
                  <th># Pedido</th>
                  <th>Fecha y hora</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Precio Unit.</th>
                  <th>Valor total</th>
                  <th>Estado</th>
                  @if(auth()->user()->rol == 'admin')
                    <th>Cliente</th>
                    <th>Direccion</th>
                    <th>Email</th>
                    <th>Acciones</th>
                 @endif

                </tr>
              </thead>
              <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->fecha }}</td>
                        <td>{{ $pedido->productos[0]->nombre }}</td>
                        <td>{{ $pedido->productos[0]->pivot->cantidad }}</td>
                        <td>{{ '$'.number_format($pedido->productos[0]->pivot->precio, 0, ',', '.') }}</td>
                        <td>{{ '$'.number_format($pedido->productos[0]->pivot->precio * $pedido->productos[0]->pivot->cantidad, 0, ',', '.') }}</td>
                        <td>
                            @if ($pedido->estado == 'pendiente')
                                <span class="badge badge-warning">{{ $pedido->estado }}</span>
                            @elseif ($pedido->estado == 'enviado')
                                <span class="badge badge-primary">{{ $pedido->estado }}</span>
                            @else
                                <span class="badge badge-success">{{ $pedido->estado }}</span>
                            @endif
                        </td>
                        {{-- Si el usuario es administrador, se muestran los datos del cliente --}}
                        @if(auth()->user()->rol == 'admin')
                            <td>{{ $pedido->user->name }}</td>
                            <td>{{ $pedido->user->address }}</td>
                            <td>{{ $pedido->user->email }}</td>
                        
                            {{-- Botones para editar o eliminar pedido para el administrador --}}
                            <td class="flex space-x-2">
                                <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-warning btn-xs normal-case">Estado</a>
                                <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Desea eliminar el pedido {{ $pedido->id }}?')" class="btn btn-error btn-xs normal-case">Eliminar</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{-- Paginacion --}}
            <div class="flex justify-center mt-4">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
@endsection
```

También debemos actualizar la vista de **listar productos** (`**resources/views/productos/index.blade.php`)** ya que de acuerdo al rol del usuario deberá mostrar los botones de crear, editar o eliminar un producto (sólo para administradores) y para los clientes solamente mostrará la opción de ordenar (crear un nuevo pedido) y si el producto tiene un stock=0 no muestra el producto al cliente.

```html
@extends('layouts.app')
@section('titulo', 'Nuestros Productos')
@section('cabecera', 'Nuestros Productos')

@section('contenido')
    {{-- si el usuario es admin muestra crear producto --}}
    @if (auth()->user()->rol == 'admin')
        <div class="flex justify-end m-4">
            <a href="{{ route('productos.create') }}" class="btn btn-outline btn-sm">Crear Producto</a>
        </div>
    @endif
    <div class="flex justify-center mx-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
            @foreach ($productos as $producto)
                {{-- No muestra a los clientes productos que tengan stock 0 --}}
                @if (auth()->user()->rol == 'admin' || $producto->stock > 0) 
                    <div class="card w-72 bg-base-100 shadow-xl">
                        <figure><img src="https://source.unsplash.com/random/400x200/?{{$producto->categoria->nombre}}&sig={{$producto->id}}" alt="{{$producto->nombre}}"></figure>
                        <div class="card-body">
                            <h2 class="card-title">{{$producto->nombre}}</h2>
                            <div class="badge badge-success badge-outline">Categoría: {{$producto->categoria->nombre}}</div>
                            <p>{{Str::limit($producto->descripcion, 80)}}</p>

                            {{-- precio y stock--}}
                            <div class="flex space-x-4">
                                <div class="badge badge-neutral">${{number_format($producto->precio, 0, ',', '.')}}</div>
                                <div class="badge badge-ghost">{{$producto->stock}} en stock</div>
                            </div>
                        
                            <div class="card-actions justify-end mt-5">
                                {{-- si el usuario es admin muestra editar o eliminar --}}
                                @if (auth()->user()->rol == 'admin')
                                    {{-- Editar --}}
                                    <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning btn-xs">Editar</a>
                                    {{-- Eliminar --}}
                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Desea eliminar el producto {{ $producto->nombre }}?')" class="btn btn-error btn-xs">Eliminar</button>
                                    </form>
                                @else
                                    {{-- si el usuario es cliente muestra realizar una orden --}}
                                    <a href="{{ route('pedidos.create', $producto->id) }}" class="btn btn-primary btn-xs">Ordenar</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
```

