<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\Tag;
use Auth;
use Gate;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Purifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $posts = Post::orderBy('created_at', 'asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost($id)
    {
        $post = Post::where('id', $id)->with('likes')->first();
        return view('blog.post', ['post' => $post]);
    }

    public function getLikePost($id)
    {
        $post = Post::where('id', $id)->first();
        $like = new Like();
        $post->likes()->save($like);
        return redirect()->back();
    }

    public function getAdminCreate()
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $tags = Tag::all();
        return view('admin.create', ['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $post = Post::find($id);
        $tags = Tag::all();
        return view('admin.edit', ['post' => $post, 'postId' => $id, 'tags' => $tags]);
    }

    public function postAdminCreate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:5'
        ]);
        $user = Auth::user();
        if (!$user) {
            return redirect()->back();
        }
        $post = new Post([
            'title' => $request->input('title'),
            'content' => Purifier::clean($request->input('content'))
        ]);

        //save image
        if (Input::hasFile('input_img')) {
            $image = Input::file('input_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move('images', $imageName);

            $post->image = $imageName;
        }

        $user->posts()->save($post);
        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));

        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = Post::find($request->input('id'));
        if (Gate::denies('manipulate-post', $post)) {
            return redirect()->back();
        }
        //save image
        if (Input::hasFile('input_img')) {
            $image = Input::file('input_img');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move('images', $imageName);
            $oldImage = $post->image;

            //update new image
            $post->image = $imageName;

            //delete old image
            File::delete('images/' . $oldImage);
        }
        $post->title = $request->input('title');
        $post->content = Purifier::clean($request->input('content'));
        $post->save();
//        $post->tags()->detach();
//        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        $post->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));

        return back()->with('info', 'Post updated successfully!');
    }

    public function getAdminDelete($id)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $post = Post::find($id);
        if (Gate::denies('manipulate-post', $post)) {
            return redirect()->back();
        }
        $post->likes()->delete();
        $post->tags()->detach();
        $post->delete();
        File::delete('images/' . $post->image);
        return redirect()->route('admin.index')->with('info', 'Post deleted!');
    }
}
