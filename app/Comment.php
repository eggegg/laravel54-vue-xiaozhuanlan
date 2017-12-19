<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body', 'post_id'];

    protected $casts = [
        'post_id' => 'integer',
        'user_id' => 'integer',
        'approved' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @param bool $forUpdate
     * @return array
     */
    public function getValidationRules($forUpdate = false)
    {
        $createRule = [
            'post_id' => 'required|integer|exists:posts,id',
            'body' => 'required|min:3'
        ];

        $updateRule = [
            'body' => 'required|min:3'
        ];

        return $forUpdate ? $updateRule : $createRule;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'avatar');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
