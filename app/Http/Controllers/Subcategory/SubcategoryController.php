<?php

namespace App\Http\Controllers\Subcategory;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Subcategory;
use App\Category;

class SubcategoryController extends ApiController
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
        $subcategories = Subcategory::all();
        return $this->showAll($subcategories);
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
            'category_id' => 'required', // Requerido
        ];
        $this->validate($request, $reglas);  // validamos con las reglas. si no valida arrojamos excepcion
        $campos = $request->all(); // extraemos todos los valores del request
        if ($request->has('category_id')){
            $category = Category::find($campos['category_id']);
            if (is_null($category))
                return $this->errorResponse('this category id does not exists', 422); 
        }
        $subcategory = Subcategory::create($campos);
        return $this->showOne($subcategory, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        return $this->showOne($subcategory);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        if ($request->has('name')){
            $subcategory->name = $request->name;
        }
        if ($request->has('category_id')){
            $category = Category::find($request['category_id']);
            if (is_null($category))
                return $this->errorResponse('this category id does not exists', 422); 
            $subcategory->category_id = $request->category_id;
        }
        if (!$subcategory->isDirty())  // Si el objeto no ha sido modificado en alguno de sus valores  // 422 Malformed Request
            return $this->errorResponse('Se debe especificar al menos un valor diferente para modificar ', 422);  
        $subcategory->save();
        return $this->showOne($subcategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        //$category = Category::findOrFail($id);
        $subcategory->delete();
        return $this->showOne($subcategory);
    }

    public function subcategoriesByCategoryId($id)
    {
        $subcategories = Subcategory::where('category_id','=',$id)->get();
        return $this->showAll($subcategories);
    }
}
