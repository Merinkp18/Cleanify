<?php
// Backend/Logic/employee_logic.php
// NOTE: jangan require auth_admin.php atau db.php di sini.
// File ini isinya fungsi query reusable, dipanggil dari page (Frontend/Admin/*).

function employees_count(mysqli $conn): int {
    $res = $conn->query("SELECT COUNT(*) AS c FROM employees");
    if (!$res) return 0;
    $row = $res->fetch_assoc();
    return (int)($row['c'] ?? 0);
}

function employees_list(mysqli $conn, int $limit, int $offset): array {
    $stmt = $conn->prepare("SELECT * FROM employees ORDER BY id DESC LIMIT ? OFFSET ?");
    if (!$stmt) return [];
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function employee_get(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ? LIMIT 1");
    if (!$stmt) return null;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ?: null;
}

function employee_create(mysqli $conn, array $data): bool {
    $name = trim((string)($data['name'] ?? ''));
    $position = trim((string)($data['position'] ?? ''));
    $status = trim((string)($data['status'] ?? 'active'));
    $rating = (float)($data['rating'] ?? 0);

    $phone = $data['phone'] ?? null;
    $email = $data['email'] ?? null;
    $address = $data['address'] ?? null;

    $emergency_name  = $data['emergency_contact_name'] ?? null;
    $emergency_phone = $data['emergency_contact_phone'] ?? null;

    $skills = $data['skills'] ?? null;
    $certifications = $data['certifications'] ?? null;

    $jobs = (int)($data['total_jobs_completed'] ?? 0);
    $shift_date = $data['shift_date'] ?? null;
    $photo = $data['photo'] ?? null;

    $sql = "INSERT INTO employees
            (name, position, status, rating, phone, email, address,
             emergency_contact_name, emergency_contact_phone, skills,
             certifications, total_jobs_completed, shift_date, photo)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    // rating = double (d), jobs = int (i)
    $stmt->bind_param(
        "sssdsssssssiss",
        $name, $position, $status, $rating,
        $phone, $email, $address,
        $emergency_name, $emergency_phone,
        $skills, $certifications,
        $jobs, $shift_date, $photo
    );

    return $stmt->execute();
}

function employee_update(mysqli $conn, int $id, array $data): bool {
    $name = trim((string)($data['name'] ?? ''));
    $position = trim((string)($data['position'] ?? ''));
    $status = trim((string)($data['status'] ?? 'active'));
    $rating = (float)($data['rating'] ?? 0);

    $phone = $data['phone'] ?? null;
    $email = $data['email'] ?? null;
    $address = $data['address'] ?? null;

    $emergency_name  = $data['emergency_contact_name'] ?? null;
    $emergency_phone = $data['emergency_contact_phone'] ?? null;

    $skills = $data['skills'] ?? null;
    $certifications = $data['certifications'] ?? null;

    $jobs = (int)($data['total_jobs_completed'] ?? 0);
    $shift_date = $data['shift_date'] ?? null;
    $photo = $data['photo'] ?? null;

    // Kalau photo kosong, jangan update kolom photo
    if ($photo === null || $photo === '') {
        $sql = "UPDATE employees SET
                name=?, position=?, status=?, rating=?, phone=?, email=?, address=?,
                emergency_contact_name=?, emergency_contact_phone=?, skills=?,
                certifications=?, total_jobs_completed=?, shift_date=?
                WHERE id=?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;

        // rating=d, jobs=i, id=i
        $stmt->bind_param(
            "sssdsssssssisi",
            $name, $position, $status, $rating,
            $phone, $email, $address,
            $emergency_name, $emergency_phone,
            $skills, $certifications,
            $jobs, $shift_date, $id
        );
        return $stmt->execute();
    }

    $sql = "UPDATE employees SET
            name=?, position=?, status=?, rating=?, phone=?, email=?, address=?,
            emergency_contact_name=?, emergency_contact_phone=?, skills=?,
            certifications=?, total_jobs_completed=?, shift_date=?, photo=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param(
        "sssdsssssssissi",
        $name, $position, $status, $rating,
        $phone, $email, $address,
        $emergency_name, $emergency_phone,
        $skills, $certifications,
        $jobs, $shift_date, $photo, $id
    );

    return $stmt->execute();
}

function employee_delete(mysqli $conn, int $id): bool {
    // kalau FK belum CASCADE, hapus schedules dulu
    $stmt1 = $conn->prepare("DELETE FROM schedules WHERE employee_id = ?");
    if ($stmt1) {
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
    }

    $stmt2 = $conn->prepare("DELETE FROM employees WHERE id = ?");
    if (!$stmt2) return false;
    $stmt2->bind_param("i", $id);
    return $stmt2->execute();
}
