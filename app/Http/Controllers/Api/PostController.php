<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;
    public function index(){
        $posts = PostResource::collection(Post::get());
        return $this->apiResponse($posts, 'success', 200);
    }
    public function show($id){
        $post = Post::find($id);
        if (!$post)
            return $this->apiResponse(null, 'The post Not Found', 404);
        return $this->apiResponse(new PostResource($post), 'success', 200);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        if ($validator->fails())
            return $this->apiResponse(null, $validator->errors(), 400);
        $post = Post::create($request->all());
        if (!$post)
            return $this->apiResponse(null, 'The post Not Save', 400);
        return $this->apiResponse(new PostResource($post), 'The post Save', 201);
    }
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        if ($validator->fails())
            return $this->apiResponse(null, $validator->errors(), 400);
        $post = Post::find($id);
        if (!$post)
            return $this->apiResponse(null, 'The post Not Found', 404);
        $post->update($request->all());
        return $this->apiResponse(new PostResource($post), 'The post is Saved', 200);
    }
    public function destroy($id){
        $post = Post::find($id);
        if (!$post)
            return $this->apiResponse(null, 'The post Not Found', 404);
        $post->delete($id);
        return $this->apiResponse(null, 'The post deleted', 200);
    }

}
