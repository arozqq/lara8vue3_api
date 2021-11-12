<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        //ambil data dari table post
        $posts = Post::latest()->get();

        // buat response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Post',
            'data' => $posts
        ], 200);
    }

    public function show($id)
    {
        // cari data post berdasarkan id
        $post = Post::findOrFail($id);

        // buat response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data'    => $post
        ], 200);
    }

    public function store(Request $request)
    {

         // buat validasi
         $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required',
        ]);

        // pesan validasi error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // simpan ke database
        $post = Post::create($request->all());

        // kondisi jika sukses simpan ke database
        if($post) {
            return response()->json([
                'success' => true,
                'message' => 'Post Berhasil Dibuat!',
                'data' => $post
            ], 200);
        }

        // kondisi jika gagal simpan ke database
        return response()->json([
            'success' => false,
            'message' => 'Post Gagal Dibuat!'
        ], 400);
    }

    public function update(Request $request, Post $post)
    {
        // buat validasi
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required',
        ]);

          // pesan validasi error
          if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // cari post yang mau di update berdasarkan id
        $post = Post::findOrFail($post->id);

        if($post) {
            // update post
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $post
            ], 200);
        }

        // jika data tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan!'
        ], 400);
    }

    public function destroy($id)
    {
        // cari data post yg mau dihapus berdasarkan id
        $post = Post::findOrFail($id);

        // kondisi jika id ketemu
        if ($post) {
            //hapus post
            $post->delete();

            // buat response json
            return response()->json([
                'success' => true,
                'message' => 'Data post berhasil dihapus'
            ], 200);
        }

        // jika data gak ketemu
        return response()->json([
            'success' => false,
            'message' => 'Data post tidak ditemukan!'
        ], 400);
    }
}
