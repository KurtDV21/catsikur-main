<?php

namespace App\Models;

use mysqli;

class PostFilterModel {

    private $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function filterPosts($color = null, $gender = null) {
        $sql = "SELECT id, cat_name, age, location, gender, color, picture FROM post WHERE 1=1";
        $params = [];
        $types = "";

        if ($color) {
            $sql .= " AND color = ?";
            $params[] = $color;
            $types .= "s";
        }

        if ($gender) {
            $sql .= " AND gender = ?";
            $params[] = $gender;
            $types .= "s";
        }

        $stmt = $this->mysqli->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
