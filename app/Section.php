<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * Модель отделов
 *
 * @package App
 */
class Section extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'logo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Все пользователи текущего отдела (из pivot-таблицы section_user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
