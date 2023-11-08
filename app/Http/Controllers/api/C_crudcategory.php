<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class C_crudcategory extends Controller
{


    public function index()
    {
        $posts = category::latest()->paginate(5);
        return response()->json($posts, 200);
    }

    public function show(category $category, $id)
    {
        $post = category::findOrFail($id);

        return response()->json($post, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        $validatordata = $validator->validated();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $category = category::create([
            'name' => $validatordata['name']
        ]);

        return response()->json(['message' => 'data berhasil ditambahkan', 'data' => $category], 200);
    }

    public function update(Request $request, category $categories, $id)
    {
        $category = category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        $validatordata = $validator->validated();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->all()) {
            $category->update([
                'name' => $validatordata['name']
            ]);
        }

        return response()->json(['message' => 'data berhasil diubah', 'data' => $category], 200);
    }


    public function destroy(category $category, $id)
    {
        try {
            $deleted = category::findOrFail($id);
            //delete post
            $deleted->delete();
            //return response
            return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (Exception $ex) {
            // Alert::warning('Error', 'Cant deleted, Barang already used !');
            return response()->json($ex, 422);
        }
    }
}
