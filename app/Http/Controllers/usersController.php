<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use mysqli;

include base_path('app/Http/topsofthetops/token.php');

class usersController extends Controller
{
    public function users(Request $request)
    {
        $id_usuario = $request->query('id');

        $host_name = env('DB_HOST');
        $database = env('DB_DATABASE');
        $user_name = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        $client_id = env('CLIENT_ID');
        $conn = new mysqli($host_name, $user_name, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
            return "bdd muere";
        }

        $access_token = \App\Http\topsofthetops\token();

        $test_usuario = "SELECT * FROM users_twitch WHERE id = ?";
        $stmt = $conn->prepare($test_usuario);
        $stmt->bind_param("s", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows <= 0) {
            $nuevo_usuario = "INSERT INTO users_twitch (id, login, display_name, type,
    broadcaster_type, desciption, profile_image_url, offline_image_url, view_count, created_at)
	VALUES (?,?,?,?,?,?,?,?,?,?);";
            $stmt2 = $conn->prepare($nuevo_usuario);

            $client_id = env('CLIENT_ID');
            $ch2 = curl_init();
            $url = "https://api.twitch.tv/helix/users?id=" . urlencode($id_usuario);
            curl_setopt($ch2, CURLOPT_URL, $url);
            curl_setopt(
                $ch2,
                CURLOPT_HTTPHEADER,
                array(
                    'Authorization: ' . 'Bearer ' . $access_token,
                    'Client-Id: ' . $client_id
                )
            );
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            $response2 = curl_exec($ch2);
            if (curl_errno($ch2)) {
                return 'Error: ' . curl_error($ch2);
            }
            $response2_decoded = json_decode($response2, true);
            curl_close($ch2);
            $datos_usuario = $response2_decoded['data'][0];

            $stmt2->bind_param(
                "ssssssssis",
                $datos_usuario['id'],
                $datos_usuario['login'],
                $datos_usuario['display_name'],
                $datos_usuario['type'],
                $datos_usuario['broadcaster_type'],
                $datos_usuario['description'],
                $datos_usuario['profile_image_url'],
                $datos_usuario['offline_image_url'],
                $datos_usuario['view_count'],
                $datos_usuario['created_at']
            );
            $stmt2->execute();
            $stmt2->close();
            return response()->json($datos_usuario);
        } else {
            return response()->json($result->fetch_assoc());
        }
        $conn . close();

    }

}
