<?php

namespace App\Services\Customer;

use App\Enums\ActiveStatus;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\DB;
use Mi\L5Core\Services\BaseService;
use Illuminate\Support\Facades\Validator;

class ImportCustomerService extends BaseService
{
    protected $collectsData = true;
    protected $storeCustomerDataRules;
    const CHUNK = 1000;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        list($customers, $rowsNotValid) = $this->prepareData();
        $rowsValid = count($customers);

        if ($rowsValid > 0) {
            // chunk data for multi insert
            $customers = array_chunk($customers, self::CHUNK);

            DB::transaction(function () use ($customers) {
                foreach ($customers as $chunkCustomers) {
                    Customer::insert($chunkCustomers);
                }
            });
        }
        return [$rowsValid, $rowsNotValid];
    }

    /**
     * prepareData
     */
    public function prepareData()
    {
        $customers = [];
        $rowsNotValid = [];

        $file = $this->data->get('file_csv');
        $path = $file->getRealPath();
        $checkEmailExist = [];
        $rowIndex = 0;
        $this->storeCustomerDataRules = (new StoreCustomerRequest())->rules();
        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, self::CHUNK, ',')) !== false) {
                $customer = [
                    'customer_name' => $data[0],
                    'email' => $data[1],
                    'tel_num' => $data[2],
                    'address' => $data[3],
                    'is_active' => ActiveStatus::ACTIVE
                ];

                $checkRowValid = ((!isset($checkEmailExist[$data[1]])) && !(Validator::make($customer, $this->storeCustomerDataRules))->fails());

                switch ($checkRowValid) {
                    case true:
                        $customers[] = $customer;
                        $checkEmailExist[$data[1]] = true;
                        break;
                    default:
                        $rowsNotValid[] = $rowIndex;
                }

                $rowIndex++;
            }
            fclose($handle);
        }
        array_shift($rowsNotValid);
        return [$customers, $rowsNotValid];
    }
}
