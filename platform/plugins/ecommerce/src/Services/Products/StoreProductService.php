<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Events\ProductQuantityUpdatedEvent;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Media\Services\UploadsManager;
use EcommerceHelper;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Storage;

class StoreProductService
{
    public function __construct(protected ProductInterface $productRepository)
    {
    }

    public function execute(Request $request, Product $product, bool $forceUpdateAll = false): Product
    {
        $data = $request->input();

        $hasVariation = $product->variations()->count() > 0;

        if ($hasVariation && ! $forceUpdateAll) {
            $data = $request->except([
                'sku',
                'quantity',
                'allow_checkout_when_out_of_stock',
                'with_storehouse_management',
                'stock_status',
                'sale_type',
                'price',
                'sale_price',
                'start_date',
                'end_date',
                'length',
                'wide',
                'height',
                'weight',
            ]);
        }

        $product->fill($data);

        $images = [];

        if ($request->input('images', [])) {
            $images = array_values(array_filter($request->input('images', [])));
        }

        $product->images = json_encode($images);

        if (! $hasVariation || $forceUpdateAll) {
            if ($product->sale_price > $product->price) {
                $product->sale_price = null;
            }

            if ($product->sale_type == 0) {
                $product->start_date = null;
                $product->end_date = null;
            }
        }

        $exists = $product->id;

        if (! $exists && EcommerceHelper::isEnabledCustomerRecentlyViewedProducts() && $request->input('product_type')) {
            if (in_array($request->input('product_type'), ProductTypeEnum::toArray())) {
                $product->product_type = $request->input('product_type');
            }
        }

        /**
         * @var Product $product
         */
        $product = $this->productRepository->createOrUpdate($product);

        if (! $exists) {
            event(new CreatedContentEvent(PRODUCT_MODULE_SCREEN_NAME, $request, $product));
        } else {
            event(new UpdatedContentEvent(PRODUCT_MODULE_SCREEN_NAME, $request, $product));
        }

        if ($product) {
            $product->categories()->sync($request->input('categories', []));

            $product->productCollections()->sync($request->input('product_collections', []));

            $product->productLabels()->sync($request->input('product_labels', []));

            $product->taxes()->sync($request->input('taxes', []));

            if ($request->has('related_products')) {
                $product->products()->detach();

                if ($relatedProducts = $request->input('related_products', '')) {
                    $product->products()->attach(array_filter(explode(',', $relatedProducts)));
                }
            }

            if ($request->has('cross_sale_products')) {
                $product->crossSales()->detach();

                if ($crossSaleProducts = $request->input('cross_sale_products', '')) {
                    $product->crossSales()->attach(array_filter(explode(',', $crossSaleProducts)));
                }
            }

            if ($request->has('up_sale_products')) {
                $product->upSales()->detach();

                if ($upSaleProducts = $request->input('up_sale_products', '')) {
                    $product->upSales()->attach(array_filter(explode(',', $upSaleProducts)));
                }
            }

            if (EcommerceHelper::isEnabledSupportDigitalProducts() && $product->isTypeDigital()) {
                $this->saveProductFiles($request, $product);
            }

            if ($request->input('has_product_options')) {
                $this->productRepository->saveProductOptions((array)$request->input('options', []), $product);
            }
        }

        event(new ProductQuantityUpdatedEvent($product));

        return $product;
    }

    public function saveProductFiles(Request $request, Product $product, bool $exists = true): Product
    {
        if ($exists) {
            foreach ($request->input('product_files', []) as $key => $value) {
                if (! $value) {
                    $product->productFiles()->where('id', $key)->delete();
                }
            }
        }

        if ($request->hasFile('product_files_input')) {
            foreach ($request->file('product_files_input', []) as $file) {
                try {
                    $data = $this->saveProductFile($file);
                    $product->productFiles()->create($data);
                } catch (Exception $ex) {
                    info($ex);
                }
            }
        }

        return $product;
    }

    public function saveProductFile(UploadedFile $file): array
    {
        $folderPath = 'product-files';
        $fileExtension = $file->getClientOriginalExtension();
        $content = File::get($file->getRealPath());
        $name = File::name($file->getClientOriginalName());
        $fileName = app(MediaFileInterface::class)->createSlug(
            $name,
            $fileExtension,
            Storage::path($folderPath)
        );

        $filePath = $folderPath . '/' . $fileName;
        app(UploadsManager::class)->saveFile($filePath, $content, $file);
        $data = app(UploadsManager::class)->fileDetails($filePath);
        $data['name'] = $name;
        $data['extension'] = $fileExtension;

        return [
            'url' => $filePath,
            'extras' => $data,
        ];
    }
}
