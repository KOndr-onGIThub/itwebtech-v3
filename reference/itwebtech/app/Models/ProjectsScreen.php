<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectsScreen extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_project', 'screen_shot', 'sorting', 'description',
    ];
}
