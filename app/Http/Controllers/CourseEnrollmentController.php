<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Course;
use App\Repositories\CourseRepoistory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Response;
use App\Repositories\CourseEnrollmentRepository;

class CourseEnrollmentController extends Controller
{

    private const MAX_DISPLAY_ITEM = 9;
    private const MIN_DISPLAY_ITEM = 3;

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
        $leaderBoard = $this->reGroupUsers($board->toArray());

        $course = $this->courseRepo
                ->findBy('slug', $slug) ??
            abort(Response::HTTP_NOT_FOUND, 'Course not found');

        $enrollment = $this->courseEnrolmentRepo->getUserLessons($course->id, auth()->id);

        if ($enrollment === null) {
            return view('courses.show', ['course' => $course]);
        }

        return view('courseEnrollments.show', ['enrollment' => $enrollment]);
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

    /**
     * @param array $courseUsers
     */
    private function reGroupUsers(array $courseUsers)
    {
        $leaderBoardCount = count($courseUsers);
        $result = [];
        if ($leaderBoardCount >= self::MIN_DISPLAY_ITEM) {
            $result['lowest'] = $this->getLowest($courseUsers, self::MIN_DISPLAY_ITEM);
            $result['highest'] = $this->getHighest($courseUsers, self::MIN_DISPLAY_ITEM);

            if ($leaderBoardCount >= self::MAX_DISPLAY_ITEM) {
                $result['list'] = $this->getDisplayItem($courseUsers, self::MAX_DISPLAY_ITEM);
            } else {
                $result['list'] = $this->getDisplayItem($courseUsers, $leaderBoardCount);
            }
        }

        return $result;
    }

    /**
     * @param array $board
     * @param int $count
     * @return array
     */
    private function getHighest(array $board, int $count)
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $board[$i];
        }

        return $this->reArrange($result);
    }

    /**
     * @param array $board
     * @param int $count
     */
    private function getLowest(array $board, int $count)
    {
        $boardCount = count($board);
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            $result[] = $board[$boardCount - $i];
        }

        return $this->reArrange($result);
    }

    /**
     * @param array $board
     * @param int $count
     */
    private function getDisplayItem(array $board, int $count)
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $board[$i];
        }

        return $this->reArrange($result);
    }

    /**
     * @param array $result
     * @return array
     */
    private function reArrange(array $result)
    {
        $loggedInUserId = auth()->user()->id;
        usort($result, function($a, $b) use($loggedInUserId) {
            if ($a->score === $b->score) {
                if ($b->userid === $loggedInUserId) {
                    return 1;
                }
            }
            return 0;
        });

        return $result;
    }
}
