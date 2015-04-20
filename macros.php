<?php

/*
|--------------------------------------------------------------------------
| Translatable fields
|--------------------------------------------------------------------------
*/
Form::macro('i18nInput', function ($name, $title, $errors, $lang) {
    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);
    $string .= Form::text("{$lang}[{$name}]", Input::old("{$lang}[{$name}]"),
        ['class' => "form-control", 'placeholder' => $title]);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

Form::macro('i18nTextarea', function ($name, $title, $errors, $lang) {
    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);
    $oldInput = Input::old("{$lang}.{$name}");
    $string .= "<textarea class='ckeditor' name='{$lang}[$name]' rows='10' cols='80'>{$oldInput}</textarea>";
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

Form::macro('i18nCheckbox', function($name, $title, $errors, $lang)
{
    $string = "<div class='checkbox" . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= "<label for='{$lang}[{$name}]'>";
    $string .= "<input id='{$lang}[{$name}]' name='{$lang}[{$name}]' type='checkbox' class='flat-blue'";
    $oldInput = Input::old("{$lang}.$name") ? 'checked' : '';
    $string .= "value='1' {$oldInput}>";
    $string .= $title;
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</label>";
    $string .= "</div>";

    return $string;
});

/*
|--------------------------------------------------------------------------
| Standard fields
|--------------------------------------------------------------------------
*/
Form::macro('normalInput', function ($name, $title, $errors) {
    $string = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);
    $string .= Form::text($name, Input::old($name),
        ['class' => "form-control", 'placeholder' => $title]);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});
