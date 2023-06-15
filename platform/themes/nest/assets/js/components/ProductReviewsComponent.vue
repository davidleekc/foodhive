<template>
    <div class="block__content comment-list">
        <div :class="'block--review single-comment justify-content-between d-flex mb-30' + (key % 2 !== 0 ? ' ml-30' : '')"
            v-for="(item, key) in data" :key="item.id">
            <div class="user justify-content-between d-flex">
                <div class="thumb text-center">
                    <img :src="item.user_avatar" :alt="item.user_name" />
                    <span class="font-heading text-brand d-block">{{ item.user_name }}</span>
                </div>
                <div class="desc">
                    <div class="d-flex justify-content-between mb-10">
                        <div>
                            <span v-if="item.ordered_at" class="font-sm text-muted d-block">{{ item.ordered_at }}</span>
                            <span class="font-xs text-muted">{{ item.created_at }}</span>
                        </div>
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" :style="{width: item.star * 20 + '%'}"></div>
                        </div>
                    </div>
                    <p class="mb-10">{{ item.comment }}</p>

                    <div class="block__images" v-if="item.images && item.images.length">
                        <a :href="image.full_url" v-for="(image, index) in item.images" :key="index">
                            <img :src="image.thumbnail" :alt="image.thumbnail" class="img-responsive rounded h-100">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="review__loading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>

        <div v-if="!isLoading && !data.length" class="text-center">
            <p>{{ __('No reviews!') }}</p>
        </div>

        <div class="pagination-area mt-15 mb-md-5 mb-lg-0">
            <pagination :data="meta" @on-click-paging="onClickPaginate" />
        </div>
    </div>
</template>

<script>
import Pagination from './utils/Pagination.vue';

export default {
    data: function() {
        return {
            isLoading: true,
            data: [],
            meta: {},
            star: 0,
        };
    },
    props: {
        url: {
            type: String,
            default: () => null,
            required: true
        },
    },
    mounted() {
        this.getData(this.url, false);
        let that = this;

        $(document).on('change', '.ps-review__filter-select select', event => {
            event.preventDefault();
            let $select = $(event.currentTarget);
            if (that.star != $select.val()) {
                that.filterByStar($select.val());
            }
        });

        $(document).on('click', '.ps-block--average-rating .ps-block__star', event => {
            event.preventDefault();
            let $block = $(event.currentTarget);
            let hasActive = $block.hasClass('active');
            if (!hasActive) {
                that.filterByStar($block.data('star'));
            } else {
                that.filterByStar();
            }
        });
    },
    methods: {
        filterByStar(star = 0) {
            let url = this.url;
            this.star = star;
            $('.ps-block--average-rating .ps-block__star').removeClass('active');
            if (star && star != 0) {
                url = this.getUriWithParam(url, {'star': star});
                $('.ps-block--average-rating .ps-block__star[data-star=' + star + ']').addClass('active');
            }
            $('.ps-review__filter-select select').val(star).trigger('change');
            this.getData(url);
        },
        getUriWithParam(baseUrl, params) {
            const url = new URL(baseUrl);
            const urlParams = new URLSearchParams(url.search);
            for (const key in params) {
                if (params[key] !== undefined) {
                    urlParams.set(key, params[key]);
                }
            }
            url.search = urlParams.toString();
            return url.toString();
        },
        getData(link, animation = true) {
            this.isLoading = true;

            if (animation) {
                $('html, body').animate({
                    scrollTop: ($('.block--product-reviews').offset().top - $('.header-area').height() - 165) + 'px',
                }, 1500);
            }

            axios.get(link)
                .then(res => {
                    this.data = res.data.data || [];
                    this.meta = res.data.meta;
                    this.isLoading = false;

                    $('.block--product-reviews .block__header h2').html(res.data.message);
                })
                .catch(res => {
                    this.isLoading = false;
                    console.log(res);
                });
        },
        onClickPaginate({element}) {
            if (!element.active) {
                this.getData(element.url);
            }
        }
    },
    updated: function () {
        let $galleries = $('.block__images');
        if ($galleries.length) {
            $galleries.map((index, value) => {
                if (jQuery().magnificPopup) {
                    $(value).magnificPopup({
                        delegate: 'a', // the selector for gallery item
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });
                }
            });
        }
    },

    components: {
        Pagination
    }
}
</script>
