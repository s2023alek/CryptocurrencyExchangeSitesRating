<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Error;
use function Nette\Utils\first;

class SiteSettingsModel extends Model
{
    protected $table = 'site_settings';
    public $timestamps = false;

    public function updateSetting($name, $value) {
        $s = $this->query()->where('name', '=', $name)->get()->first();
        if ($s === null) {
            //echo 'setting '.$name.' not found, creating...';
            $this->name = $name;
            $this->value = $value;
            $this->save();
            return;
        }

        //echo 'setting '.$name.' found, updating...';
        $this->query()->where('name', '=', $name)->update([
            'value' => $value
        ]);
    }

/*
name - setting name
value - setting value
*/

}
