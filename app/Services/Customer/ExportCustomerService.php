<?php

namespace App\Services\Customer;

use App\Http\Requests\BaseRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Repositories\CustomerRepository;
use Mi\L5Core\Services\BaseService;
use League\Csv\Writer;
use App\Services\Customer\ListCustomerService;

class ExportCustomerService extends BaseService
{
    protected $collectsData = true;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {
        // check $noFilter
        $hasFilters =  $this->data->get('customer_name') ||
            $this->data->get('email') ||
            $this->data->get('address') ||
            $this->data->get('is_active');

        // if !$hasFilters then only get first page
        if (!$hasFilters) {
            $this->data->put('per_page', BaseRequest::DEFAULT_PER_PAGE);
        }

        $customers = resolve(ListCustomerService::class)->setData($this->data)->handle();

        if (!$hasFilters) {
            $customers = new CustomerCollection($customers);
        }

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['Tên khác hàng', 'Email', 'TelNum', 'Địa chỉ']);

        // Add data rows to the CSV file
        foreach ($customers as $customer) {
            $csv->insertOne([$customer->customer_name, $customer->email, $customer->tel_num, $customer->address]);
        }

        // Set the response headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="export.csv"',
        ];

        // Return the CSV file as a download response
        return response($csv->getContent(), 200, $headers);
    }
}
