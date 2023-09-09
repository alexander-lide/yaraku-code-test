<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class LibraryCollection extends Collection
{
    // Sort collection by key
    public function sortCollection(string $key, string $order = 'asc'):Collection
    {
        if ($order === 'desc')
        {
            return $this->sortByDesc($key);
        }
        else
        {
            return $this->sortBy($key);
        }
    }

    // Filter collection
    public function filterBy(string $key, string $value):Collection
    {
        return $this->filter(function ($item) use ($key, $value) {
            return stripos($item[$key],$value) !== false;
        });
    }

    // Return array ready to use as options in FormSelect component
    public function getAsOptionsArray( string $value = 'id', string $name ):array
    {
        $options = [];
        foreach ($this as $item)
        {
            $options[$item[$value]] = $item[$name];    
        }

        return $options;
    }
}