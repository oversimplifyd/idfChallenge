<?php declare(strict_types=1);

namespace App\Repositories;

use App\CourseEnrollment;
use App\Repository\Eloquent\Repository as Repo;

class CourseEnrollmentRepository extends Repo
{

    public function model()
    {
        return CourseEnrollment::class;
    }

    public function getUserLessons(int $courseId, int $userId)
    {
        return ($this->model())
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->with('course.lessons')
            ->first();
    }
}
