<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

/**
 * Pole vlastností, které nejsou chráněné před mass assignment útokem.
 *
 * @var array
 */
protected $fillable = [
    'url', 'name', 'sorting', 'img', 'description', 'content', 
];



}
