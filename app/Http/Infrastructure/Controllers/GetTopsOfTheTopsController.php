<?php

//
//namespace App\Http\Controllers;
//
//use App\Services\DatabaseConnectionService;
//use DateTime;
//use Exception;
//use Illuminate\Http\JsonResponse;
//use Illuminate\Http\Request;
//use App\Services\TokenService;
//use App\Services\VideosService;
//use App\Services\TopThreeService;
//use mysqli_result;
//
//class GetTopsOfTheTopsController extends Controller
//{
//    private TokenService $tokenService;
//    private TopThreeService $topThreeService;
//    private VideosService $videosService;
//    private DatabaseConnectionService $databaseConnection;
//
//    public function __construct()
//    {
//        $this->tokenService = new TokenService();
//        $this->topThreeService = new TopThreeService();
//        $this->videosService = new VideosService();
//        $this->databaseConnection = new DatabaseConnectionService();
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function topsOfTheTops(Request $request): JsonResponse
//    {
//        $token = $this->tokenService->getToken();
//        $topThreeGames = $this->topThreeService->top3($token);
//        $conn = $this->databaseConnection->__construct();
//
//        $time = $this->setTime($request);
//
//        $finalResult = $this->resultArray($time, $token, $topThreeGames, $conn);
//
//        return response()->json($finalResult);
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function resultArray($time, $token, $topThreeGames, $conn): array
//    {
//        $date = date('Y-m-d H:i:s');
//
//        $result = $this->getTopStreamers($conn);
//
//        $finalResult = array();
//
//        if ($result->num_rows > 0) {
//            $allIds = $this->getAllIds($result);
//            $inTime = $this->getInTimeStreamers($result, $time);
//            foreach ($topThreeGames['data'] as $game) {
//                $boolean = true;
//                foreach ($inTime as $inTimeStreamer) {
//                    if ($game['id'] === $inTimeStreamer['game_id']) {
//                        $finalResult[] = $inTimeStreamer;
//                        $boolean = false;
//                    }
//                }
//                if ($boolean) {
//                    $gameNotFoundVideos = $this->videosService->videos($token, $game);
//                    $finalResult[] = $gameNotFoundVideos;
//                    if (in_array($game['id'], $allIds)) {
//                        $this->updateStreamer($conn, $gameNotFoundVideos, $date);
//                    }
//                    if (!in_array($game['id'], $allIds)) {
//                        $this->insertStreamer($conn, $gameNotFoundVideos, $date);
//                    }
//                }
//            }
//        }
//        if ($result->num_rows <= 0) {
//            foreach ($topThreeGames['data'] as $game) {
//                $gameVideos = $this->videosService->videos($token, $game);
//                $finalResult[] = $gameVideos;
//                $this->insertStreamer($conn, $gameVideos, $date);
//            }
//        }
//
//        $conn->close();
//
//        return $finalResult;
//    }
//
//    public function setTime($request): int
//    {
//        if ($request->has('since')) {
//            return intval($request->query('since'));
//        }
//        return 600;
//    }
//
//    public function getTopStreamers($conn): mysqli_result
//    {
//        $sql = "SELECT * FROM topsofthetops";
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();
//        $result = $stmt->get_result();
//        $stmt->close();
//        return $result;
//    }
//
//    public function insertStreamer($conn, $gameVideos, $date): void
//    {
//        $sql = "INSERT INTO topsofthetops VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//        $stmt = $conn->prepare($sql);
//        $stmt->bind_param(
//            "sssiisisss",
//            $gameVideos['game_id'],
//            $gameVideos['game_name'],
//            $gameVideos['user_name'],
//            $gameVideos['total_videos'],
//            $gameVideos['total_views'],
//            $gameVideos['most_viewed_title'],
//            $gameVideos['most_viewed_views'],
//            $gameVideos['most_viewed_duration'],
//            $gameVideos['most_viewed_created_at'],
//            $date
//        );
//        $stmt->execute();
//        $stmt->close();
//    }
//
//    public function updateStreamer($conn, $gameVideos, $date): void
//    {
//        $sql = "UPDATE topsofthetops
//                    SET user_name = ?, total_videos = ?, total_views = ?, most_viewed_title = ?,
//                    most_viewed_views = ?, most_viewed_duration = ?, most_viewed_created_at = ?, date = ?
//                    WHERE game_id = ?";
//        $stmt = $conn->prepare($sql);
//        $stmt->bind_param(
//            "siisissss",
//            $gameVideos['user_name'],
//            $gameVideos['total_videos'],
//            $gameVideos['total_views'],
//            $gameVideos['most_viewed_title'],
//            $gameVideos['most_viewed_views'],
//            $gameVideos['most_viewed_duration'],
//            $gameVideos['most_viewed_created_at'],
//            $date,
//            $gameVideos['game_id']
//        );
//        $stmt->execute();
//        $stmt->close();
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function getInTimeStreamers($result, $time): array
//    {
//        $inTime = array();
//
//        while ($line = $result->fetch_assoc()) {
//            $date1 = new DateTime($line['date']);
//            $date2 = new DateTime();
//            $difference = $date1->diff($date2);
//            $minutes = ($difference->days * 24 * 60) + ($difference->h * 60) + $difference->i;
//            if ($minutes < $time / 60) {
//                unset($line['date']);
//                $inTime[] = $line;
//            }
//        }
//
//        return $inTime;
//    }
//
//    public function getAllIds($result): array
//    {
//        $allIds = array();
//        while ($line = $result->fetch_assoc()) {
//            $allIds[] = $line['game_id'];
//        }
//        return $allIds;
//    }
//}
