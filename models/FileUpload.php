<?php
require_once "../models/ProductImage.php";

class FileUpload
{
    public static $uploadDir = '../uploads/';

    public function uploadImages($uploadedFiles, $productId, $primaryImg)
    {
        $productImage = new ProductImage();
        $responses = [];

        $primaryImage = $primaryImg;

        foreach ($uploadedFiles['name'] as $index => $fileName) {
            // Get file details
            $fileTmpPath = $uploadedFiles['tmp_name'][$index];
            $fileError = $uploadedFiles['error'][$index];

            // Check for errors
            if ($fileError !== UPLOAD_ERR_OK) {
                echo "Error uploading file: $fileName\n";
                continue;
            }

            // Generate a unique name to avoid overwriting files
            $uniqueFileName = uniqid() . '-' . basename($fileName);

            if (move_uploaded_file($fileTmpPath, self::$uploadDir . $uniqueFileName)) {
                $newImageId = $productImage->addProductImage($productId, $uniqueFileName, 0);
                if ($newImageId) {
                    // Update primary image if needed
                    if ($primaryImg === "new-$index") {
                        $primaryImage = $newImageId;
                    }
                }

                $response[] = "Uploaded: $fileName";
            } else {
                $response[] = "Failed to upload: $fileName";
            }
        }

        if ($primaryImage) {
            $productImage->changePrimaryImg($productId, $primaryImage);
        }

        return $responses;
    }
}
