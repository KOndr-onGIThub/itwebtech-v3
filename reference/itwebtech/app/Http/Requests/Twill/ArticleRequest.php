<?php

namespace App\Http\Requests\Twill;

use A17\Twill\Http\Requests\Admin\Request;

class ArticleRequest extends Request
{
    public function rulesForCreate()
    {
        return [
            'title' => ['required', 'max:200'],
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }
}
