<template>
    <div class="container wow animate__animated animate__fadeIn">
        <div class="section-title" v-if="!isLoading">
            <div class="title">
                <h3>{{ title }}</h3>
            </div>
            <div class="slider-arrow slider-arrow-2 flex-right carousel-10-columns-arrow" id="carousel-10-columns-arrows"></div>
        </div>
        <div v-carousel class="carousel-10-columns-cover position-relative" v-if="!isLoading" :data-items-xxl="scrollItems" data-items-xl="6" data-items-lg="4" data-items-md="3" data-items-sm="2">
            <div class="carousel-slider-wrapper carousel-10-columns" id="carousel-10-columns">
                <div class="card-2 wow animate__animated animate__fadeInUp"
                    v-for="(item, key) in data"
                    v-bind:key="key"
                    :data-wow-delay="(key + 1) / 10 + 's'"
                    :style="item.background_color ? 'background-color:' + item.background_color : ''">
                    <figure class="img-hover-scale overflow-hidden">
                        <a :href="item.url"><img :src="item.thumbnail" :alt="item.name" /></a>
                    </figure>
                    <h6><a :href="item.url" :title="item.name" v-html="item.name"></a></h6>
                    <span>{{ item.products_count_text }}</span>
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
            title: {
                type: String,
                default: () => null,
            },
            scrollItems: {
                type: Number,
                default: () => 10,
                required: true
            },
        },
        mounted() {
          this.getCategories();
        },
        methods: {
            getCategories() {
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
