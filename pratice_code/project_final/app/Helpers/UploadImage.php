<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadImage
{
  /**
   * Upload Base64 image.
   */

  public static function upload(
    UploadedFile $file,
    string $folder = 'images'
  ): string {
    // Generate unique filename
    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

    // Create folder if it doesn't exist
    $destination = public_path($folder);

    if (! is_dir($destination)) {
      mkdir($destination, 0755, true);
    }

    // Move image to public/images
    $file->move($destination, $filename);

    // Return only filename
    return $filename;
  }

  public static function uploadBase64(
    string $base64Image,
    string $folder = 'images'
  ): string {

    if (! preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
      throw new \Exception('Invalid base64 image.');
    }

    $extension = strtolower($matches[1]);

    if (! in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
      throw new \Exception('Unsupported image type.');
    }

    $image = substr($base64Image, strpos($base64Image, ',') + 1);

    $image = base64_decode($image);

    if ($image === false) {
      throw new \Exception('Base64 decode failed.');
    }

    $filename = Str::uuid() . '.' . $extension;

    $path = $folder . '/' . $filename;

    Storage::disk('public')->put($path, $image);

    return $path;
  }

  public static function delete(?string $path): void
  {
    if ($path && Storage::disk('public')->exists($path)) {
      Storage::disk('public')->delete($path);
    }
  }
}
