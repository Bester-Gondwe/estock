<?php
class FileUpload {
    private $uploadDir = 'uploads/';

    public function uploadImage($file) {
        $targetFile = $this->uploadDir . time() . '_' . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        }
        return false;
    }

    public function uploadMultipleImages($files) {
        $uploadedFiles = [];
        
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $file = [
                'name' => $files['name'][$key],
                'tmp_name' => $tmp_name,
            ];
            
            $uploadedFile = $this->uploadImage($file);
            if ($uploadedFile) {
                $uploadedFiles[] = $uploadedFile;
            }
        }
        
        return $uploadedFiles;
    }
}
?>
