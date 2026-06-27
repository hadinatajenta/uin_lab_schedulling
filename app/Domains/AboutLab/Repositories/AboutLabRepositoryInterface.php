<?php

namespace App\Domains\AboutLab\Repositories;

use App\Domains\AboutLab\Models\AboutLab;

interface AboutLabRepositoryInterface
{
    /**
     * Get the first AboutLab record.
     *
     * @return AboutLab|null
     */
    public function getFirst(): ?AboutLab;
}
