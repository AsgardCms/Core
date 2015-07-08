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
use Illuminate\Support\ViewErrorBag;

Form::macro('i18nInput', function ($name, $title, ViewErrorBag $errors, $lang, $object = null, array $options = []) {
    $options = array_merge(['class' => "form-control", 'placeholder' => $title], $options);

    $string  = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::text("{$lang}[{$name}]", Input::old("{$lang}[{$name}]", $currentData), $options);
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
Form::macro('i18nTextarea', function ($name, $title, ViewErrorBag $errors, $lang, $object = null, array $options = []) {
    $options = array_merge(['class' => 'ckeditor', 'rows' => 10, 'cols' => 10], $options);

    $string  = "<div class='form-group " . ($errors->has($lang . '.' . $name) ? ' has-error' : '') . "'>";
    $string .= Form::label("{$lang}[{$name}]", $title);

    if (is_object($object)) {
        $currentData = $object->hasTranslation($lang) ? $object->translate($lang)->{$name} : '';
    } else {
        $currentData = '';
    }

    $string .= Form::textarea("{$lang}[$name]", Input::old("{$lang}[{$name}]", $currentData), $options);
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
Form::macro('i18nCheckbox', function ($name, $title, ViewErrorBag $errors, $lang, $object = null) {
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
Form::macro('normalInput', function ($name, $title, ViewErrorBag $errors, $object = null, array $options = []) {
    $options = array_merge(['class' => "form-control", 'placeholder' => $title], $options);

    $string  = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = $object->{$name} ?: '';
    } else {
        $currentData = '';
    }

    $string .= Form::text($name, Input::old($name, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

Form::macro('normalTextarea', function ($name, $title, ViewErrorBag $errors, $object = null, array $options = []) {
    $options = array_merge(['class' => 'ckeditor', 'rows' => 10, 'cols' => 10], $options);

    $string  = "<div class='form-group " . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= Form::label($name, $title);

    if (is_object($object)) {
        $currentData = $object->{$name} ?: '';
    } else {
        $currentData = '';
    }

    $string .= Form::textarea($name, Input::old($name, $currentData), $options);
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</div>";

    return $string;
});

/**
 * Add a checkbox input field
 * @param string $name The field name
 * @param string $title The field title
 * @param object $errors The laravel errors object
 * @param null|object $object The entity of the field
 */
Form::macro('normalCheckbox', function ($name, $title, ViewErrorBag $errors, $object = null) {
    $string = "<div class='checkbox" . ($errors->has($name) ? ' has-error' : '') . "'>";
    $string .= "<input type='hidden' value='0' name='{$name}'/>";
    $string .= "<label for='$name'>";
    $string .= "<input id='$name' name='$name' type='checkbox' class='flat-blue'";

    if (is_object($object)) {
        $currentData = isset($object->$name) && (bool) $object->$name ? 'checked' : '';
    } else {
        $currentData = false;
    }

    $oldInput = Input::old($name, $currentData) ? 'checked' : '';
    $string .= "value='1' {$oldInput}>";
    $string .= $title;
    $string .= $errors->first($name, '<span class="help-block">:message</span>');
    $string .= "</label>";
    $string .= "</div>";

    return $string;
});
