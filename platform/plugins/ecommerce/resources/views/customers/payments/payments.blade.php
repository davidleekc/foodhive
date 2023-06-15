<div class="widget-body p-0" id="payment-histories">
    <div class="comment-log-timeline">
        <div class="column-left-history ps-relative" id="order-history-wrapper">
                <div class="item-card">
                    <div class="item-card-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.order') }}</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.charge_id') }}</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.amount') }}</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.payment_method') }}</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.status') }}</th>
                                <th scope="col">{{ trans('plugins/ecommerce::payment.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start"> {{ $payment->order->code }}</td>
                                    <td>{{ $payment->charge_id }}</td>
                                    <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                                    <td>{{ $payment->payment_channel->label() }}</td>
                                    <td>{!! $payment->status->toHtml() !!}</td>
                                    <td class="text-center" style="width: 120px;">
                                        <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-icon btn-sm btn-info me-1 btn-trigger-edit-payment"
                                           data-bs-toggle="tooltip"
                                           role="button" data-bs-original-title="{{ trans('core/base::forms.view_new_tab') }}" target="_blank">
                                            <i class="fa fa-external-link"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <td colspan="7" class="text-center">{{ trans('plugins/ecommerce::payment.no_data') }}</td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

{!! Form::modalAction('edit-payment-modal', trans('plugins/ecommerce::payment.edit_payment'), 'info', null, 'confirm-edit-payment-button', trans('plugins/ecommerce::payment.save'), 'modal-md') !!}
