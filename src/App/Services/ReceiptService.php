<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;
use Framework\DB;
use Framework\Exceptions\ValidationException;

class ReceiptService
{
    public function __construct(private DB $db)
    {
    }

    public function validateFile(?array $file)
    {

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                "receipt" => ["No file provided"]
            ]);
        }

        if ($file['size'] / 1024 / 1024 > $_ENV['UPLOAD_MAX_MB']) {
            throw new ValidationException([
                'receipt' => ['File is too large']
            ]);
        }

        $clientMimeType = $file['type'];
        $allowedMimeType = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!in_array($clientMimeType, $allowedMimeType)) {
            throw new ValidationException([
                'receipt' => ['Invalid file type']
            ]);
        }
    }

    public function upload(array $file, int $transaction)
    {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new ValidationException(['receipt' => ['Failed to upload file']]);
        }

        $this->db->query(
            "INSERT INTO receipts
            (original_filename, storage_filename, media_type, transaction_id)
            VALUES(:original, :new, :media, :tID)",
            [
                'original' => $file['name'],
                'new' => $newFilename,
                'media' => $file['type'],
                'tID' => $transaction
            ]
        );
    }

    public function getReceipt(string $receipt)
    {
        $receipt = $this->db->query(
            "SELECT * FROM receipts WHERE id = :id",
            [
                "id" => $receipt
            ]
        )->find();
        return $receipt;
    }

    public function read(array $receipt)
    {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];
        if (!file_exists($filePath)) {
            redirectTo('/');
        }

        header("Content-Disposition: inline;filename: {$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}");

        readfile($filePath);
    }

    public function delete(array $receipt)
    {
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        if (!file_exists($filePath)) {
            redirectTo('/');
        }

        unlink($filePath);

        $this->db->query(
            "DELETE FROM receipts WHERE id = :id",
            ['id' => $receipt['id']]
        );

        redirectTo('/');
    }
}
