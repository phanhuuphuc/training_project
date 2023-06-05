<?php

namespace Mi\L5Core\Traits;

use Illuminate\Support\Facades\Storage;

trait ManipulateFile
{
    /**
     * Generate image path
     *
     * @param string|null $name
     * @return string
     */
    protected function generateFilePath(string $name = null)
    {
        $path = date('Y/m_d/H_m/') . crc32(get_class($this)) . '/' . rand();

        return trim($path.'/'.$name, '/');
    }

    /**
     * Save private file to the storage
     *
     * @param mixed $file
     * @param string|null $path
     * @return string
     */
    protected function putPrivateFile($file, $path = null)
    {
        return Storage::putFileAs(
            $path ?: $this->generateFilePath($file->hashName()),
            $file,
            '',
            [ 'visibility' => 'private' ]
        );
    }

    /**
     * Generate file data from file
     *
     * @param mixed $file
     * @param array $options
     * @return array
     */
    protected function generateFileData($file, $options = [])
    {
        // TODO: handle for other files more than image
        list($width, $height) = getimagesize($file);

        return array_merge([
            'name'      => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size'      => $file->getClientSize(),
            'width'     => $width,
            'height'    => $height,
            'path'      => $this->generateFilePath($file->hashName())
        ], $options);
    }
}
