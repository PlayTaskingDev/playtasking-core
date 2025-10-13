<?php

namespace App\Rules;

use App\Models\Campaign;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\DataAwareRule;

class NoOverlapDate implements DataAwareRule, InvokableRule
{
    protected $data = [];

    public function setData($data)
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (isset($this->data['id'])) {
            $campaign = Campaign::whereRaw('init_date BETWEEN ? AND ? AND `id` != ?', [$this->data['init_date'],$this->data['end_date'],$this->data['id']])
            ->orWhereRaw('end_date BETWEEN ? AND ? AND `id` != ?', [$this->data['init_date'],$this->data['end_date'],$this->data['id']])->first();
        } else {
            $campaign = Campaign::whereRaw('init_date BETWEEN ? AND ?', [$this->data['init_date'],$this->data['end_date']])
            ->orWhereRaw('end_date BETWEEN ? AND ?', [$this->data['init_date'],$this->data['end_date']])->first();
        }

        if ($campaign) {
            $fail('validation.overlap_date')->translate();
        }
    }
}
