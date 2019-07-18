<?php

namespace App;

use Carbon\Carbon;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Auth;


class Post extends Model
{
    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    use Sluggable;

    protected $fillable = ['title', 'content', 'date'];

    public function category()
    {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null){ return; }

        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function removeImage()
    {
        if($this->image != null){
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function getImage()
    {
        if ($this->image == null){
            return '/img/null_image.png';
        }
        return '/uploads/' . $this->image;
    }

    public function getCategoryTitle()
    {
        if($this->category != null){
            return $this->category->title;
        }
        return 'Нет категории';
    }

        public function getCategoryId()
    {
        if($this->category != null){
            return $this->category->id;
        }
        return null;
    }

    public function getTagsTitles()
    {

        if($this->tags != null){
            return implode(', ', $this->tags->pluck('title')->all());

        }
        return 'Нет тегов';
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

        $this->tags()->sync($ids);
        $this->save();
    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if($value == null){
           return $this->setDraft();
        }
        return $this->setPublic();
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if($value == null){
           return $this->setStandart();
        }
        return $this->setFeatured();
    }

    // public function setDateAttribute($value)
    // {

    //     if(\DateTime::createFromFormat('d/m/y', $value) !== false){
    //         $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
    //         $this->attributes['date'] = $date;
    //     }
    //     $this->attributes['date'] = $value;

    // }

    // public function getDateAttribute($value)
    // {
    //     if(\DateTime::createFromFormat('Y-m-d', $value) !== false){
    //         $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
    //         return $date;
    //     }

    // }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }


    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious();
        return self::find($postID);
    }

    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext();
        return self::find($postID);
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

     public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

}
