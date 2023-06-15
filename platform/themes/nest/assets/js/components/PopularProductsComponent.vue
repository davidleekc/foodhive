<template>
    <div class="container" v-if="!noData">
        <div class="section-title style-2 wow animate__animated animate__fadeIn" v-if="!isLoading">
            <div class="title">
                <h3>{{ title }}</h3>
            </div>
        </div>
        <div :class="'row product-grid-' + perRow">
            <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 mb-lg-0 mb-md-5 mb-sm-5" v-for="(item, key) in data" :key="key">
                <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" :data-wow-delay="(key + 1) / 10 +'s'">
                    <div class="product-img-action-wrap">
                        <div class="product-img product-img-zoom">
                            <a :href="item.url">
                                <img class="default-img" :src="item.image" :alt="item.name" />
                                <img class="hover-img" :src="item.hover_image" :alt="item.name" />
                            </a>
                        </div>
                        <div class="product-action-1">
                            <a :aria-label="__('Quick View')" href="#" class="action-btn hover-up js-quick-view-button" :data-url="item.urls.quick_view">
                                <i class="fi-rs-eye"></i>
                            </a>
                            <a :aria-label="__('Add To Wishlist')" href="#" class="action-btn hover-up js-add-to-wishlist-button" :data-url="item.urls.wishlist" v-if="item.urls.wishlist">
                                <i class="fi-rs-heart"></i>
                            </a>
                            <a :aria-label="__('Add To Compare')" href="#" class="action-btn hover-up js-add-to-compare-button" :data-url="item.urls.compare" v-if="item.urls.compare">
                                <i class="fi-rs-shuffle"></i>
                            </a>
                        </div>
                        <div class="product-badges product-badges-position product-badges-mrg">
                            <span v-if="item.is_out_of_stock" style="background-color: #000; font-size: 11px;">{{ __('Out Of Stock') }}</span>
                            <span v-else-if="item.product_labels && item.product_labels.length"
                                v-for="(label, k) in item.product_labels" :key="k" :style="label.color ? 'background-color: ' + label.color  + '' : ''">{{ label.name }}</span>
                            <span v-else-if="item.sale_percentage" class="hot">{{ item.sale_percentage }}</span>
                        </div>
                    </div>
                    <div class="product-content-wrap">
                        <div class="product-category" v-if="item.category && item.category.name">
                            <a :href="item.category.url">{{ item.category.name }}</a>
                        </div>
                        <h2><a :href="item.url">{{ item.name }}</a></h2>
                        <div class="product-rate-cover" v-if="item.reviews">
                            <div class="product-rate d-inline-block">
                                <div class="product-rating" :style="'width: ' + (item.reviews ? item.reviews.avg * 20 : 0)  + '%'"></div>
                            </div>
                            <span class="font-small ml-5 text-muted"> ({{ item.reviews ? item.reviews.count : 0 }})</span>
                        </div>
                        <div v-if="item.vendor && item.vendor.name">
                            <span class="font-small text-muted">{{ __('Sold By') }} <a :href="item.vendor.url">{{ item.vendor.name }}</a></span>
                        </div>
                        <div class="product-card-bottom">
                            <div class="product-price">
                                <span v-if="!item.sale_price || item.sale_price === item.price">{{ item.price }}</span>
                                <span v-if="item.sale_price && item.sale_price !== item.price">{{ item.sale_price }}</span>
                                <span class="old-price" v-if="item.sale_price && item.sale_price !== item.price">{{ item.price }}</span>
                            </div>
                            <div class="add-cart" v-if="item.urls.add_to_cart">
                                <a :aria-label="__('Add To Cart')"
                                    class="action-btn add-to-cart-button add"
                                    :data-id="item.id"
                                    :data-url="item.urls.add_to_cart"
                                    href="#">
                                    <i class="fi-rs-shopping-cart mr-5"></i> <span class="d-inline-block">{{ __('Add') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                isLoading: true,
                data: [],
                noData: false,
            };
        },
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
            title: {
                type: String,
                default: () => null,
            },
            perRow: {
                type: Number,
                default: () => 4,
                required: true
            },
        },
        mounted() {
          this.getData();
        },
        methods: {
            getData() {
                this.noData = false;
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        this.data = res.data.data || [];
                        this.isLoading = false;
                        this.noData = !this.data.length;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>
