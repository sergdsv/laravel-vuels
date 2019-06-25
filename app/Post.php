<?php

namespace App;

use Illuminate\Sappord\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    use Sluggable;

    protected $fillable = ['title', 'content'];

    public function category()
    {
    	return $this->hasOne(Category::class);
    }

    public function autor()
    {
    	return $this->hasOne(User::class);
    }

    public function tags()
    {
        //Много ко многим
    	return $this->belongsToMany(
    		Tag::class,
    		'post_tags',
    		'post_id',
    		'tag_id'
    	);
    }

       /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add ($fields)
    {
        // или можно написать $post = new self;
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $post->fill($lields);
        $post->save();
    }

    public function remove()
    {
        Storage::delete('uploads' . $this->image);
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null){ return; }

        Storage::delete('uploads' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if ($this->image == null){
            return '/img/null_image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function setCategory($id)
    {
        if ($id == null){ return; }

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {

        if ($ids == null) { return; }

        $this->tags->sync($ids);
        $this->save();
    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
    }

    public function toogleStatus($value)
    {
        if($value == null){
           return $this->setDraft;
        }
        return $this->setPublic;
    }

    public function setFeatured()
    {
        $this->status = 0;
    }

    public function setStandart()
    {
        $this->status = 1;
    }

    public function toogleFeatured($value)
    {
        if($value == null){
           return $this->setFeatured;
        }
        return $this->setStandart;
    }




}
