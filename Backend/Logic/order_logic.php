<?php
// Backend/Logic/order_logic.php

function orders_list(mysqli $conn, int $limit, int $offset): array {
    $sql = "SELECT o.*, c.name AS customer_name, s.name AS service_name
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN services s ON o.service_id = s.id
            ORDER BY o.id DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function orders_count(mysqli $conn): int {
    $res = $conn->query("SELECT COUNT(*) AS c FROM orders");
    if (!$res) return 0;
    $row = $res->fetch_assoc();
    return (int)($row['c'] ?? 0);
}

function order_get(mysqli $conn, int $id): ?array {
    $sql = "SELECT o.*, c.name AS customer_name, s.name AS service_name
            FROM orders o
            LEFT JOIN customers c ON o.customer_id = c.id
            LEFT JOIN services s ON o.service_id = s.id
            WHERE o.id=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

function order_update(mysqli $conn, int $id, array $data): bool {
    $status = $data['status'] ?? '';
    $notes = $data['notes'] ?? '';
    $total_cost = (float)($data['total_cost'] ?? 0);
    $stmt = $conn->prepare("UPDATE orders SET status=?, notes=?, total_cost=? WHERE id=?");
    $stmt->bind_param("ssdi", $status, $notes, $total_cost, $id);
    return $stmt->execute();
}

function order_delete(mysqli $conn, int $id): bool {
    // hapus schedules yang nyangkut
    $stmt1 = $conn->prepare("DELETE FROM schedules WHERE order_id=?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    $stmt2 = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt2->bind_param("i", $id);
    return $stmt2->execute();
}


function order_update_full(mysqli $conn, int $id, array $data): bool {
    $customer_id = (int)($data['customer_id'] ?? 0);
    $order_code = $data['order_code'] ?? null;
    $order_date = $data['order_date'] ?? null;
    $total_cost = (float)($data['total_cost'] ?? 0);
    $payment_proof = $data['payment_proof'] ?? null;
    $status = $data['status'] ?? null;
    $payment_confirmed_at = $data['payment_confirmed_at'] ?? null;

    $sql = "UPDATE orders SET
            customer_id=?, order_code=?, order_date=?, total_cost=?, payment_proof=?, status=?, payment_confirmed_at=?
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdsssi", $customer_id, $order_code, $order_date, $total_cost, $payment_proof, $status, $payment_confirmed_at, $id);
    return $stmt->execute();
}
