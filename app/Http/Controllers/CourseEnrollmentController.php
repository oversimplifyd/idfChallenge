<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Course;
use App\Repositories\CourseRepoistory;
use App\Services\LeaderBoard;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Response;
use App\Repositories\CourseEnrollmentRepository;

class CourseEnrollmentController extends Controller
{

    private $courseEnrolmentRepo;
    private $courseRepo;

    public function __construct(CourseEnrollmentRepository $ceRepo, CourseRepoistory $courseRepo)
    {
        $this->courseEnrolmentRepo = $ceRepo;
        $this->courseRepo = $courseRepo;
    }

    public function show(string $slug): Renderable
    {
        /** @var Course $course */
        $board = $this->courseRepo->getCourseLeaderboard($slug);
        $leaderBoard = LeaderBoard::reGroupUsers($board->toArray());

        $course = $this->courseRepo
                ->findBy('slug', $slug) ??
            abort(Response::HTTP_NOT_FOUND, 'Course not found');

        $enrollment = $this->courseEnrolmentRepo->getUserLessons($course->id, auth()->user()->id);

        if ($enrollment === null) {
            return view('courses.show', ['course' => $course]);
        }

        return view('courseEnrollments.show', ['enrollment' => $enrollment, 'leaderboard' => $leaderBoard]);
    }

    public function store(string $slug)
    {
        /** @var Course $course */
        $course = $this->courseRepo
                ->findBy('slug', $slug) ??
            abort(Response::HTTP_NOT_FOUND, 'Course not found');

        $course->enroll(auth()->user());

        return redirect()->action([self::class, 'show'], [$course->slug]);
    }
}
