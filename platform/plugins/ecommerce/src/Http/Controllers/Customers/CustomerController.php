<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Assets;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\CustomerForm;
use Botble\Ecommerce\Http\Requests\AddCustomerWhenCreateOrderRequest;
use Botble\Ecommerce\Http\Requests\CustomerCreateRequest;
use Botble\Ecommerce\Http\Requests\CustomerEditRequest;
use Botble\Ecommerce\Http\Requests\CustomerUpdateEmailRequest;
use Botble\Ecommerce\Http\Resources\CustomerAddressResource;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Tables\CustomerTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends BaseController
{
    public function __construct(protected CustomerInterface $customerRepository, protected AddressInterface $addressRepository)
    {
    }

    public function index(CustomerTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::customer.name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::customer.create'));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/customer.js');

        return $formBuilder->create(CustomerForm::class)->remove('is_change_password')->renderForm();
    }

    public function store(CustomerCreateRequest $request, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->getModel();
        $customer->fill($request->input());
        $customer->confirmed_at = Carbon::now();
        $customer->password = Hash::make($request->input('password'));
        $customer->dob = Carbon::parse($request->input('dob'))->toDateString();
        $customer = $this->customerRepository->createOrUpdate($customer);

        event(new CreatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $response
            ->setPreviousUrl(route('customers.index'))
            ->setNextUrl(route('customers.edit', $customer->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder)
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/customer.js');

        $customer = $this->customerRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/ecommerce::customer.edit', ['name' => $customer->name]));

        $customer->password = null;

        return $formBuilder->create(CustomerForm::class, ['model' => $customer])->renderForm();
    }

    public function update(int|string $id, CustomerEditRequest $request, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->findOrFail($id);

        $customer->fill($request->except('password'));

        if ($request->input('is_change_password') == 1) {
            $customer->password = Hash::make($request->input('password'));
        }

        $customer->dob = Carbon::parse($request->input('dob'))->toDateString();

        $customer = $this->customerRepository->createOrUpdate($customer);

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $response
            ->setPreviousUrl(route('customers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $customer = $this->customerRepository->findOrFail($id);
            $this->customerRepository->delete($customer);
            event(new DeletedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $customer = $this->customerRepository->findOrFail($id);
            $this->customerRepository->delete($customer);
            event(new DeletedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function verifyEmail(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->getFirstBy([
            'id' => $id,
            'confirmed_at' => null,
        ]);

        if (! $customer) {
            abort(404);
        }

        $customer->confirmed_at = Carbon::now();
        $customer->save();

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $response
            ->setPreviousUrl(route('customers.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getListCustomerForSelect(BaseHttpResponse $response)
    {
        $customers = $this->customerRepository
            ->allBy([], [], ['id', 'name'])
            ->toArray();

        return $response->setData($customers);
    }

    public function getListCustomerForSearch(Request $request, BaseHttpResponse $response)
    {
        $customers = $this->customerRepository
            ->getModel()
            ->where('name', 'LIKE', '%' . $request->input('keyword') . '%')
            ->simplePaginate(5);

        foreach ($customers as &$customer) {
            $customer->avatar_url = (string)$customer->avatar_url;
        }

        return $response->setData($customers);
    }

    public function postUpdateEmail($id, CustomerUpdateEmailRequest $request, BaseHttpResponse $response)
    {
        $this->customerRepository->createOrUpdate(['email' => $request->input('email')], ['id' => $id]);

        return $response->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getCustomerAddresses($id, BaseHttpResponse $response)
    {
        $addresses = $this->addressRepository->allBy(['customer_id' => $id]);

        return $response->setData(CustomerAddressResource::collection($addresses));
    }

    public function getCustomerOrderNumbers($id, BaseHttpResponse $response)
    {
        $customer = $this->customerRepository->findById($id);
        if (! $customer) {
            return $response->setData(0);
        }

        return $response->setData($customer->orders()->count());
    }

    public function postCreateCustomerWhenCreatingOrder(
        AddCustomerWhenCreateOrderRequest $request,
        BaseHttpResponse $response
    ) {
        $request->merge(['password' => Hash::make(Str::random(36))]);
        $customer = $this->customerRepository->createOrUpdate($request->input());
        $customer->avatar = (string)$customer->avatar_url;

        event(new CreatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        $request->merge([
            'customer_id' => $customer->id,
            'is_default' => true,
        ]);

        $address = $this->addressRepository->createOrUpdate($request->input());

        $address->country = $address->country_name;
        $address->state = $address->state_name;
        $address->city = $address->city_name;

        $address->country_name = $address->country;
        $address->state_name = $address->state;
        $address->city_name = $address->city;

        return $response
            ->setData(compact('address', 'customer'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }
}
