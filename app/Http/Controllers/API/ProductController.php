<?php
     
namespace App\Http\Controllers\API;
     
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
     
class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
      
        return $this->sendResponse(ProductResource::collection($products), 'Data Produk berhasil dibaca.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
     
        if($validator->fails()){
            return $this->sendError('Kesalahan Validasi.', $validator->errors());       
        }
     
        $product = Product::create($input);
     
        return $this->sendResponse(new ProductResource($product), 'Produk berhasil dibuat.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);
    
        if (is_null($product)) {
            return $this->sendError('Product tidak tersedia.');
        }
     
        return $this->sendResponse(new ProductResource($product), 'Data Produk berhasil dibaca.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $input = $request->all();
     
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
     
        if($validator->fails()){
            return $this->sendError('Kesalahan Validasi.', $validator->errors());       
        }
     
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
     
        return $this->sendResponse(new ProductResource($product), 'Produk berhasil diperbarui.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
     
        return $this->sendResponse([], 'Produk berhasil dihapus.');
    }
}