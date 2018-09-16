<?php

namespace input;

function cleanFlatArray($data)
{
    return array_map(function ($item) {
        return htmlentities($item, ENT_QUOTES, 'UTF-8');
    }, $data);
}

function uploadProductPicture($inputName, $oldFileName = null)
{
    return uploadFile($inputName, 'products', $oldFileName);
}

function uploadFile($inputName, $folderName = null, $oldFileName = null)
{
    $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];

    if (isset($_FILES[$inputName])) {
        $tmpFile = $_FILES[$inputName]['tmp_name'];
        $path = $folderName ? ROOT_PATH."/public/uploads/{$folderName}/"
                            : ROOT_PATH.'/public/uploads/';

        $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
        $newFile = uniqid().'.'.$ext;

        if (in_array($ext, $allowedFormats)) {
            if (move_uploaded_file($tmpFile, $path.$newFile)) {
                if ($oldFileName) {
                    @unlink($path.$oldFileName);
                }

                return $newFile;
            } else {
                throw new \Exception('Something went wrong with file upload.');
            }
        } else {
            throw new \Exception('Wrong file format, '.implode(',', $allowedFormats).' is allowed only.');
        }
    }
}
