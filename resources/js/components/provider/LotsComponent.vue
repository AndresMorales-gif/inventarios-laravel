<template>
    <div class="content-form">
        <form @submit.prevent="addLot">
            <div class="element-form">
                <label>Producto:</label>
                <input @click="availableLot" list="product-l" name="product" v-model="lot.product">
                <datalist id="product-l">
                    <option v-for="(item, index) in products" v-bind:value="item.id">{{ item.name }}</option>
                </datalist>
            </div>
            <div class="element-form">
                <label>Numero de lote:</label>
                <input @keyup="availableLot" type="text" name="lotNumber" placeholder="Numero de lote" v-model="lot.lotNumber">             
            </div>
            <div class="message" v-if="this.messageLot">
                <span>{{ this.messageLot }}</span>
            </div>
            <div class="element-form">
                <label>Fecha de expiracion:</label>
                <input type="date" name="expiration" v-model="lot.expiration">             
            </div>
            <div class="element-form">
                <label>Precio:</label>
                <input type="number" name="price" placeholder="Precio" v-model="lot.price">             
            </div>
            <div class="element-form">
                <label>Cantidad:</label>
                <input type="number" name="quantity" placeholder="Cantidad" v-model="lot.quantity">             
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
                available: false,
                message: '',
                messageLot: '',
    			products: [],
                lot: {
                    product: '',
                    lotNumber: '',
                    expiration: '',
                    price: '',
                    quantity: ''
                }
    		}
    	},
        mounted() {
            axios.get('/product')
				.then((res) =>{
		           this.products = res.data;		          		          
		        });
        },
        methods: {
           addLot(){
            if (this.lot.product.trim() === '' || this.lot.lotNumber.trim() === '' || this.lot.expiration.trim() === '' || this.lot.price.trim() === '' || this.lot.quantity.trim() === '' ) {
                this.message = 'Debes completar todos los campos antes de guardar';
                return;
            }
            if (this.available) {
                this.messageLot = 'Lote no disponible';
                return;
            }
            const params = {                    
                product: this.lot.product,
                lotNumber: this.lot.lotNumber,
                expiration: this.lot.expiration,
                price: this.lot.price,
                quantity: this.lot.quantity 
            };
            this.lot.product = '';
            this.lot.lotNumber = '';
            this.lot.expiration = '';
            this.lot.price = '';
            this.lot.quantity = '';
            axios.post('/lot', params)
                .then((res) =>{
                     this.message = res.data.data;                   
                })
                .catch((err) =>{
                   this.message = 'No se ha podido guardar el lote';                               
                });  
           },
           availableLot(){
            const params = {                    
                product: this.lot.product,
                lotNumber: this.lot.lotNumber 
            };              
            axios.post('/lot/available',params)
                .then((res) =>{
                  this.available = res.data.data;
                  if (this.available) {
                    this.messageLot = 'Lote no disponible';
                  }
                  else{
                    this.messageLot = '';
                  }                   
                });
           }  
        }
    }
</script>