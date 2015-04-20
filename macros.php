<?php

/*
|--------------------------------------------------------------------------
| Translatable fields
|--------------------------------------------------------------------------
*/
/**
 * Add an input field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 */
Form::macro('i18nInput', function ($name, $title, $errors, $lang, $object = null) {
    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::text("{$lang}[{$name}]", Input::old("{$lang}[{$name}]", $currentData),
        ['class' => "form-control", 'placeholder' => $title]);
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

/**
 * Add a textarea
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 */
Form::macro('i18nTextarea', function ($name, $title, $errors, $lang, $object = null) {
    $string = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $oldInput = Input::old("{$lang}.{$name}", $currentData);
    $string .= "<textarea class='ckeditor' name='{$lang}[$name]' rows='10' cols='80'>{$oldInput}</textarea>";
    $string .= $errors->first("{$lang}.{$name}", '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

/**
 * Add a checkbox input field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param string $lang the language of the field
 * @param null|object $object The entity of the field
 */
Form::macro('i18nCheckbox', function($name, $title, $errors, $lang, $object = null)
{
    $string = "<div class='checkbox" . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= "<label for='{$lang}[{$name}]'>";
    $string .= "<input id='{$lang}[{$name}]' name='{$lang}[{$name}]' type='checkbox' class='flat-blue'";

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? (bool) $object->translate($lang)->{$name} : '';
    } else {
        $currentData = false;
    }

    $oldInput = Input::old("{$lang}.$name", $currentData) ? 'checked' : '';
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
/**
 * Add an input field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param null|object $object The entity of the field
 */
Form::macro('normalInput', function ($name, $title, $errors, $object = null) {
    $string = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = $object->{$name} ?: '';
    } else {
        $currentData = '';
    }

    $string .= Form::text($name, Input::old($name, $currentData),
        ['class' => "form-control", 'placeholder' => $title]);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});
