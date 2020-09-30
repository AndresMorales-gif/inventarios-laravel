<template>
    <div class="container-list-products">
    	<div class="product" v-for="(item, index) in products">
    		<span>{{ item.name }}</span>

    	</div>
    	<div class="not-product" v-if="products.length == 0">
    		<span>No hay productos</span>
    	</div>        
    </div>
</template>

<script>
    export default {
    	data(){
    		return {
    			products: []
    		}
    	},
        mounted() {
            axios.get('/product')
				.then((res) =>{
		           this.products = res.data;		          		          
		        });
        }
    }
</script>
