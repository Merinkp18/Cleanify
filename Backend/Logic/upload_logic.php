<?php
// Backend/Logic/upload_logic.php
// Upload image aman: whitelist ext + mime + size limit.
// Return: filename saja (untuk disimpan ke DB)

function upload_image(array $file, string $targetDirAbs, string $targetDirRel = '', int $maxBytes = 2097152): ?string {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) return null;
    if (($file['size'] ?? 0) <= 0 || ($file['size'] ?? 0) > $maxBytes) return null;

    $original = $file['name'] ?? '';
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $allowedExt = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowedExt, true)) return null;

    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMime = ['image/jpeg','image/png','image/webp'];
        if (!in_array($mime, $allowedMime, true)) return null;
    }

    if (!is_dir($targetDirAbs)) {
        mkdir($targetDirAbs, 0755, true);
    }

    $filename = 'emp_' . bin2hex(random_bytes(12)) . '.' . $ext;
    $destAbs = rtrim($targetDirAbs, '/\\') . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destAbs)) return null;

    // return filename aja biar DB bersih
    return $filename;
}

