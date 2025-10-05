<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class EncryptionService
{
    /**
     * Encrypts the content of a file and stores it securely.
     *
     * @param string $path The temporary path of the uploaded file on the storage disk.
     * @param string $disk The disk the file is stored on (e.g., 'private').
     * @param string $storageFolder The final folder name for the encrypted file.
     * @return string The new file path where the ENCRYPTED content is stored.
     */
    public function encryptAndStoreFile(string $path, string $disk = 'private', string $storageFolder = 'aadhaar_encrypted'): string
    {
        // 1. Read the original, unencrypted content
        $content = Storage::disk($disk)->get($path);

        if (!$content) {
            throw new \Exception("Could not read file content from path: {$path}");
        }
        
        // 2. Encrypt the content
        $encryptedContent = Crypt::encryptString($content);
        
        // 3. Define the new file name (keeping original extension)
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $fileName = uniqid('enc_') . '.' . $extension;
        $newPath = $storageFolder . '/' . $fileName;

        // 4. Store the encrypted content
        Storage::disk($disk)->put($newPath, $encryptedContent);
        
        // 5. Delete the original, unencrypted file
        Storage::disk($disk)->delete($path);
        
        return $newPath;
    }

    /**
     * Retrieves and decrypts the content of a secure file.
     *
     * @param string $path The path of the encrypted file on the storage disk.
     * @param string $disk The disk the file is stored on (e.g., 'private').
     * @return string The decrypted content (file stream/data).
     */
    public function decryptFileContent(string $path, string $disk = 'private'): string
    {
        $encryptedContent = Storage::disk($disk)->get($path);

        if (!$encryptedContent) {
            throw new \Exception("Encrypted file not found or empty at path: {$path}");
        }

        return Crypt::decryptString($encryptedContent);
    }
}
