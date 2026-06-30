<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;

class BlogService {
    //TODO: increase cache time for prod
    public static function getPosts(){
        $posts = Cache::remember('blog-posts', 30, function () {
            $client = new Client();
            $response = $client->get('https://beta.weddinginsidersnetwork.com/?rest_route=/wp/v2/posts&_embed=wp:featuredmedia&_fields=title,excerpt,tags,id,_links,_embedded');
            $postsList = json_decode($response->getBody()->getContents());

            //fix image urls
            foreach($postsList as $post){
                if(!property_exists($post,"_embedded")){
                    continue;
                }
                $post->featured_image = $post->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes->large->source_url;
                $post->_embedded = null;
                $post->_links = null;
            }
            return $postsList;
        });
        $tags = Cache::remember('blog-tags', 30, function () {
            $client = new Client();
            $response = $client->get('https://beta.weddinginsidersnetwork.com/index.php?rest_route=/wp/v2/tags&_fields[]=id&_fields[]=name&_fields[]=slug');
            return json_decode($response->getBody()->getContents());
        });
        foreach($posts as $post){
            //add tags
            $tagIds = $post->tags;
            $post->tags = [];
            foreach($tagIds as $tag){
                foreach($tags as $t){
                    if($tag == $t->id){
                        array_push($post->tags, $t->name);
                    }
                }
            }
        }
        return $posts;
    }

    public static function getPost($id){
        $post = Cache::remember('blog-post-'.$id, 1200, function () use ($id) {
            $client = new Client();
            $response = $client->get('https://beta.weddinginsidersnetwork.com/?rest_route=/wp/v2/posts/'.$id.'&_embed=wp:featuredmedia&_fields=title,content,date,tags,id,_links,_embedded');
            $post = json_decode($response->getBody()->getContents());
            if(!property_exists($post,"_embedded")){
                return null;
            }
            $post->featured_image = $post->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes->large->source_url;
            $post->_embedded = null;
            $post->_links = null;
            return $post;
        });
        $tags = Cache::remember('blog-tags', 300, function () {
            $client = new Client();
            $response = $client->get('https://beta.weddinginsidersnetwork.com/index.php?rest_route=/wp/v2/tags&_fields[]=id&_fields[]=name&_fields[]=slug');
            return json_decode($response->getBody()->getContents());
        });
        //add tags
        $tagIds = $post->tags;
        $post->tags = [];
        foreach($tagIds as $tag){
            foreach($tags as $t){
                if($tag == $t->id){
                    array_push($post->tags, $t->name);
                }
            }
        }
        return $post;
    }
}