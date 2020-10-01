<template>
    <div class="content-sale">
        <div class="content-invoice-form" v-if="this.newSale">
            <div class="content-invoice" v-if="this.newDetails">
                <div class="table-invoice">
                    <table>
                        <tr>
                            <th>Producto</th>
                            <th>Descripcion</th>
                            <th>Numero de lote</th>
                            <th>Fecha de vencimiento</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Opciones</th>
                        </tr>
                        <tr v-for="(itemSale, indexSale) in sales">
                            <td>{{ itemSale.name }}</td>
                            <td>{{ itemSale.description }}</td>
                            <td>{{ itemSale.lot_number }}</td>
                            <td>{{ itemSale.expiration_date }}</td>
                            <td>{{ itemSale.quantity }}</td>
                            <td>{{ itemSale.price }}</td>
                            <td><button @click="editSaleDetail(itemSale.id)">Editar</button><button @click="delSaleDetail(itemSale.id)">Eliminar</button></td>
                        </tr>
                    </table>
                </div>
                <div class="content-accions">
                    <button @click="viewInventory">Ver inventario</button>
                    <button @click="invoice">Facturar</button>
                    <button @click="cancel">Cancelar</button>    
                </div>
            </div>
            <div v-else="this.newDetails">
                <div class="table-invoice">
                    <table>
                        <tr>
                            <th>Producto</th>
                            <th>Nuemro de lote</th>
                            <th>Cantidad inventario</th>
                            <th>Cantidad pedido</th>
                            <th>Inventario total</th>
                            
                        </tr>
                        <tr v-for="(itemInventory, indexInventory) in inventories">
                            <td>{{ itemInventory.name }}</td>
                            <td>{{ itemInventory.lot_number }}</td>
                            <td>{{ itemInventory.quantityI }}</td>
                            <td>{{ itemInventory.quantityS }}</td>
                            <td v-if="itemInventory.quantityS">{{ itemInventory.quantityI - itemInventory.quantityS }}</td>
                            <td v-else="itemInventory.quantityS">{{ itemInventory.quantityI }}</td>                   
                        </tr>
                    </table>
                </div>
                <div class="content-accions">
                    <button @click="viewTable">Ver mis productos</button>                      
                </div>
            </div>
            <div class="content-form" >
                <form @submit.prevent="addSaleDetail">
                	<div class="element-form">
                		<label>Lote del producto:</label>
                		<input @change="availableLot" list="lot-l" name="lot" v-model="saleDetail.lot">
                        <datalist id="lot-l">
                            <option v-for="(item, index) in lots" v-bind:value="index">Producto: {{ item.name }} Numero de lote: {{item.lot_number}} Precio: {{ item.price }} Fecha de expiracion: {{ item.expiration_date }}</option>
                        </datalist>
                	</div>
                	<div class="message" v-if="this.messageLot">
                		<span>{{ this.messageLot }}</span>
                	</div>
                	<div class="element-form">
                        <label>Cantidad:</label>
                        <input @keyup="availableLot" type="number" name="quantity" placeholder="Cantidad" v-model="saleDetail.quantity">             
                    </div>
                    <div class="message" v-if="this.messageQuantity">
                        <span>{{ this.messageQuantity }}</span>
                    </div>
                	<div class="message" v-if="this.message">
                		<span>{{ this.message }}</span>
                	</div>                    
                	<div class="element-form">
                		<button type="submit">Enviar</button>
                	</div>
                </form>
            </div>
        </div>
        <div class="content-new-sale" v-else="this.newSale">
            <button @click="addNewSale">Nueva compra</button>
        </div>
    </div>
</template>

