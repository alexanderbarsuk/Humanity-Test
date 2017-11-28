<?php
declare(strict_types = 1);

namespace Tools\Log\Storage;

use Tools\Log\Exception\WriteToStorageException;

class InFileStorage extends AbstractStorage
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * InFileStorage constructor.
     * @param string $fileName
     * @throws WriteToStorageException
     */
    public function __construct(string $fileName)
    {
        if (!file_exists($fileName)) {
            if ($createdFile = @fopen($fileName, "w")) {
                fclose($createdFile);
            } else {
                throw new WriteToStorageException("Could not create new file for storage");
            }
        }

        if (!is_writable($fileName)) {
            throw new WriteToStorageException("Could not write to file storage");
        }
        $this->fileName = $fileName;
    }

    /**
     * @param string $message
     * @throws WriteToStorageException
     * @return void
     */
    public function store(string $message) : void
    {
        $result = file_put_contents($this->fileName, $message, FILE_APPEND | LOCK_EX);

        if ($result === false) {
            throw new WriteToStorageException("Could not write message into file " . $this->fileName);
        }
    }
}
