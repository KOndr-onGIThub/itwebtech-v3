<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Http\Controllers\Admin\NestedModuleController as BaseModuleController;
use A17\Twill\Services\Forms\Fields\BlockEditor;
use A17\Twill\Services\Forms\Fields\Tags;
use A17\Twill\Services\Forms\Fields\Wysiwyg;

class ArticleController extends BaseModuleController
{
    protected $moduleName = 'articles';
    protected $showOnlyParentItemsInBrowsers = true;
    protected $nestedItemsDepth = 1;
    /**
     * This method can be used to enable/disable defaults. See setUpController in the docs for available options.
     */
    protected function setUpController(): void
    {
        $this->enableReorder();
        $this->setPermalinkBase('');
        $this->withoutLanguageInPermalink();
    }

    /**
     * See the table builder docs for more information. If you remove this method you can use the blade files.
     * When using twill:module:make you can specify --bladeForm to use a blade form instead.
     */
    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(Tags::make());
        $form->add(Input::make()->name('description')->label('Popis')->translatable());
        $form->add(Input::make()->name('img_preview')->label('img preview')->translatable());
        $form->add(Input::make()->name('img_main')->label('img main')->translatable());
        $form->add(Wysiwyg::make()->name('perex')->type('tiptap')->label('perex')->translatable());
        $form->add(Wysiwyg::make()->name('content_1')->type('tiptap')->label('content 1')->translatable());
        $form->add(Wysiwyg::make()->name('content_mid')->type('tiptap')->label('content mid')->translatable());
        $form->add(Input::make()->name('img_mid')->label('img mid')->translatable());
        $form->add(Wysiwyg::make()->name('content_2')->type('tiptap')->label('content 2')->translatable());
        $form->add(Input::make()->name('img_end')->label('img end')->translatable());
        $form->add(Wysiwyg::make()->name('bonus')->type('tiptap')->label('bonus')->translatable());
        $form->add(Wysiwyg::make()->name('extra')->type('tiptap')->label('extra')->translatable());




        $form->add( BlockEditor::make()->blocks(['perex', 'content_1', 'content_mid', 'text'])->label('content 2'));

        return $form;
    }

    /**
     * This is an example and can be removed if no modifications are needed to the table.
     */
    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('description')->title('Description')
        );

        return $table;
    }
}
