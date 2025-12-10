<?php
// Backend/Logic/customer_logic.php

function customers_count(mysqli $conn): int {
    $res = $conn->query("SELECT COUNT(*) AS c FROM customers");
    if (!$res) return 0;
    $row = $res->fetch_assoc();
    return (int)($row['c'] ?? 0);
}

function customers_list(mysqli $conn, int $limit, int $offset): array {
    $stmt = $conn->prepare("SELECT * FROM customers ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function customer_get(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

function customer_create(mysqli $conn, array $data): bool {
    $name = $data['name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? null;
    $address = $data['address'] ?? '';
    $stmt = $conn->prepare("INSERT INTO customers (name, phone, email, address) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $address);
    return $stmt->execute();
}

function customer_update(mysqli $conn, int $id, array $data): bool {
    $name = $data['name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? null;
    $address = $data['address'] ?? '';
    $stmt = $conn->prepare("UPDATE customers SET name=?, phone=?, email=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $phone, $email, $address, $id);
    return $stmt->execute();
}

function customer_delete(mysqli $conn, int $id): bool {
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
