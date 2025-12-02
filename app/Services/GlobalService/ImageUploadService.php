<?php

namespace App\Services\GlobalService;

use Exception;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
public function upload($file, $folder = 'uploads/'): string
{
        // Detect file extension
    $extension = strtolower($file->getClientOriginalExtension());

         // Generate unique filename
    $fileName = uniqid() . '.' . $extension;
    $filePath = $folder . $fileName;

    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
        // Handle images with Intervention (convert to WebP)
        $manager = new ImageManager(new Driver());
        $webpFileName = uniqid() . '.webp';
        $filePath = $folder . $webpFileName;

        $webpImage = $manager->read($file)->toWebp(80);
        $result = Storage::put($filePath, (string) $webpImage);

        return $result ? $filePath : '';
    }

        // Handle non-image files (store as-is)
    $result = Storage::putFileAs($folder, $file, $fileName);

    return $result ? $filePath : '';
}

    public function duplicateImage(string $originalPath, string $folder = 'uploads/'): ?string
    {
        if (!Storage::exists($originalPath)) {
            throw new Exception('Image not found');
        }

        $fileExtension = pathinfo($originalPath, PATHINFO_EXTENSION);
        $duplicateFileName = uniqid('duplicate_') . '.' . $fileExtension;
        $duplicateFilePath = $folder . $duplicateFileName;

        $success = Storage::copy($originalPath, $duplicateFilePath);

        return $success ? $duplicateFilePath : null;
    }

    public function delete(string $filePath): bool
    {
        if (Storage::exists($filePath)) {
            return Storage::delete($filePath);
        }

        return false;
    }

    public function updateImages(array $images = [], $folder = 'uploads/'): array
    {
        $newImages = [];
        $oldImages = Storage::files($folder);

        foreach ($images as $image) {
            if (!in_array($image, $oldImages)) {
                $newImages[] = $this->upload($image, $folder);
            } else {
                $newImages[] = $image;
            }
        }

        foreach ($oldImages as $oldImage) {
            if (!in_array($oldImage, $images)) {
                $this->delete($oldImage);
            }
        }

        return $newImages;
    }

public function updateFile($newFile, string $folder = 'uploads/', $model, $id, string $keyName): ?string
{
    $globalRepo = new GlobalService($model);
    $getOldFile = $globalRepo->show($id);

    // Get existing file path from DB
    $oldFile = $getOldFile->{$keyName} ?? null;

    if ($newFile) {
        // Determine extension
        $extension = strtolower($newFile->getClientOriginalExtension());

        // Generate unique name
        $fileName = uniqid() . '.' . $extension;
        $filePath = $folder . $fileName;

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            // Use GD upload for images
            $uploaded = $this->upload($newFile, $folder);
        } elseif ($extension === 'pdf') {
            // Store PDF as-is
            $uploaded = Storage::putFileAs($folder, $newFile, $fileName);
            $uploaded = $uploaded ? $filePath : null;
        } else {
            throw new \Exception("Unsupported file type: {$extension}");
        }

        // Delete old file if it exists
        if ($oldFile && Storage::exists($oldFile)) {
            $this->delete($oldFile);
        }

        return $uploaded;
    }

    // If no new file uploaded, keep old one
    return $oldFile;
}



    // TODO: Add a method to add a watermark to an image
}
