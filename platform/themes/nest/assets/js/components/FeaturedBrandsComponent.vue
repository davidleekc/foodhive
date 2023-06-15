<template>
    <div>
        <div v-if="isLoading">
            <div class="half-circle-spinner">
                <div class="circle circle-1"></div>
                <div class="circle circle-2"></div>
            </div>
        </div>
        <div v-carousel class="carousel-6-columns-cover arrow-center position-relative wow fadeIn animated" v-if="!isLoading" :data-items-xxl="scrollItems" data-items-xl="6" data-items-lg="4" data-items-md="4" data-items-sm="1">
            <div class="slider-arrow slider-arrow-3 carousel-6-columns-arrow" id="carousel-6-columns-3-arrows"></div>
            <div class="carousel-slider-wrapper carousel-6-columns text-center" id="carousel-6-columns-3">
                <div class="brand-logo" v-for="item in data">
                    <a v-if="item.website" :href="item.website"><img class="img-grey-hover" :src="item.logo" :alt="item.name"/></a>
                    <img class="img-grey-hover" v-if="!item.website" :src="item.logo" :alt="item.name"/>
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
            scrollItems: {
                type: Number,
                default: () => 8,
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
