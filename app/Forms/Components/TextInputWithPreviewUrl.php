<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class TextInputWithPreviewUrl extends Field
{
    public $prependText;

    protected string $view = 'forms.components.text-input-with-preview-url';

    public function setPrependText(string $text): static
    {
        $this->prependText = $text;
        return $this;
    }

    public function getPrependText(): string
    {
        return $this->prependText;
    }
}
