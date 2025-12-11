<?php
// Backend/Logic/service_logic.php

function services_list(mysqli $conn): array {
    $res = $conn->query("SELECT * FROM services ORDER BY id DESC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function service_get(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare("SELECT * FROM services WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

/**
 * Catatan:
 * Kolom yang dipakai di form update.php kamu:
 * - name
 * - category
 * - price
 * - duration_minutes
 * - short_description
 * - full_description
 * - features
 * - not_included
 * - status
 *
 * Jadi create/update harus pakai kolom-kolom itu, BUKAN "description".
 */

function service_create(mysqli $conn, array $data): bool {
    $name  = trim($data['name'] ?? '');
    $category = trim($data['category'] ?? '');
    $price = (float)($data['price'] ?? 0);
    $duration = (int)($data['duration_minutes'] ?? 0);
    $short = trim($data['short_description'] ?? '');
    $full  = trim($data['full_description'] ?? '');
    $features = trim($data['features'] ?? '');
    $not_included = trim($data['not_included'] ?? '');
    $status = trim($data['status'] ?? 'active');

    $sql = "INSERT INTO services
            (name, category, price, duration_minutes, short_description, full_description, features, not_included, status)
            VALUES (?,?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssss", $name, $category, $price, $duration, $short, $full, $features, $not_included, $status);
    return $stmt->execute();
}

function service_update(mysqli $conn, int $id, array $data): bool {
    $name  = trim($data['name'] ?? '');
    $category = trim($data['category'] ?? '');
    $price = (float)($data['price'] ?? 0);
    $duration = (int)($data['duration_minutes'] ?? 0);
    $short = trim($data['short_description'] ?? '');
    $full  = trim($data['full_description'] ?? '');
    $features = trim($data['features'] ?? '');
    $not_included = trim($data['not_included'] ?? '');
    $status = trim($data['status'] ?? 'active');

    $sql = "UPDATE services SET
              name=?,
              category=?,
              price=?,
              duration_minutes=?,
              short_description=?,
              full_description=?,
              features=?,
              not_included=?,
              status=?
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisssssi", $name, $category, $price, $duration, $short, $full, $features, $not_included, $status, $id);
    return $stmt->execute();
}

function service_delete(mysqli $conn, int $id): bool {
    $stmt = $conn->prepare("DELETE FROM services WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
