<?php

namespace Modules\User\Presentation\Controllers;

use Src\Infrastructure\Storage\StorageManager;
use Src\Presentation\Http\Response;

class UploadController
{
    public function upload(): void
    {
        if (
            !isset($_FILES['file'])
        ) {

            Response::json([

                'message' =>
                    'No file uploaded'

            ], 422);

            return;
        }

        $file =
            $_FILES['file'];

        $allowed = [

            'jpg',
            'jpeg',
            'png',
            'pdf'
        ];

        $extension =
            strtolower(

                pathinfo(

                    $file['name'],

                    PATHINFO_EXTENSION

                )

            );

        if (
            !in_array(
                $extension,
                $allowed
            )
        ) {

            Response::json([

                'message' =>
                    'Invalid file type'

            ], 422);

            return;
        }

        $path =
            StorageManager::driver()
                ->store($file);

        Response::json([

            'message' =>
                'File uploaded',

            'path' => $path

        ]);
    }
}