<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use mysqli;

include base_path('app/Http/topsofthetops/videos.php');
include base_path('app/Http/topsofthetops/top3.php');
include base_path('app/Http/topsofthetops/token.php');

class topsofthetopsController extends Controller
{
    public function topsOfTheTops(Request $request)
    {
        $token = \App\Http\topsofthetops\token();

        $top3_juegos = \App\Http\topsofthetops\top3($token);

        $host_name = env('DB_HOST');
        $database = env('DB_DATABASE');
        $user_name = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $conn = new mysqli($host_name, $user_name, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        if ($request->has('since')) {
            $tiempo = $request->query('since');
        } else {
            $tiempo = 600;
        }

        $sql = "SELECT * FROM topsofthetops";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $resultado_final = array();

        if ($result->num_rows > 0) {
            $cumplen = array();
            $ids_toda_la_base = array();
            while ($linea = $result->fetch_assoc()) {
                $fecha1 = new DateTime($linea['fecha']);
                $fecha2 = new DateTime();
                $diferencia = $fecha1->diff($fecha2);
                $minutos = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;

                if ($minutos < $tiempo / 60) {
                    unset($linea['fecha']);
                    $cumplen[] = $linea;
                }

                $ids_toda_la_base[] = $linea['game_id'];
            }
            foreach ($top3_juegos['data'] as $juego) {
                $booleano = true;
                foreach ($cumplen as $cumplen_base) {
                    if ($juego['id'] == $cumplen_base['game_id']) {
                        $resultado_final[] = $cumplen_base;
                        $booleano = false;
                    }
                }
                if ($booleano) {
                    $videos_juego_no_encontrado = \App\Http\topsofthetops\videos($token, $juego);
                    $resultado_final[] = $videos_juego_no_encontrado;
                    $fecha = date('Y-m-d H:i:s');
                    if (in_array($juego['id'], $ids_toda_la_base)) {
                        $nuevo_juego_sql = "UPDATE topsofthetops
                    SET user_name = ?, total_videos = ?, total_views = ?, most_viewed_title = ?,
                    most_viewed_views = ?, most_viewed_duration = ?, most_viewed_created_at = ?, fecha = ?
                    WHERE game_id = ?";
                        $stmt3 = $conn->prepare($nuevo_juego_sql);
                        $stmt3->bind_param(
                            "siisissss",
                            $videos_juego_no_encontrado['user_name'],
                            $videos_juego_no_encontrado['total_videos'],
                            $videos_juego_no_encontrado['total_views'],
                            $videos_juego_no_encontrado['most_viewed_title'],
                            $videos_juego_no_encontrado['most_viewed_views'],
                            $videos_juego_no_encontrado['most_viewed_duration'],
                            $videos_juego_no_encontrado['most_viewed_created_at'],
                            $fecha,
                            $videos_juego_no_encontrado['game_id']
                        );
                        $stmt3->execute();
                        $stmt3->close();
                    } else {
                        $insertar_nuevo = "INSERT INTO topsofthetops (game_id, game_name, user_name, total_videos, total_views,
                    most_viewed_title, most_viewed_views, most_viewed_duration, most_viewed_created_at, fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt4 = $conn->prepare($insertar_nuevo);
                        $stmt4->bind_param(
                            "sssiisisss",
                            $videos_juego_no_encontrado['game_id'],
                            $videos_juego_no_encontrado['game_name'],
                            $videos_juego_no_encontrado['user_name'],
                            $videos_juego_no_encontrado['total_videos'],
                            $videos_juego_no_encontrado['total_views'],
                            $videos_juego_no_encontrado['most_viewed_title'],
                            $videos_juego_no_encontrado['most_viewed_views'],
                            $videos_juego_no_encontrado['most_viewed_duration'],
                            $videos_juego_no_encontrado['most_viewed_created_at'],
                            $fecha
                        );
                        $stmt4->execute();
                        $stmt4->close();
                    }
                }
            }
        } else {
            $fecha = date('Y-m-d H:i:s');
            foreach ($top3_juegos['data'] as $juego) {
                $videos_juego = \App\Http\topsofthetops\videos($token, $juego);
                $resultado_final[] = $videos_juego;
                $insertar = "INSERT INTO topsofthetops VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = $conn->prepare($insertar);
                $stmt2->bind_param(
                    "sssiisisss",
                    $videos_juego['game_id'],
                    $videos_juego['game_name'],
                    $videos_juego['user_name'],
                    $videos_juego['total_videos'],
                    $videos_juego['total_views'],
                    $videos_juego['most_viewed_title'],
                    $videos_juego['most_viewed_views'],
                    $videos_juego['most_viewed_duration'],
                    $videos_juego['most_viewed_created_at'],
                    $fecha
                );
                $stmt2->execute();
            }
            $stmt2->close();
        }

        $stmt->close();
        $conn->close();

        return response()->json($resultado_final);

    }
}
