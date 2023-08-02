<?php
namespace App\Console\Commands\Edi;

use App\Console\Commands\BaseCommand;
use App\Models\Legacy\EdiCustomer;
use Carbon\Carbon;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;

class EdiBaseCommand extends BaseCommand
{
    protected $name = "edi-base-command";
    public $signature = 'edi:help';
    public $description = "EDI Help";

    protected $fileSystem = null;
    protected $customerEdiInfo = null;
    protected $customerCode = null;

    protected function getCustomerCode()
    {
        if (empty($this->customerCode)) {
            $this->customerCode = $this->argument('customerCode');
        }

        return $this->customerCode;
    }

    public function getUniqueEdiID()
    {
        return mt_rand(100000000,999999999);
    }

    /**
     * @return Filesystem
     */
    protected function getRemoteFilesystem()
    {
        if (empty($this->fileSystem)) {
            $ediServerInfo = $this->getCustomerEdiInfo();

            $this->fileSystem = new Filesystem(new SftpAdapter(new SftpConnectionProvider(
                $ediServerInfo->getEdiStorageHost(),
                $ediServerInfo->getEdiStorageUsername(),
                $ediServerInfo->getEdiStoragePassword()
            ),
                $ediServerInfo->getEdiStorageRoot(),
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
        }

        return $this->fileSystem;
    }

    /**
     * @return EdiCustomer
     */
    protected function getCustomerEdiInfo()
    {
        if (empty($this->customerEdiInfo)) {

            $ftpDetails = EdiCustomer::where('CustomerCode', $this->getCustomerCode())->first();

            $this->customerEdiInfo = $ftpDetails;
        }

        return $this->customerEdiInfo;
    }

    public function encodeEdi($ediContents)
    {
        return base64_encode(json_encode($ediContents));
    }

    public function uploadEdi($ediDoc, $docType, $docName, $ftpCreds)
    {
        $data = $ediDoc->rawData();

        $tmpFile = tempnam(storage_path(), "edi{$docType}_");

        file_put_contents($tmpFile, $data);

        $fileName = $docName . '_' . Carbon::now()->format("Hi") . '.edi';

        $this->uploadToS3('perfecterp-edi', Carbon::now()->format('Ymd') . '/' . $fileName, $tmpFile);

        if ($ftpCreds) {
            $this->uploadToSFTP($ftpCreds, $ftpCreds['outboundDir'] . '/' . $fileName, $tmpFile);
        }
    }

}
