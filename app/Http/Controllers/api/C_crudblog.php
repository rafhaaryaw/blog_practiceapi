<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\blog;
use App\Models\category;
use App\Models\post;
use App\Models\posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Throwable;

class C_crudblog extends Controller
{
    public function index()
    {
        $blog = posts::join('category', 'category.id', '=', 'posts.r_id_category')
            ->select('title', 'posts.id', 'category.name', 'content')
            ->get();

        return response()->json($blog, 200);
    }

    public function show(posts $posts, $id)
    {

        $blog = posts::join('category', 'category.id', '=', 'posts.r_id_category')
            ->select('title', 'posts.id', 'category.name', 'content')
            ->findOrFail($id);


        return response()->json($blog, 200);
    }

    public function category()
    {
        $cat = category::get();

        // $blog = Blog::with('r_id_category')->get();

        return response()->json(['message' => 'data berhasil ditemukan', 'data' => $cat], 200);
    }

    public function post_blog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'r_id_category' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatordata = $validator->validated();
        $post = posts::create([
            'title' => $validatordata['title'],
            'r_id_category' => $validatordata['r_id_category'],
            'content' => $validatordata['content'],
        ]);

        return response()->json(['message' => 'data berhasil ditambahkan', 'data' => $post], 200);
    }

    public function updateget($id)
    {
        $post = posts::join('category', 'category.id', '=', 'posts.r_id_category')
            ->select('title', 'posts.id', 'category.name', 'content')
            ->findOrFail($id);

        return response()->json($post, 200);
    }

    public function update(Request $request, posts $posts, $id)
    {
        $post = posts::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'r_id_category' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatordata = $validator->validated();

        if ($request->all()) {
            $post->update([
                'title' => $validatordata['title'],
                'r_id_category' => $validatordata['r_id_category'],
                'content' => $validatordata['content'],
            ]);
        }

        return response()->json(['message' => 'data berhasil diubah', 'data' => $post], 200);
    }


    public function destroy($id)
    {
        try {
            $post = posts::findOrFail($id);
            if ($post->delete()) {
                return response([
                    'Berhasil Menghapus Data'
                ]);
            } else {
                //response jika gagal menghapus
                return response([
                    'Tidak Berhasil Menghapus Data'
                ]);
            }
            //delete post
            //return response
            // return response()->json(['message' => 'data berhasil dihapus'], 200);
        } catch (Throwable $ex) {
            // Alert::warning('Error', 'Cant deleted, Barang already used !');
            return response()->json($ex, 422);
        }
    }
}
