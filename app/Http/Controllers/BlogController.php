<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlogService;

class BlogController extends Controller
{
    //
    public function index(){
        $data = [
            "posts" => BlogService::getPosts()
        ];
        return view('blog.index', $data);
    }

    public function viewPost($id){
        $data = [
            "post" => BlogService::getPost($id),
            "id" => $id
        ];
        return view('blog.post', $data);
    }
}