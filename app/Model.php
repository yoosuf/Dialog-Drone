<?php
namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{   
    /**
     * Convert values to (int)
     * useful for json encode
     */
    protected $integers = ['id'];

    protected $strings = [];

    public function getIntegerFields()
    {
        return $this->integers;
    }

    public function getStringFields()
    {
        return $this->strings;
    }

    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        if (in_array($key, $this->getIntegerFields()))
            return (int) $value;
        elseif (in_array($key, $this->getStringFields()))
            return (string) $value;
        else
            return $value;
    }

    public function attributesToArray()
    {
        $attributes = $this->getArrayableAttributes();

        // If an attribute is a date, we will cast it to a string after converting it
        // to a DateTime / Carbon instance. This is so we will get some consistent
        // formatting while accessing attributes vs. arraying / JSONing a model.
        foreach ($this->getDates() as $key)
        {
            if ( ! isset($attributes[$key])) continue;

            $attributes[$key] = $this->asDateTime($attributes[$key])->toIso8601String();
        }

        // We want to spin through all the mutated attributes for this model and call
        // the mutator for the attribute. We cache off every mutated attributes so
        // we don't have to constantly check on attributes that actually change.
        foreach ($this->getMutatedAttributes() as $key)
        {
            if ( ! array_key_exists($key, $attributes)) continue;

            $attributes[$key] = $this->mutateAttributeForArray(
                $key, $attributes[$key]
            );
        }

        // Here we will grab all of the appended, calculated attributes to this model
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the model for convenience to the coder.
        foreach ($this->getArrayableAppends() as $key)
        {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        foreach ($this->getIntegerFields() as $key) {
            if (array_key_exists($key, $attributes))
                $attributes[$key] = (int) $attributes[$key];
        }

        foreach ($this->getStringFields() as $key) {
            if (array_key_exists($key, $attributes))
                $attributes[$key] = (string) $attributes[$key];
        }

        return $attributes;
    }

    protected function formatImageUrlAttribute($key)
    {
        $path = $this->attributes[$key];

        if (starts_with($path, 'http://'))
            return $path;

        if (empty($path))
            return '';
        else
            return asset($path);
    }
}