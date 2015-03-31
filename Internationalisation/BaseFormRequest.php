<?php namespace Modules\Core\Internationalisation;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Get the validator instance for the request.
     * @return \Illuminate\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $factory = $this->container->make('Illuminate\Validation\Factory');
        if (method_exists($this, 'validator')) {
            return $this->container->call([$this, 'validator'], compact('factory'));
        }

        $rules = $this->container->call([$this, 'rules']);
        $attributes = $this->attributes();
        foreach ($this->requiredLocales() as $key => $locale) {
            foreach ($this->container->call([$this, 'translationRules']) as $attribute => $rule) {
                $key = "{$key}[{$attribute}]";
                $rules[$key] = $rule;
                $attributes[$key] = trans('validation.attributes.' . $attribute);
            }
        }

        return $factory->make(
            $this->all(), $rules, $this->messages(), $attributes
        );
    }

    /**
     * @return array
     */
    public function withTranslations()
    {
        $results = $this->all();
        $translations = [];
        foreach ($this->requiredLocales() as $key => $locale) {
            $locales[] = $key;
            $translations[$key] = $this->get($key);
        }
        $results['translations'] = $translations;
        array_forget($results, $locales);

        return $results;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function requiredLocales()
    {
        return LaravelLocalization::getSupportedLocales();
    }
}
