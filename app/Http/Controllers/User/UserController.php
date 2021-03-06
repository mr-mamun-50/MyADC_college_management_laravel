<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

class UserController extends Controller
{
    //__Homepage view
    public function index()
    {
        $posts = DB::table('posts')
                ->leftjoin('users', 'users.id', '=', 'posts.user_id')
                ->select('posts.*', 'users.name', 'users.user_image')
                ->where('visibility', '=', '1')
                ->orderBy('posts.post_date', 'desc')
                ->paginate(10);

        $peoples = DB::table('users')
                ->inRandomOrder()
                ->paginate(10);

        return view('home', compact('posts', 'peoples'));
    }

    //__User profile view
    public function profile($id)
    {
        $user = DB::table('users')->where('id', '=', $id)->first();

        if(Auth::user()->id == $user->id) {
            $posts = DB::table('posts')
                ->leftjoin('users', 'users.id', '=', 'posts.user_id')
                ->select('posts.*', 'users.name', 'users.user_image', 'users.created_at')
                ->where('posts.user_id', '=', $id)
                ->orderBy('posts.post_date', 'desc')
                ->paginate(10);
        }
        else {
            $posts = DB::table('posts')
                ->leftjoin('users', 'users.id', '=', 'posts.user_id')
                ->select('posts.*', 'users.name', 'users.user_image', 'users.created_at')
                ->where('posts.user_id', '=', $id)
                ->where('visibility', '=', '1')
                ->orderBy('posts.post_date', 'desc')
                ->paginate(10);
        }

        return view('user.profile', compact('user', 'posts'));
    }


    //__View videos
    public function videos()
    {
        $videos = DB::table('posts')
                ->leftjoin('users', 'users.id', '=', 'posts.user_id')
                ->select('posts.*', 'users.name', 'users.user_image')
                ->where('video', '!=', 'NULL')
                ->where('visibility', '=', '1')
                ->orderBy('posts.post_date', 'desc')
                ->paginate(10);

        $peoples = DB::table('users')
                ->inRandomOrder()
                ->paginate(10);

        return view('user.videos', compact('videos', 'peoples'));
    }


    //__View Routines
    public function routines() {

        $xi_routine = DB::table('class_routine_xi')->get();
        $xii_routine = DB::table('class_routine_xii')->get();

        return view('user.routine.routines', compact('xi_routine', 'xii_routine'));
    }
    //__Print or download routine
    public function export($class, $dept)
    {
        if($class == 'XI') {
            $data = DB::table('class_routine_xi')->get();
        }
        else {
            $data = DB::table('class_routine_xii')->get();
        }

        return view('user.routine.print', compact('data', 'dept', 'class'));
    }
}
