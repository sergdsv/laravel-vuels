<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Comment extends Model
{
    public function post()
    {
    	return $this->belongsTo(Post::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function allow()
    {
        $this->status = 1;
        $this->save();
    }

    public function disAllow()
    {
        $this->status = 0;
        $this->save();
    }

    public function toggleStatus()
    {

        if($this->status == 0){
            return $this->allow();
        }
        return $this->disAllow();
    }

    public function remove()
    {
    	$this->delete();
    }

}
