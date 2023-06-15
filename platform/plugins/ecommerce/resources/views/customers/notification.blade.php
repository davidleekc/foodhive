@if(! $data->confirmed_at)
    <div class="note note-warning">
        <p>
            {!! BaseHelper::clean(trans('plugins/ecommerce::customer.verify_email.notification', [
                'approve_link' => Html::link(route('customers.verify-email', $data->id),
                trans('plugins/ecommerce::customer.verify_email.approve_here'),
                ['class' => 'verify-customer-email-button']),
            ])) !!}
        </p>
    </div>

    @push('footer')
        {!! Form::modalAction(
            'verify-customer-email-modal',
            trans('plugins/ecommerce::customer.verify_email.confirm_heading'),
            'warning',
            trans('plugins/ecommerce::customer.verify_email.confirm_description'),
            'confirm-verify-customer-email-button',
            trans('plugins/ecommerce::customer.verify_email.confirm_button'))
        !!}
    @endpush
@endif
