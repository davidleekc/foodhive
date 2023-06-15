<template>
    <div class="container">
        <div class="row">
            <div v-if="isLoading">
                <div class="half-circle-spinner">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-2"></div>
                </div>
            </div>

            <div v-for="(item, index) in data" :key="index" v-if="!isLoading && data.length"
                :class="'col-xl-' + (12 / data.length) + ' col-lg-6 col-md-6 mb-md-0'">
                <h4 class="section-title style-1 mb-30 animated animated">{{ item.title }}</h4>
                <div class="product-list-small animated animated">
                    <article class="row align-items-center hover-up" v-for="(product, i) in item.products" :key="'' + i + index + product.id">
                        <figure class="col-md-4 mb-0">
                            <a :href="product.url"><img :src="product.image" :alt="product.name" /></a>
                        </figure>
                        <div class="col-md-8 mb-0">
                            <h6>
                                <a :href="product.url">{{ product.name }}</a>
                            </h6>
                            <div class="product-rate-cover" v-if="product.reviews_count">
                                <div class="product-rate d-inline-block">
                                    <div class="product-rating" :style="'width: ' + product.reviews_avg * 20  + '%'"></div>
                                </div>
                                <span class="font-small ml-5 text-muted">({{ product.reviews_count }})</span>
                            </div>
                            <div class="product-price">
                                <span v-if="!product.sale_price || product.sale_price === product.price">{{ product.price }}</span>
                                <span v-if="product.sale_price && product.sale_price !== product.price">{{ product.sale_price }}</span>
                                <span class="old-price" v-if="product.sale_price && product.sale_price !== product.price">{{ product.price }}</span>
                            </div>
                        </div>
                    </article>
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
        props: {
            url: {
                type: String,
                default: () => null,
                required: true
            },
        },
        mounted() {
          this.getData();
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
                    .catch(res => {
                        this.isLoading = false;
                        console.log(res);
                    });
            },
        },
    }
</script>
