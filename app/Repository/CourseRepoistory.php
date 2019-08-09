<?php declare(strict_types=1);

namespace App\Repositories;

use App\Course;
use App\Repository\Eloquent\Repository as Repo;
use Illuminate\Support\Facades\DB;

class CourseRepoistory extends Repo
{

    public function model()
    {
        return Course::class;
    }

    public function getCourseLeaderboard(string $slug)
    {
        return DB::table('courses')
            ->join('lessons', 'courses.id', '=', 'lessons.course_id')
            ->join('quizzes', 'lessons.id', '=', 'quizzes.lesson_id')
            ->join('quiz_answers', 'quizzes.id', '=', 'quiz_answers.quiz_id')
            ->join('users', 'quiz_answers.user_id', '=', 'users.id')
            ->select(DB::raw('SUM(quiz_answers.score) as score'),'users.name', 'users.id as userid', 'courses.id as courseid', 'courses.title')
            ->where('slug', $slug)
            ->groupBy('users.name', 'users.id', 'courses.id', 'courses.title')
            ->orderBy('score', 'desc')
            ->get();
    }
}
