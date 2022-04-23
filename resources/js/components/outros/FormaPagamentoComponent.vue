<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <card-component :title="'Formas de Pagamento'">
                    <template v-slot:header>
                        <button class="btn btn-primary float-end me-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalFormaPagamentoInsert" 
                            @click="$setStore({
                                id: '',
                                descricao: '',
                            })"
                        >Inserir Forma de Pagamento</button>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'ID', type: 'text', align: 'center'},
                                descricao: {title: 'Descrição', type: 'text', align: 'left'},
                            }"
                            :view="{active: false, dataToggle: 'modal', dataTarget: '#modalFormaPagamentoView'}"
                            :update="{active: true, dataToggle: 'modal', dataTarget: '#modalFormaPagamentoUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalFormaPagamentoRemove'}"
                            :extraButton="{active: false, name: '', dataToggle: '', dataTarget: ''}"
                            :infoButton="{active: false, name: '', dataToggle: '', dataTarget: ''}"
                            :data="formas_pagamentos.data">
                        </table-component>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Inicio do modal de inclusão de forma de pagamento -->
        <modal-component id="modalFormaPagamentoInsert" titulo="Incluir Forma de Pagamento">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Descrição da forma de pagamento" 
                        v-model="$store.state.item.descricao" 
                        @blur="$store.state.item.descricao = $store.state.item.descricao.toUpperCase()" 
                        required>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="insert()">Incluir</button>
            </template>
        </modal-component>
        <!-- Fim do modal de inclusão de forma de pagamento -->

        <!-- Inicio do modal de atualização de banco -->
        <modal-component id="modalFormaPagamentoUpdate" titulo="Atualizar Forma de Pagamento">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Descrição da forma de pagamento" 
                        v-model="$store.state.item.descricao" 
                        @blur="$store.state.item.descricao = $store.state.item.descricao.toUpperCase()" 
                        required>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="update()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de atualização de forma de pagamento -->

        <!-- Inicio do modal de remoção de forma de pagamento -->
        <modal-component id="modalFormaPagamentoRemove" titulo="Remover Forma de Pagamento">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

             <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="number" class="form-control" id="insertID" aria-describedby="insetIDHelp" placeholder="ID" v-model="$store.state.item.id" required>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="" v-model="$store.state.item.descricao" required>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de forma de pagamento -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                formas_pagamentos: { data: [] },
                modal: '',
                url: 'parametros/formas_pagamentos'
            }
        },
        computed: {
            formasPagamentosTreatment() {
                let dataTreated = [];
                this.formas_pagamentos.forEach((data) => {
                    dataTreated.push(data);
                })
                this.formas_pagamentos.data = dataTreated;
            }
        },
        methods: {
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}`;
                await axios.get(url)
                    .then(response => {
                        this.formas_pagamentos = response.data.result;
                        this.formasPagamentosTreatment;
                    })
                    .catch(errors => {
                        console.log(errors);
                })
            },
            insert() {
                this.modal = '#modalFormaPagamentoInsert';
                this.$showLoading();
                let formData = new FormData();
                formData.append('descricao', this.$store.state.item.descricao);

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Forma de Pagamento incluído com sucesso.';
                        this.$store.state.transaction.data = '';
                        this.urlPaginate = 'page=1';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            update() {
                this.modal = '#modalFormaPagamentoUpdate';
                this.$showLoading();
                let formData = new FormData();
                formData.append('_method', 'put');
                formData.append('descricao', this.$store.state.item.descricao);

                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Forma de Pagamento atualizado com sucesso.';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            remove() {
                this.modal = '#modalFormaPagamentoRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Forma de Pagamento deletado com sucesso.';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            }
        },
        mounted() {
            this.loadContent();
        }
    }
</script>