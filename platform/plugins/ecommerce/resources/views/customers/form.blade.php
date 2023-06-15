@extends('core/base::forms.form-tabs')

@section('form_end')
    {!! Form::modalAction('add-address-modal', trans('plugins/ecommerce::addresses.add_address'), 'info', view('plugins/ecommerce::customers.addresses.form', [
    'data' => [
        'customer' => $form->getModel(),
    ],
    ])->render(), 'confirm-add-address-button', trans('plugins/ecommerce::addresses.add'), 'modal-md') !!}

    {!! Form::modalAction('edit-address-modal', trans('plugins/ecommerce::addresses.edit_address'), 'info', null, 'confirm-edit-address-button', trans('plugins/ecommerce::addresses.save'), 'modal-md') !!}

    @include('core/table::partials.modal-item', [
        'type' => 'danger',
        'name' => 'modal-confirm-delete',
        'title' => trans('core/base::tables.confirm_delete'),
        'content' => trans('core/base::tables.confirm_delete_msg'),
        'action_name' => trans('core/base::tables.delete'),
        'action_button_attributes' => [
            'class' => 'delete-crud-entry',
        ],
    ])
@endsection
