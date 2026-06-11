<?php

require_once __DIR__ . '/multipart_put.php';

function image_upload_init_request(): void
{
  multipart_populate_post_files_for_put_patch();
}

/**
 * @return array{0: ?string, 1: ?string} image_file, image_path
 */
function image_upload_from_request(
  string $fieldName = 'image_file',
  ?string $fallbackFile = null,
  ?string $fallbackPath = null
): array {
  $uploadDir = dirname(__DIR__) . '/wwwroot/images/';

  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $saved = image_upload_save_file_field($fieldName, $uploadDir);
  if ($saved !== null) {
    return $saved;
  }

  $saved = image_upload_save_base64_field($fieldName, $uploadDir);
  if ($saved !== null) {
    return $saved;
  }

  $saved = image_upload_save_base64_field('image_base64', $uploadDir);
  if ($saved !== null) {
    return $saved;
  }

  return [$fallbackFile, $fallbackPath];
}

/**
 * @return array{0: string, 1: string}|null
 */
function image_upload_save_file_field(string $fieldName, string $uploadDir): ?array
{
  if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
    return null;
  }

  $file = $_FILES[$fieldName];

  if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    return null;
  }

  $tmpName = $file['tmp_name'] ?? '';
  $size = (int) ($file['size'] ?? 0);

  if ($tmpName === '' || !is_readable($tmpName) || $size <= 0) {
    return null;
  }

  $originalName = $file['name'] ?? 'image.jpg';
  $extension = pathinfo($originalName, PATHINFO_EXTENSION);
  if ($extension === '' || $extension === false) {
    $extension = image_upload_extension_from_mime($file['type'] ?? '') ?? 'jpg';
  }

  $fileName = uniqid('img_', true) . '.' . $extension;
  $targetPath = $uploadDir . $fileName;

  if (!image_upload_move_to_target($tmpName, $targetPath)) {
    http_response_code(500);
    die('Error uploading file');
  }

  return [$fileName, 'wwwroot/images/' . $fileName];
}

/**
 * @return array{0: string, 1: string}|null
 */
function image_upload_save_base64_field(string $fieldName, string $uploadDir): ?array
{
  if (!isset($_POST[$fieldName]) || !is_string($_POST[$fieldName])) {
    return null;
  }

  $raw = trim($_POST[$fieldName]);
  if ($raw === '') {
    return null;
  }

  $extension = 'jpg';
  $binary = null;

  if (preg_match('/^data:image\/(\w+);base64,(.+)$/is', $raw, $matches)) {
    $extension = strtolower($matches[1]);
    $extension = $extension === 'jpeg' ? 'jpg' : $extension;
    $binary = base64_decode($matches[2], true);
  } elseif (strlen($raw) > 100 && preg_match('/^[A-Za-z0-9+\/=\r\n]+$/', $raw)) {
    $binary = base64_decode(preg_replace('/\s+/', '', $raw), true);
  }

  if ($binary === false || $binary === null || $binary === '') {
    return null;
  }

  $fileName = uniqid('img_', true) . '.' . $extension;
  $targetPath = $uploadDir . $fileName;

  if (file_put_contents($targetPath, $binary) === false) {
    http_response_code(500);
    die('Error uploading file');
  }

  return [$fileName, 'wwwroot/images/' . $fileName];
}

function image_upload_move_to_target(string $tmpName, string $targetPath): bool
{
  if (is_uploaded_file($tmpName)) {
    return move_uploaded_file($tmpName, $targetPath);
  }

  if (@rename($tmpName, $targetPath)) {
    return true;
  }

  if (@copy($tmpName, $targetPath)) {
    @unlink($tmpName);
    return true;
  }

  return false;
}

function image_upload_extension_from_mime(string $mime): ?string
{
  $map = [
    'image/jpeg' => 'jpg',
    'image/jpg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
  ];

  return $map[strtolower(trim($mime))] ?? null;
}
