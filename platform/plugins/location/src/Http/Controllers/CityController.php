<?php

namespace Botble\Location\Http\Controllers;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Location\Forms\CityForm;
use Botble\Location\Http\Requests\CityRequest;
use Botble\Location\Http\Resources\CityResource;
use Botble\Location\Models\City;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Tables\CityTable;
use Exception;
use Illuminate\Http\Request;

class CityController extends BaseController
{
    public function __construct(protected CityInterface $cityRepository)
    {
    }

    public function index(CityTable $table)
    {
        page_title()->setTitle(trans('plugins/location::city.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/location::city.create'));

        return $formBuilder->create(CityForm::class)->renderForm();
    }

    public function store(CityRequest $request, BaseHttpResponse $response)
    {
        $city = $this->cityRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(CITY_MODULE_SCREEN_NAME, $request, $city));

        return $response
            ->setPreviousUrl(route('city.index'))
            ->setNextUrl(route('city.edit', $city->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $city = $this->cityRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $city));

        page_title()->setTitle(trans('plugins/location::city.edit') . ' "' . $city->name . '"');

        return $formBuilder->create(CityForm::class, ['model' => $city])->renderForm();
    }

    public function update(int|string $id, CityRequest $request, BaseHttpResponse $response)
    {
        $city = $this->cityRepository->findOrFail($id);

        $city->fill($request->input());

        $this->cityRepository->createOrUpdate($city);

        event(new UpdatedContentEvent(CITY_MODULE_SCREEN_NAME, $request, $city));

        return $response
            ->setPreviousUrl(route('city.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $city = $this->cityRepository->findOrFail($id);

            $this->cityRepository->delete($city);

            event(new DeletedContentEvent(CITY_MODULE_SCREEN_NAME, $request, $city));

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
            $city = $this->cityRepository->findOrFail($id);
            $this->cityRepository->delete($city);
            event(new DeletedContentEvent(CITY_MODULE_SCREEN_NAME, $request, $city));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getList(Request $request, BaseHttpResponse $response)
    {
        $keyword = BaseHelper::stringify($request->input('q'));

        if (! $keyword) {
            return $response->setData([]);
        }

        $data = $this->cityRepository->advancedGet([
            'condition' => [
                ['cities.name', 'LIKE', '%' . $keyword . '%'],
            ],
            'select' => ['cities.id', 'cities.name'],
            'take' => 10,
            'order_by' => ['order' => 'ASC', 'name' => 'ASC'],
        ]);

        $data->prepend(new City(['id' => 0, 'name' => trans('plugins/location::city.select_city')]));

        return $response->setData(CityResource::collection($data));
    }

    public function ajaxGetCities(Request $request, BaseHttpResponse $response)
    {
        $params = [
            'select' => ['cities.id', 'cities.name'],
            'condition' => [
                'cities.status' => BaseStatusEnum::PUBLISHED,
            ],
            'order_by' => ['order' => 'ASC', 'name' => 'ASC'],
        ];

        if ($request->input('state_id') && $request->input('state_id') != 'null') {
            $params['condition']['cities.state_id'] = $request->input('state_id');
        }

        $data = $this->cityRepository->advancedGet($params);

        $data->prepend(new City(['id' => 0, 'name' => trans('plugins/location::city.select_city')]));

        return $response->setData(CityResource::collection($data));
    }
}
