<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;


class SocialProvider extends Model
{
    use Sushi;

    protected $rows = [];

    public function getRows()
    {
        // Fetching the social providers from the configuration file
        $rowsArray = [];
        $socialProviders = config('socialite', []);

        foreach ($socialProviders as $key => $provider) {
            $provider['slug'] = $key;
            array_push($rowsArray, $provider);
        }

        $this->rows = $rowsArray;

        return $this->rows;
    }

    protected function sushiShouldCache()
    {
        if (app()->isLocal()) {
            return false;
        }

        return true;
    }
}
