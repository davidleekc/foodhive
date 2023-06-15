<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Http\Requests\CreateAddressFromAdminRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Repositories\Interfaces\AddressInterface;

class AddressController extends BaseController
{
    public function __construct(protected AddressInterface $addressRepository)
    {
    }

    public function store(CreateAddressFromAdminRequest $request, BaseHttpResponse $response)
    {
        if ($request->boolean('is_default')) {
            $this->addressRepository->update([
                'is_default' => 1,
                'customer_id' => $request->input('customer_id'),
            ], ['is_default' => 0]);
        }

        $request->merge([
            'customer_id' => $request->input('customer_id'),
            'is_default' => $request->input('is_default', 0),
        ]);

        $this->addressRepository->createOrUpdate($request->input());

        return $response
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function update($id, CreateAddressFromAdminRequest $request, BaseHttpResponse $response)
    {
        if ($request->input('is_default') == 1) {
            $this->addressRepository->update([
                'is_default' => $id,
                'customer_id' => $request->input('customer_id'),
            ], ['is_default' => 0]);
        }

        $request->merge([
            'customer_id' => $request->input('customer_id'),
            'is_default' => $request->input('is_default', 0),
        ]);

        $this->addressRepository->update([
            'id' => $id,
        ], $request->input());

        return $response
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        $address = Address::findOrFail($id);

        $address->delete();

        return $response
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function edit($id)
    {
        $address = Address::findOrFail($id);

        return view('plugins/ecommerce::customers.addresses.form-edit', compact('address'))->render();
    }
}
