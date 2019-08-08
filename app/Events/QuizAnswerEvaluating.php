<?php declare(strict_types=1);

namespace App\Events;

use App\GraderInterface;
use App\QuizAnswer;
use Illuminate\Queue\SerializesModels;

final class QuizAnswerEvaluating
{
    use SerializesModels;

    /** @var QuizAnswer */
    public $quizAnswer;

    /** @var int */
    public $score;

    /** @var GraderInterface */
    public $grader;

    public function __construct(QuizAnswer $quizAnswer, int $score, GraderInterface $grader)
    {
        $this->quizAnswer = $quizAnswer;
        $this->score = $score;
        $this->grader = $grader;
    }
}
