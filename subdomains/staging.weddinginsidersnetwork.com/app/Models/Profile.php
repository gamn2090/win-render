<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'bio',
        'belongs_to',
        'photo',
        'facebook_link',
        'instagram_link',
        'linkedin_link',
        'type',
        'business_link',
        'portfolio_images',
        'google_review_score',
        'google_reviews_count',
        'google_place_link'
    ];

    public function portfolioImages($max = null){
        $portfolioImages = json_decode($this->portfolio_images);
        if($max != null){
            $portfolioImages = array_slice($portfolioImages, 0, $max);
        }
        return $portfolioImages;
    }

    public function removeImage($img){
        $portfolioImages = $this->portfolioImages();
        if (($key = array_search($img, $portfolioImages)) !== false) {
            array_splice($portfolioImages, $key, 1);
            //delete image from storage
            unlink(storage_path('app/public/images/' . $img));
        }
        $this->portfolio_images = json_encode($portfolioImages);
        $this->save();
        return;
    }

    public function getLink($name){
        switch ($name) {
            case 'facebook':
                return 'https://facebook.com' . $this->facebook_link;
            case 'instagram':
                return 'https://instagram.com' . $this->instagram_link;
            case 'linkedin':
                return 'https://linkedin.com' . $this->linkedin_link;
            default:
                return '/404';
        }
    }
}
