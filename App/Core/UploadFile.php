<?php

namespace App\Core;

use App\Core\Exception\FileException;

class UploadFile
{
  /**
   * Upload a file
   * @return string|null return path of file or null if cant upload
   */
  public static function upload(): string|null
  {

    if (!isset($_FILES['file'])) {
      throw new FileException("aucun fichier à uploader");
    }
    $image_error = $_FILES['file']['error'];


    if ($image_error === 0) {
      $fileName =  $_FILES['file']['name'];

      $file_tmp_name = $_FILES['file']['tmp_name'];
      $fileMimeType = mime_content_type($file_tmp_name);
      $fileSize = $_FILES['file']['size'];
      $extension = explode('.', $fileName);
      $extension = end($extension);
      $extension = strtolower($extension);
      $fileName = rtrim($fileName, ". .$extension");

      // save image
      if ($fileSize > MAX_FILE_SIZE) {
        throw new FileException("Le fichier doit faire moins de " . (round(MAX_FILE_SIZE / (1024 * 1024))) . " mb");
      } elseif (
        !in_array($extension, ALLOWED_EXTENSIONS_FILE)
        && !in_array($fileMimeType, ALLOWED_EXTENSIONS_FILE)
      ) {
        throw new FileException("Le format n'est pas valide");
      }

      // random file name
      $fileName = bin2hex(random_bytes(6)) . '.' . $extension;
      $destination = "uploads/" . $fileName;
      if (!file_exists($destination)) {
        move_uploaded_file($file_tmp_name, $destination);

        if (!file_exists($destination)) {
          throw new FileException("Le fichier n'a pas pu être upload vérifié les droits d'écriture");
        }

        return $fileName;
      } else {
        throw new FileException("Un fichier avec ce nom existe déjà");
      }
    } else {
      if ($_FILES["file"]["error"] !== UPLOAD_ERR_OK) {
        switch ($_FILES["file"]["error"]) {
          case UPLOAD_ERR_PARTIAL:
            throw new FileException("image partiellement chargé");
          case UPLOAD_ERR_NO_FILE:
            throw new FileException("Aucun fichier à upload");
          case UPLOAD_ERR_EXTENSION:
            throw new FileException("File upload stopped by a PHP extension");
          case UPLOAD_ERR_FORM_SIZE:
            throw new FileException("File exceeds MAX_FILE_SIZE in the HTML form");
          case UPLOAD_ERR_INI_SIZE:
            throw new FileException("Le fichier dois faire moins de " . (MAX_FILE_SIZE / (1024 * 1024)) . "mb");
          case UPLOAD_ERR_NO_TMP_DIR:
            throw new FileException("Temporary folder not found");
          case UPLOAD_ERR_CANT_WRITE:
            throw new FileException("Failed to write file");
          default:
            throw new FileException('Unknown upload error');
        }
      }
    }

    return null;
  }
  /**
   * Remove a file
   * @param $filename path of file
   */
  public  static function  remove(string $fileName)
  {
    try {

      if (file_exists("uploads/" . $fileName)) {
        unlink("uploads/" . $fileName);
      }
    } catch (FileException $e) {
      throw new FileException("Impossible de supprimer l'image : " . $e->getMessage());
    }
  }
}
