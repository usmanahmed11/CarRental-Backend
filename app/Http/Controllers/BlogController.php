<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blogTitle' => 'required',
            'blogCategory' => 'required',
            'blogDescription' => 'required',
            'picture' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $blog = new Blog();
        $blog->title = $request->input('blogTitle');
        $blog->category = $request->input('blogCategory');
        $blog->description = $request->input('blogDescription');

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picturePath = $picture->store('blog_pictures', 'public');
            $blog->picture = $picturePath;
        }

        $blog->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully',
        ], 200);
    }

    public function index()
    {
        $blog = Blog::get();

        return response()->json($blog);
    }
    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully']);
    }
    public function getBlogs($id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json([
            'title' => $blog->title,
            'category' => $blog->category,
            'description' => $blog->description,
            'picture' => $blog->picture,

        ]);
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'blogTitle' => 'required',
            'blogCategory' => 'required',
            'blogDescription' => 'required',

        ]);

        $blog = Blog::findOrFail($id);

        $blog->title = $validatedData['blogTitle'];
        $blog->category = $validatedData['blogCategory'];
        $blog->description = $validatedData['blogDescription'];


        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picturePath = $picture->store('blog_pictures', 'public');
            $blog->picture = $picturePath;
        }

        $blog->save();

        return response()->json(['message' => 'Blog updated successfully'], 200);
    }
}
