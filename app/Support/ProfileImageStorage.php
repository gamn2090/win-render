<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Profile avatars use the "public" disk (storage/app/public/images).
 * Browsers request /storage/images/{file} via public/storage.
 * When public/storage is not the same folder as storage/app/public (common on XAMPP),
 * syncToWeb() mirrors files into public/storage/images.
 */
class ProfileImageStorage
{
    public const DEFAULT_FILENAME = 'user.jpg';

    public const RELATIVE_DIR = 'images';

    public static function url(?string $filename): string
    {
        $name = $filename ?: self::DEFAULT_FILENAME;
        self::syncToWeb($name);

        return asset('storage/' . self::RELATIVE_DIR . '/' . $name);
    }

    public static function store(UploadedFile $file): string
    {
        $filename = $file->hashName();
        $file->storeAs(self::RELATIVE_DIR, $filename, 'public');
        self::syncToWeb($filename);

        return $filename;
    }

    public static function deleteIfExists(?string $filename): void
    {
        if (! $filename || $filename === self::DEFAULT_FILENAME) {
            return;
        }

        $relative = self::RELATIVE_DIR . '/' . $filename;
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }

        $webPath = self::webPath($filename);
        if (! is_file($webPath)) {
            return;
        }

        $diskPath = self::diskPath($filename);
        if (! is_file($diskPath) || realpath($webPath) !== realpath($diskPath)) {
            @unlink($webPath);
        }
    }

    public static function syncToWeb(string $filename): void
    {
        if (self::webServesSameFilesAsDisk()) {
            return;
        }

        $source = self::diskPath($filename);
        if (! is_file($source)) {
            return;
        }

        $dest = self::webPath($filename);
        File::ensureDirectoryExists(dirname($dest));

        if (! is_file($dest) || filemtime($source) > filemtime($dest)) {
            File::copy($source, $dest);
        }
    }

    public static function webServesSameFilesAsDisk(): bool
    {
        $diskDir = realpath(Storage::disk('public')->path(self::RELATIVE_DIR));
        $webDir = realpath(public_path('storage/' . self::RELATIVE_DIR));

        return $diskDir && $webDir && $diskDir === $webDir;
    }

    private static function diskPath(string $filename): string
    {
        return Storage::disk('public')->path(self::RELATIVE_DIR . '/' . $filename);
    }

    private static function webPath(string $filename): string
    {
        return public_path('storage/' . self::RELATIVE_DIR . '/' . $filename);
    }
}
