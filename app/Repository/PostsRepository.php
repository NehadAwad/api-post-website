<?php
namespace App\Repository;
use App\Interfaces\PostInterface;
use App\Models\Post;


class PostsRepository implements PostInterface
{
    public function test(){
        return 'sas';
    }


    public function allPosts()
    {
        return Post::all();
    }


}
