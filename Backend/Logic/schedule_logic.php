<?php
// Backend/Logic/schedule_logic.php

function schedules_list(mysqli $conn, int $limit, int $offset): array {
    $sql = "SELECT sch.*, e.name AS employee_name, o.id AS order_code
            FROM schedules sch
            LEFT JOIN employees e ON sch.employee_id = e.id
            LEFT JOIN orders o ON sch.order_id = o.id
            ORDER BY sch.id DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function schedules_count(mysqli $conn): int {
    $res = $conn->query("SELECT COUNT(*) AS c FROM schedules");
    if (!$res) return 0;
    $row = $res->fetch_assoc();
    return (int)($row['c'] ?? 0);
}

function schedule_delete(mysqli $conn, int $id): bool {
    $stmt = $conn->prepare("DELETE FROM schedules WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
