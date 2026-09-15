<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Form;

class ArticleController extends ModuleController
{
    protected $moduleName = 'articles';

    protected $titleColumnKey = 'title';

    protected function additionalIndexTableColumns(): \A17\Twill\Services\Listings\Columns\TwillColumns
    {
        return parent::additionalIndexTableColumns();
    }

    public function getForm(TwillModelContract $model): Form
    {
        return Form::make([
            Input::make()->name('title')->label('Nadpis')->translatable()->required(),
            Input::make()->name('description')->label('Popis (perex)')->translatable(),
            Wysiwyg::make()->name('perex')->label('Perex')->translatable(),
            Wysiwyg::make()->name('content_1')->label('Obsah 1')->translatable(),
            Wysiwyg::make()->name('content_mid')->label('Obsah 2 (uprostřed)')->translatable(),
            Wysiwyg::make()->name('content_2')->label('Obsah 3')->translatable(),
            Wysiwyg::make()->name('bonus')->label('Bonus')->translatable(),
            Wysiwyg::make()->name('extra')->label('Extra')->translatable(),
        ]);
    }
}
