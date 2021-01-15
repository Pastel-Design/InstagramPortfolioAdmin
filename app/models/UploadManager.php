<?php


namespace app\models;


use RuntimeException;
use Nette\Http\FileUpload as FileUpload;

class UploadManager
{
    /**
     * @param $values
     *
     * @return bool|array
     */
    public static function UploadMultiple($values)
    {
        $files = array();
        $filenames = array();
        try {
            foreach ($values as $file) {
                /**
                 * @var FileUpload $file
                 */
                switch ($file->getImageFileExtension()) {
                    case"jpeg":
                        $ext = "jpg";
                        break;
                    default:
                        $ext = $file->getImageFileExtension();
                        break;
                }
                array_push($files, ($filename = hash("sha256", $file->getTemporaryFile())) . "." . $ext);
                array_push($filenames, $file->getSanitizedName());
                $fileNameWDir = sprintf(
                    'images/fullView/%s.%s',
                    $filename,
                    $ext
                );

                if (!move_uploaded_file(
                    $file->getTemporaryFile(),
                    $fileNameWDir
                )) {
                    throw new RuntimeException();
                }
                ImageFileManager::defaultImage($fileNameWDir);
                ImageFileManager::makeThumbnail($fileNameWDir);
            }
        } catch (RuntimeException $exception) {
            if (!empty($files)) {
                foreach ($files as $filename) {
                    unlink("images/fullView/" . $filename);
                    unlink("images/thumbnail/" . $filename);
                }
            }
            return false;
        }
        return ["filenames" => $files, "file-names" => $filenames];
    }

    public static function UploadSingle($file)
    {
        $fileName = "";
        try {
            /**
             * @var FileUpload $file
             */
            switch ($file->getImageFileExtension()) {
                case"jpeg":
                    $ext = "jpg";
                    break;
                default:
                    $ext = $file->getImageFileExtension();
                    break;
            }
            $fileName = ($filename = hash("sha256", $file->getTemporaryFile())) . "." . $ext;
            $fileNameWDir = sprintf(
                'images/fullView/%s.%s',
                $filename,
                $ext
            );

            if (!move_uploaded_file(
                $file->getTemporaryFile(),
                $fileNameWDir
            )) {
                throw new RuntimeException();
            }
            ImageFileManager::defaultImage($fileNameWDir);
            ImageFileManager::makeThumbnail($fileNameWDir);
            return $fileName;
        } catch (RuntimeException $exception) {
            @unlink("images/fullView/" . $fileName);
            @unlink("images/thumbnail/" . $fileName);
            return false;
        }
    }

    /**
     * function for uploading from folder, do not use!!!
     *
     * @param $file
     * @param $album
     *
     * @internal
     *
     * @return string
     */
    private function uploadFromFolder($file, $album)
    {
        $ext = strtolower(array_reverse(explode(".", $file))[0]);
        $fileName = ($filename = hash("sha256", $file)) . "." . $ext;
        $fileNameWDir = sprintf(
            'images/fullView/%s.%s',
            $filename,
            $ext
        );
        $targetDir = "D:/wamp/www/InstagramPortfolioAdmin/www/images/Gallery/" . $album . "/" . $file;
        copy($targetDir, $fileNameWDir);
        ImageFileManager::defaultImage($fileNameWDir);
        ImageFileManager::makeThumbnail($fileNameWDir);
        return $fileName;
    }
}