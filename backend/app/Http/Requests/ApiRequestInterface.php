<?php

namespace SkPro\Http\Requests;

use Illuminate\Support\Collection;

interface ApiRequestInterface
{
    public function authorize(): bool;

    public function rules(): array;
}