<script>
    export default {
    	data(){
    		return {
                saleId: '',
                edit: false,
                idDetail: '',
    			message: '',
    			messageLot: '',
                messageQuantity: '',
                newDetails: true,
    			available: false,
                newSale: false,
                saleDetail: {                    
                    lot: '',
                    quantity: ''                    
                },
                inventories: [],
    			lots: [],
                sales: [],
    		}
    	},
        mounted() {
            axios.get('/lot')
                .then((res) =>{
                   this.lots = res.data;                                                     
                });
        },
    	methods: {
    		addSaleDetail(){
                if (this.saleDetail.lot.trim() === '' || this.saleDetail.quantity.trim() === '' ) {
                    this.message = 'Debes completar todos los campos antes de enviar';
                    return;
                }
                if (this.available) {
                    this.messageLot = 'Ya estas usando este lote, editalo';
                    return;
                }
    			var params = {
                    type: 'saleDetail',
                    lot: this.lots[this.saleDetail.lot].id,
                    sale: this.saleId,
                    quantity: this.saleDetail.quantity,
                    price: this.lots[this.saleDetail.lot].price
                };
                this.saleDetail.lot = '';
                this.saleDetail.quantity = '';
                if (this.edit) {
                    params['idSale'] = this.idDetail;
                    axios.post('/saleEdit', params)
                        .then((res) =>{ 
                            this.idDetail = '';
                            this.edit = false;
                            this.message = res.data.data;                 
                            this.salesI();                                
                        });
                }
                else {
                    axios.post('/sale', params)
                        .then((res) =>{                   
                           this.message = res.data.data;
                           this.salesI();
                        });
                }
    		},
    		availableLot(){
    			const params = {                    
                    sale: this.saleId,
                    lot: this.lots[this.saleDetail.lot].id 
                };
                axios.post('/sale/available',params)
                    .then((res) =>{
                      this.available = res.data.data;
                      if (this.available) {
                        this.messageLot = 'Ya estas usando este lote, editalo';
                      }
                      else{
                        this.messageLot = '';
                      }                   
                    });
                if (!(this.saleDetail.lot.trim() === '' || this.saleDetail.quantity.trim() === '' )) {
                    const params = {                    
                        quantity: this.saleDetail.quantity,
                        lot: this.lots[this.saleDetail.lot].id 
                    };
                    axios.post('/sale/availableQuantity',params)
                        .then((res) =>{
                          this.available = res.data.data;
                          if (this.available) {
                            this.messageQuantity= 'Cantidad insuficiente en el inventario, use otro lote';
                          }
                      else{
                        this.messageQuantity = '';
                      }                   
                    });
                }    			        
    		},
            salesI(){
                const params = {
                    sale: this.saleId
                };
                axios.post('/sale/sale', params)
                    .then((res) =>{
                        this.sales = res.data;                                  
                    });
            },
            addNewSale(){
                const params = {
                    type: 'sale'
                };
                axios.post('/sale', params)
                .then((res) =>{
                   this.newSale = true;
                   this.saleId = res.data.data;                                                  
                });                
            },
            editSaleDetail(id){
                this.idDetail = id;
                const params = {
                    idSale: this.idDetail
                }
                this.edit = true;
                axios.post('/lot/sale', params)
                    .then((res) =>{ 
                        this.saleDetail.quantity = res.data.quantity;
                                                       
                    });
            },
            delSaleDetail(id){
                const params = {
                    idSale: id
                }
                axios.post('/saleDelete', params)
                    .then((res) =>{ 
                        this.message = res.data.data;
                        this.salesI();                                
                    });
            },
            viewInventory(){
                const params = {
                    sale: this.saleId
                };
                axios.post('/sale/inventory', params)
                    .then((res) =>{
                        this.inventories = res.data;
                        this.newDetails = false;                                  
                    });
                
            },
            invoice(){
                const params = {
                    sale: this.saleId
                };
                const id = this.saleId;
                axios.post('/sale/invoice', params)
                    .then((res) =>{
                        this.saleId = '';
                        this.sales = [];
                        this.newSale = false;
                        window.location.href = '/sale/pdfInvoice/'+id;                                  
                    });
            },
            cancel(){
                const params = {
                    sale: this.saleId
                };
                axios.post('/sale/cancel', params)
                    .then((res) =>{
                        this.saleId = '';
                        this.sales = [];
                        this.newSale = false;                                  
                    });
            },
            viewTable(){
                this.newDetails = true;
            }
    	}       
    }
</script>
