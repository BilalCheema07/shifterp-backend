<?php
namespace App\Console\Commands;

use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

class BaseCommand extends Command
{
    protected $name = "base-command";
    private $s3Client = null;

    /**
     * @return S3Client
     */
    private function getS3Client()
    {
        if (empty($this->s3Client)) {
            $this->s3Client = App::make('aws')->createClient('s3');
        }

        return $this->s3Client;
    }
    public function exportToCSV($header, $data, $fileName)
    {
        $handle = fopen($fileName, 'w');

        fputcsv($handle, $header);

        foreach ($data as $d) {
            fputcsv($handle, $d);
        }

        fclose($handle);
    }

    public function uploadToS3($bucket, $remoteFileName, $localFileName)
    {
        $s3 = $this->getS3Client();

        return $s3->putObject(array(
            'Bucket'     => $bucket,
            'Key'        => $remoteFileName,
            'SourceFile' => $localFileName,
        ));
    }

    public function deleteObject($bucket, $file)
    {
        $s3 = $this->getS3Client();

        return $s3->deleteObject([
            'Bucket'  => $bucket,
            'Key'  => $file,
        ]);
    }

    public function getObjectContents($bucket, $file)
    {
        $s3 = $this->getS3Client();

        $tmpFile = tempnam(storage_path(), "ediprocess_");

        $s3->getObject([
            'Bucket'  => $bucket,
            'Key'  => $file,
            'SaveAs' => $tmpFile
        ]);

        return $tmpFile;
    }

    public function listObjectsInBucket($bucket, $path)
    {
        $s3 = $this->getS3Client();

        $results = [];

        $objects = $s3->listObjectsV2([
            'Bucket'  => $bucket,
            'Prefix'  => $path,
            'MaxKeys' => 10
        ]);

        if (!empty($objects['Contents'])) {
            $results = $objects['Contents'];
        }

        return $results;
    }

    public function listFiles(array $creds, string $remoteFileName)
    {
        $filesystem = new Filesystem(new SftpAdapter(new SftpConnectionProvider(
            $creds['host'],
            $creds['username'],
            $creds['password']
        ),
            '/',
            PortableVisibilityConverter::fromArray([
                'file' => [
                    'public' => 0640,
                    'private' => 0604,
                ],
                'dir' => [
                    'public' => 0740,
                    'private' => 7604,
                ],
            ])));


        try {
            $this->info("Downloading file ($remoteFileName) from SFTP");

            $contents = $filesystem->listContents('Outbound');

            $tableData = [];

            /** @var FileAttributes $content */
            foreach ($contents as $content) {
                $tableData[] = [
                    $content->path(),
                    Carbon::parse($content->lastModified())->format('Y-m-d H:i:s')
                ];
            }

            $this->table(['File Name', 'Last Modified'], $tableData);

            $data = $filesystem->read($remoteFileName);
            dd($data);

        } catch (FilesystemException $e) {
            dd($e->getMessage());
            // TODO: Log the error in elastic.
        }
    }

    public function uploadToSFTP($filesystem, string $remoteFileName, string $localFileName, string $fileContents = null)
    {
        if (empty($fileContents)) {
            $contents = file_get_contents($localFileName);
        }

        try {
            $this->info("Uploading file ($localFileName) to SFTP");

            $pathInfo = pathinfo($remoteFileName);
            $dirName = $pathInfo['dirname'];

            $filesystem->write($remoteFileName, $contents);

            $contents = $filesystem->listContents($dirName);

            $tableData = [];

            /** @var FileAttributes $content */
            foreach ($contents as $content) {
                $tableData[] = [
                    $content->path(),
                    Carbon::parse($content->lastModified())->format('Y-m-d H:i:s')
                ];
            }

            $this->table(['File Name', 'Last Modified'], $tableData);

        } catch (FilesystemException $e) {
            dd($e->getMessage());
            // TODO: Log the error in elastic.
        }

    }
}
