<?php namespace Modules\Core\Internationalisation;

use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Set the translation key prefix for attributes.
     * @var string
     */
    protected $translationsAttributesKey = 'validation.attributes.';
    /**
     * Set the translation key prefix for messages.
     * @var string
     */
    protected $translationsMessagesKey = 'validation.messages.';

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

        $translationsAttributesKey = $this->getTranslationsAttributesKey();
        $translationsMessagesKey = $this->getTranslationsMessagesKey();

        foreach ($this->requiredLocales() as $localeKey => $locale) {
            foreach ($this->container->call([$this, 'translationRules']) as $attribute => $rule) {
                $key = $localeKey . '.' . $attribute;
                $rules[$key] = $rule;
                $attributes[$key] = trans($translationsAttributesKey . $attribute);
                $messages[$key . '.' . $rule] = trans($translationsMessagesKey . $attribute);
            }
        }

        return $factory->make(
            $this->all(), $rules, array_merge($this->messages(), $messages), $attributes
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

    /**
     * Get the validation for attributes key from the implementing class
     * or use a sensible default
     * @return string
     */
    private function getTranslationsAttributesKey()
    {
        return rtrim($this->translationsAttributesKey, '.') . '.';
    }

    /**
     * Get the validation key for messages from the implementing class
     * or use a sensible default
     * @return string
     */
    private function getTranslationsMessagesKey()
    {
        return rtrim($this->translationsMessagesKey, '.') . '.';
    }
}
