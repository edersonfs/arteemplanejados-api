<?php

/**
 * PHP does not populate $_POST / $_FILES for multipart/form-data on PUT or PATCH.
 * Call once before reading $_POST when handling multipart PUT/PATCH uploads.
 */
function multipart_populate_post_files_for_put_patch(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (!in_array($method, ['PUT', 'PATCH'], true)) {
        return;
    }
    $contentType = $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? '');
    if (stripos($contentType, 'multipart/form-data') === false) {
        return;
    }
    if (!preg_match('/boundary\s*=\s*(?:([^;\s]+)|"([^"]+)")/i', $contentType, $bm)) {
        return;
    }
    $boundary = $bm[1] !== '' ? $bm[1] : ($bm[2] ?? '');
    if ($boundary === '') {
        return;
    }
    $raw = file_get_contents('php://input');
    if ($raw === '' || $raw === false) {
        return;
    }

    [$fields, $files] = multipart_parse_raw_body($raw, $boundary);

    foreach ($fields as $key => $value) {
        $_POST[$key] = $value;
    }
    foreach ($files as $key => $fileStruct) {
        $_FILES[$key] = $fileStruct;
    }
}

/**
 * @return array{0: array<string,string>, 1: array<string,array>}
 */
function multipart_parse_raw_body(string $raw, string $boundary): array {
    $fields = [];
    $files = [];
    $marker = '--' . $boundary;
    $parts = explode($marker, $raw);
    foreach ($parts as $part) {
        $part = ltrim($part, "\r\n");
        if ($part === '' || $part === '--') {
            continue;
        }
        if (!preg_match(
            '/Content-Disposition:\s*form-data;\s*name="([^"]+)"(?:;\s*filename="([^"]*)")?/is',
            $part,
            $mh
        )) {
            continue;
        }
        $name = $mh[1];
        $filename = isset($mh[2]) ? $mh[2] : null;

        $sep = strpos($part, "\r\n\r\n");
        if ($sep === false) {
            continue;
        }
        $headers = substr($part, 0, $sep);
        $body = substr($part, $sep + 4);
        if (substr($body, -2) === "\r\n") {
            $body = substr($body, 0, -2);
        }

        $hasFilename = $filename !== null && $filename !== '';
        if ($hasFilename) {
            if ($body === '') {
                continue;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'cb_');
            if ($tmp === false) {
                continue;
            }
            file_put_contents($tmp, $body);
            $mime = '';
            if (preg_match('/Content-Type:\s*([^\r\n]+)/i', $headers, $ct)) {
                $mime = trim($ct[1]);
            }
            $files[$name] = [
                'name' => $filename,
                'type' => $mime,
                'tmp_name' => $tmp,
                'error' => UPLOAD_ERR_OK,
                'size' => strlen($body),
            ];
        } else {
            $fields[$name] = $body;
        }
    }

    return [$fields, $files];
}
