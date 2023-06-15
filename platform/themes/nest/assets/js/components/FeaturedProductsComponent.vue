<template>
    <div>
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div v-carousel class="carousel-3-columns-cover position-relative" v-if="!isLoading" data-items-xl="3" data-items-lg="3" data-items-md="2" data-items-sm="1">
            <div class="slider-arrow slider-arrow-2 carousel-3-columns-arrow" id="carousel-3-columns-products-arrows"></div>
            <div class="carousel-slider-wrapper carousel-3-columns" id="carousel-3-columns-products">
                <div class="product-cart-wrap small hover-up p-10" v-for="item in data" :key="item.id" v-if="!isLoading && data.length" v-html="item">
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
                data: []
            };
        },

        mounted() {
            this.getData();
        },

        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            }
        },

        methods: {
            getData() {
                this.data = [];
                this.isLoading = true;
                axios.get(this.url)
                    .then(res => {
                        this.data = res.data.data ? res.data.data : [];
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            },
        }
    }
</script>
