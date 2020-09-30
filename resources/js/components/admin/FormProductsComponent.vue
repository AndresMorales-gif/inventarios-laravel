<template>
    <div class="content-form">
        <form @submit.prevent="addProduct">
        	<div class="element-form">
        		<label>Nombre del producto:</label>
        		<input @keyup="availableProduct" type="text" name="name" placeholder="Nombre del producto" v-model="product.name">
        	</div>
        	<div class="message" v-if="this.messageName">
        		<span>{{ this.messageName }}</span>
        	</div>
        	<div class="element-form">
        		<label>Descripcion del producto:</label>
        		<textarea name="description" id="description" cols="30" rows="10" v-model="product.description"></textarea>        		
        	</div>
        	<div class="message" v-if="this.message">
        		<span>{{ this.message }}</span>
        	</div>
        	<div class="element-form">
        		<button type="submit">Enviar</button>
        	</div>
        </form>
    </div>
</template>

<script>
    export default {
    	data(){
    		return {
    			message: '',
    			messageName: '',
    			available: false,
    			product: { name:'', description:'' }
    		}
    	},
    	methods: {
    		addProduct(){
    			if(this.product.name.trim() === '' || this.product.description.trim() === ''){
			       this.message = 'Debes completar todos los campos antes de guardar';
			       return;
			    }
			    if (this.available) {
			    	this.message = 'Nombre no disponible';
			       return;
			    }
    			const params = {     				
    				name: this.product.name, 
    				description: this.product.description 
    			};
    			this.product.name = '';
    			this.product.description = '';
    			axios.post('/product', params)
    				.then((res) =>{
			          this.message = res.data.data;			          
			        })
			        .catch((err) =>{
			          this.message = 'No se ha podido guardar el producto';		         		          
			        });

    		},
    		availableProduct(){
    			const params = {     				
    				name: this.product.name 
    			};    			
    			axios.post('/product/available',params)
    				.then((res) =>{
			          this.available = res.data.data;
			          if (this.available) {
			          	this.messageName = 'Nombre no disponible';
			          }
                      else{
                        this.messageName = '';
                      } 			          
			        });
			        
    		}
    	}       
    }
</script>
