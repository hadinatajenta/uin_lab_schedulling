<?php

namespace App\Domains\AboutLab\Repositories;

use App\Domains\AboutLab\Models\AboutLab;

class AboutLabRepository implements AboutLabRepositoryInterface
{
    public function getFirst(): ?AboutLab
    {
        return AboutLab::first();
    }
}
