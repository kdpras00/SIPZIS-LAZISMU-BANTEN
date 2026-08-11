<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    
    public function uploadImage(?UploadedFile $file, string $directory, ?string $oldPath = null): ?string
    {
        if (!$file) {
            return $oldPath; 
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
        
        if (!in_array(strtolower($file->getClientMimeType()), $allowedMimeTypes) ||
            !in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new \InvalidArgumentException('Tipe file tidak diizinkan. Harap unggah gambar.');
        }

        if ($oldPath && !filter_var($oldPath, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        
        return $file->store($directory, 'public');
    }

    
    public function deleteImage(?string $path): bool
    {
        if ($path && !filter_var($path, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
        }

        return false;
    }
}
