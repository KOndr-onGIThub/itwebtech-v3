<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Fieldset;

class ProjectController extends ModuleController
{
    protected $moduleName = 'projects';

    protected $titleColumnKey = 'title';

    public function getForm(TwillModelContract $model): Form
    {
        return Form::make([
            Fieldset::make()->title('Základní info')->fields([
                Input::make()->name('title')->label('Název projektu')->translatable()->required(),
                Input::make()->name('description')->label('Popis (krátký)')->translatable(),
                Input::make()->name('customer')->label('Zákazník'),
                Input::make()->name('kind')->label('Typ projektu'),
                Input::make()->name('price_czk')->label('Cena CZK'),
                Input::make()->name('price_eur')->label('Cena EUR'),
            ]),
            Fieldset::make()->title('Obsah')->fields([
                Wysiwyg::make()->name('content')->label('Obsah projektu')->translatable(),
                Input::make()->name('cta')->label('CTA text')->translatable(),
            ]),
            Fieldset::make()->title('Testimonial')->fields([
                Input::make()->name('client_name')->label('Jméno klienta'),
                Input::make()->name('client_role')->label('Role klienta'),
                Input::make()->name('client_says')->label('Co říká klient')->translatable(),
                Input::make()->name('testimonial')->label('Nadpis testimonial bloku')->translatable(),
            ]),
        ]);
    }
}
