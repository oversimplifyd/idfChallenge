<?php declare(strict_types=1);

namespace App\Repositories;

use App\CourseEnrollment;
use App\Repository\Eloquent\Repository as Repo;

class CourseRepoistory extends Repo
{

    public function model()
    {
        return CourseEnrollment::class;
    }
}
