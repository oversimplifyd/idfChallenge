<?php declare(strict_types=1);

namespace App\Services;

class LeaderBoard
{

    private const MAX_DISPLAY_ITEM = 9;
    private const MIN_DISPLAY_ITEM = 3;

    /**
     * @param array $courseUsers
     */
    public static function reGroupUsers(array $courseUsers)
    {
        $leaderBoardCount = count($courseUsers);
        $result = [];
        if ($leaderBoardCount >= self::MIN_DISPLAY_ITEM) {
            $result['lowest'] = self::getLowest($courseUsers, self::MIN_DISPLAY_ITEM);
            $result['highest'] = self::getHighest($courseUsers, self::MIN_DISPLAY_ITEM);

            if ($leaderBoardCount >= self::MAX_DISPLAY_ITEM) {
                $result['list'] = self::getDisplayItem($courseUsers, self::MAX_DISPLAY_ITEM);
            } else {
                $result['list'] = self::getDisplayItem($courseUsers, $leaderBoardCount);
            }
        }

        $result['neighbours'] = self::getLoggedInUserPosition($courseUsers);

        return $result;
    }

    /**
     * @param array $board
     * @param int $count
     * @return array
     */
    private static function getHighest(array $board, int $count)
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $board[$i];
        }

        return self::reArrange($result);
    }

    /**
     * @param array $board
     * @param int $count
     */
    private static function getLowest(array $board, int $count)
    {
        $boardCount = count($board);
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            $board[$boardCount -$i]->position = $boardCount -$i + 1;
            $result[] = $board[$boardCount - $i];
        }

        return self::reArrange($result);
    }

    /**
     * @param array $board
     * @param int $count
     */
    private static function getDisplayItem(array $board, int $count)
    {
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $board[$i];
        }

        return self::reArrange($result);
    }

    private static function getLoggedInUserPosition(array $board)
    {
        $boardCount = count($board);
        $result = [];
        for ($i = 0; $i < $boardCount; $i++) {
            if ($board[$i]->userid === auth()->user()->id) {
                if ($i > 0 && $i < $boardCount - 1) {
                    $board[$i - 1]->position = $i -1;
                    $board[$i]->position = $i;
                    $board[$i + 1]->position = $i + 1;

                    $result[$i - 1] = $board[$i - 1];
                    $result[$i] = $board[$i]; //currently logged in user
                    $result[$i + 1] = $board[$i + 1];
                }
            }
        }

        return self::reArrange($result);
    }

    /**
     * @param array $result
     * @return array
     */
    private static function reArrange(array $result)
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
