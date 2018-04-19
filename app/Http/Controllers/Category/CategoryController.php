<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    public function __construct()
    {
        // Solo chequea cliente-id, no tiene que loguearse en el sistema
        //$this->middleware('client.credentials')->only(['index']);
        // chequea usuarios autenticados
        //$this->middleware('auth:api')->except(['index']);
    }   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required', // Requerido
        ];
        $this->validate($request, $reglas);  // validamos con las reglas. si no valida arrojamos excepcion
        $campos = $request->all(); // extraemos todos los valores del request
        $category = Category::create($campos);
        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //$category = Category::findOrFail($id);
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if ($request->has('name')){
            $category->name = $request->name;
        }
        if (!$category->isDirty())  // Si el objeto no ha sido modificado en alguno de sus valores  // 422 Malformed Request
            return $this->errorResponse('Se debe especificar al menos un valor diferente para modificar ', 422);  
        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //$category = Category::findOrFail($id);
        $category->delete();
        return $this->showOne($category);
    }
}
