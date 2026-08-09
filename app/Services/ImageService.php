<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Traite et stocke une image uploadée :
     * - Redimensionne si elle dépasse la taille max (en gardant les proportions)
     * - Compresse en JPEG qualité 85%
     * - Les PNG avec transparence et les SVG sont stockés tels quels
     *
     * @param  UploadedFile  $file      Le fichier uploadé
     * @param  string        $directory Le sous-dossier dans storage/app/public
     * @param  int           $maxWidth  Largeur max en pixels
     * @param  int           $maxHeight Hauteur max en pixels
     * @param  int           $quality   Qualité JPEG (1-100)
     * @return string                   Le chemin relatif stocké
     */
    public static function store(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 2000,
        int $maxHeight = 2000,
        int $quality = 85
    ): string {
        $extension = strtolower($file->getClientOriginalExtension());

        // SVG : stocker tel quel, pas de traitement image
        if ($extension === 'svg') {
            return $file->store($directory, 'public');
        }

        // Lire les dimensions de l'image originale
        $imageInfo = @getimagesize($file->getPathname());
        if (!$imageInfo) {
            // Pas une image valide, stocker tel quel
            return $file->store($directory, 'public');
        }

        [$origWidth, $origHeight, $type] = $imageInfo;

        // PNG avec transparence : stocker en PNG compressé
        if ($type === IMAGETYPE_PNG) {
            return self::processWithGd($file, $directory, $type, $origWidth, $origHeight, $maxWidth, $maxHeight, 'png', $quality);
        }

        // Tout le reste (JPEG, WebP, GIF, BMP) : convertir en JPEG
        return self::processWithGd($file, $directory, $type, $origWidth, $origHeight, $maxWidth, $maxHeight, 'jpg', $quality);
    }

    private static function processWithGd(
        UploadedFile $file,
        string $directory,
        int $type,
        int $origWidth,
        int $origHeight,
        int $maxWidth,
        int $maxHeight,
        string $outputFormat,
        int $quality
    ): string {
        // Créer la resource GD depuis le fichier source
        $source = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($file->getPathname()),
            IMAGETYPE_PNG  => @imagecreatefrompng($file->getPathname()),
            IMAGETYPE_WEBP => @imagecreatefromwebp($file->getPathname()),
            IMAGETYPE_GIF  => @imagecreatefromgif($file->getPathname()),
            IMAGETYPE_BMP  => @imagecreatefrombmp($file->getPathname()),
            default        => null,
        };

        if (!$source) {
            // Fallback : stocker tel quel
            return $file->store($directory, 'public');
        }

        // Calculer les nouvelles dimensions en gardant les proportions
        $newWidth  = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth  = (int) round($origWidth * $ratio);
            $newHeight = (int) round($origHeight * $ratio);
        }

        // Redimensionner
        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Préserver la transparence pour les PNG
        if ($outputFormat === 'png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefill($resized, 0, 0, $transparent);
        }

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Générer le nom de fichier
        $filename = Str::random(40) . '.' . $outputFormat;
        $path     = $directory . '/' . $filename;
        $fullPath = Storage::disk('public')->path($path);

        // S'assurer que le dossier existe
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Écrire le fichier
        if ($outputFormat === 'png') {
            // Compression PNG (0-9, 6 est un bon compromis)
            imagepng($resized, $fullPath, 6);
        } else {
            imagejpeg($resized, $fullPath, $quality);
        }

        // Libérer la mémoire
        imagedestroy($source);
        imagedestroy($resized);

        return $path;
    }
}
