<?php
require_once "../models/ProductImage.php";

class FileUpload
{
    public static $uploadDir = '../uploads/';

    public function uploadImages($uploadedFiles, $productId, $primaryImg)
    {
        $productImage = new ProductImage();
        $responses = [];
        $primaryImageId = null;

        if (!isset($uploadedFiles['name']) || !is_array($uploadedFiles['name'])) {
            return [];
        }

        foreach ($uploadedFiles['name'] as $index => $fileName) {
            $tmpPath = $uploadedFiles['tmp_name'][$index];
            $fileError = $uploadedFiles['error'][$index];

            if ($fileError !== UPLOAD_ERR_OK) {
                $responses[] = "Error uploading: $fileName";
                continue;
            }

            $uniqueFileName = uniqid() . '-' . basename($fileName);
            $destination = self::$uploadDir . $uniqueFileName;

            if (move_uploaded_file($tmpPath, $destination)) {
                $newImageId = $productImage->addProductImage($productId, $uniqueFileName, 0);

                if ($primaryImg === "new-$index") {
                    $primaryImageId = $newImageId;
                }

                $responses[] = "Uploaded: $fileName";
            } else {
                $responses[] = "Failed to upload: $fileName";
            }
        }

        if ($primaryImageId) {
            $productImage->changePrimaryImg($productId, $primaryImageId);
        }

        return $responses;
    }
}
