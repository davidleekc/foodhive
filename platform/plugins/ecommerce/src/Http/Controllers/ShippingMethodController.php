<?php

namespace Botble\Ecommerce\Http\Controllers;

use Assets;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;
use Botble\Ecommerce\Http\Requests\AddShippingRegionRequest;
use Botble\Ecommerce\Http\Requests\ShippingRuleRequest;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use EcommerceHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ShippingMethodController extends BaseController
{
    public function __construct(
        protected ShippingInterface $shippingRepository,
        protected ShippingRuleInterface $shippingRuleRepository,
        protected OrderInterface $orderRepository,
        protected ShippingRuleItemInterface $shippingRuleItemRepository
    ) {
    }

    public function index()
    {
        page_title()->setTitle(trans('plugins/ecommerce::shipping.shipping_methods'));

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Assets::addScriptsDirectly('vendor/core/plugins/location/js/location.js');
        }

        Assets::addStylesDirectly(['vendor/core/plugins/ecommerce/css/ecommerce.css'])
            ->addScriptsDirectly(['vendor/core/plugins/ecommerce/js/shipping.js'])
            ->addScripts(['input-mask']);

        $shipping = $this->shippingRepository
            ->allBy([], [
                'rules' => function ($query) {
                    $query->withCount(['items']);
                },
            ]);

        return view('plugins/ecommerce::shipping.methods', compact('shipping'));
    }

    public function postCreateRegion(AddShippingRegionRequest $request, BaseHttpResponse $response)
    {
        $country = $request->input('region');

        $shipping = $this->shippingRepository->createOrUpdate([
            'title' => $country ?: trans('plugins/ecommerce::shipping.all'),
            'country' => $country,
        ]);

        if (! $shipping) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::shipping.error_when_adding_new_region'));
        }

        $default = $this->shippingRepository
            ->getModel()
            ->whereNull('country')
            ->join('ec_shipping_rules', 'ec_shipping_rules.shipping_id', 'ec_shipping.id')
            ->select(['ec_shipping_rules.from', 'ec_shipping_rules.to', 'ec_shipping_rules.price'])
            ->first();

        $from = 0;
        $to = null;
        $price = 0;
        if ($default) {
            $from = $default->from;
            $to = $default->to;
            $price = $default->price;
        }

        $this->shippingRuleRepository->createOrUpdate([
            'name' => trans('plugins/ecommerce::shipping.delivery'),
            'type' => ShippingRuleTypeEnum::BASED_ON_PRICE,
            'price' => $price,
            'from' => $from,
            'to' => $to,
            'shipping_id' => $shipping->id,
        ]);

        return $response->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function deleteRegion(Request $request, BaseHttpResponse $response)
    {
        $shipping = $this->shippingRepository->findOrFail($request->input('id'));

        $this->shippingRepository->delete($shipping);
        $this->shippingRuleRepository->deleteBy(['shipping_id' => $shipping->id]);

        event(new DeletedContentEvent(SHIPPING_MODULE_SCREEN_NAME, $request, $shipping));

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function deleteRegionRule(Request $request, BaseHttpResponse $response)
    {
        $rule = $this->shippingRuleRepository->findOrFail($request->input('id'));
        $this->shippingRuleRepository->delete($rule);

        $ruleCount = $this->shippingRuleRepository->count(['shipping_id' => $rule->shipping_id]);

        if ($ruleCount === 0) {
            $shipping = $this->shippingRepository->findOrFail($rule->shipping_id);
            $this->shippingRepository->delete($shipping);
            event(new DeletedContentEvent(SHIPPING_MODULE_SCREEN_NAME, $request, $shipping));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'))->setData([
            'count' => $ruleCount,
            'shipping_id' => $rule->shipping_id,
        ]);
    }

    public function putUpdateRule(int|string $id, ShippingRuleRequest $request, BaseHttpResponse $response)
    {
        $rule = $this->shippingRuleRepository->findOrFail($id);

        $rule->fill($request->input());

        $rule = $this->shippingRuleRepository->createOrUpdate($rule);

        if ($rule->type != ShippingRuleTypeEnum::BASED_ON_ZIPCODE) {
            $this->shippingRuleItemRepository->deleteBy(['shipping_rule_id' => $id]);

            foreach ($request->input('shipping_rule_items', []) as $key => $item) {
                if (Arr::get($item, 'is_enabled', 0) == 0 || Arr::get($item, 'adjustment_price', 0) != 0) {
                    $this->shippingRuleItemRepository->createOrUpdate([
                        'shipping_rule_id' => $id,
                        'city' => $key,
                        'adjustment_price' => Arr::get($item, 'adjustment_price', 0),
                        'is_enabled' => Arr::get($item, 'is_enabled', 0),
                    ]);
                }
            }
        }

        $data = view('plugins/ecommerce::shipping.rules.item', compact('rule'))->render();

        return $response->setData([
                'rule' => $rule->toArray(),
                'html' => $data,
            ])
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function postCreateRule(ShippingRuleRequest $request, BaseHttpResponse $response)
    {
        $shipping = $this->shippingRepository->findOrFail($request->input('shipping_id'));

        if (! $shipping->country && in_array($request->input('type'), [ShippingRuleTypeEnum::BASED_ON_ZIPCODE, ShippingRuleTypeEnum::BASED_ON_LOCATION])) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/ecommerce::shipping.rule.cannot_create_rule_type_for_this_location', ['type' => ShippingRuleTypeEnum::getLabel($request->input('type'))]));
        }

        $rule = $this->shippingRuleRepository->createOrUpdate($request->input());
        $data = view('plugins/ecommerce::shipping.rules.item', compact('rule'))->render();

        return $response
            ->setMessage(trans('core/base::notices.create_success_message'))
            ->setData([
                'rule' => $rule->toArray(),
                'html' => $data,
            ]);
    }
}
