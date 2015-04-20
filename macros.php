<?php

/*
|--------------------------------------------------------------------------
| Translatable fields
|--------------------------------------------------------------------------
*/
Form::macro('i18nInput', function($name, $title, $errors, $lang)
{
    $string = "<div class='form-group " . ($errors->has($lang.'.'.$name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);
    $string .= Form::text("{$lang}[{$name}]", Input::old("{$lang}[title]"), ['class' => "form-control", 'placeholder' => $title]);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

/*
|--------------------------------------------------------------------------
| Standard fields
|--------------------------------------------------------------------------
*/
