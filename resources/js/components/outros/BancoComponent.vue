<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <card-component :title="'Bancos'">
                    <template v-slot:header>
                        <button class="btn btn-primary float-end me-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalBancoInsert" 
                            @click="$setStore({
                                id: '',
                                descricao: '',
                            })"
                        >Inserir Banco</button>
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
                            :view="{active: false, dataToggle: 'modal', dataTarget: '#modalBancoView'}"
                            :update="{active: true, dataToggle: 'modal', dataTarget: '#modalBancoUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalBancoRemove'}"
                            :extraButton="{active: false, name: '', dataToggle: '', dataTarget: ''}"
                            :infoButton="{active: false, name: '', dataToggle: '', dataTarget: ''}"
                            :data="bancos.data">
                        </table-component>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Inicio do modal de inclusão de banco -->
        <modal-component id="modalBancoInsert" titulo="Incluir banco">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="number" class="form-control" id="insertID" aria-describedby="insetIDHelp" placeholder="Número do Banco" 
                        v-model="$store.state.item.id" 
                        required>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Nome do banco" 
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
        <!-- Fim do modal de inclusão de banco -->

        <!-- Inicio do modal de atualização de banco -->
        <modal-component id="modalBancoUpdate" titulo="Atualizar banco">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="number" class="form-control" id="insertID" aria-describedby="insetIDHelp" placeholder="Número do Banco" v-model="$store.state.item.id" required>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Nome do banco" 
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
        <!-- Fim do modal de atualização de banco -->

        <!-- Inicio do modal de remoção de banco -->
        <modal-component id="modalBancoRemove" titulo="Remover banco">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

             <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="number" class="form-control" id="insertID" aria-describedby="insetIDHelp" placeholder="Número do Banco" v-model="$store.state.item.id" required>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Nome do banco" v-model="$store.state.item.descricao" required>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de banco -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                bancos: { data: [] },
                modal: '',
                url: 'parametros/bancos',
                search: {
                    id: '',
                    descricao: '',
                }
            }
        },
        computed: {
            bancosTreatment() {
                let dataTreated = [];
                this.bancos.forEach((data) => {
                    dataTreated.push(data);
                })
                this.bancos.data = dataTreated;
            }
        },
        methods: {
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}`;
                await axios.get(url)
                    .then(response => {
                        this.bancos = response.data.result;
                        this.bancosTreatment;
                    })
                    .catch(errors => {
                        console.log(errors);
                })
            },
            insert() {
                this.modal = '#modalBancoInsert';
                this.$showLoading();
                let formData = new FormData();
                formData.append('id', this.$store.state.item.id);
                formData.append('descricao', this.$store.state.item.descricao);

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Banco incluído com sucesso.';
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
                this.modal = '#modalBancoUpdate';
                this.$showLoading();
                let formData = new FormData();
                formData.append('_method', 'put');
                formData.append('id', this.$store.state.item.id);
                formData.append('descricao', this.$store.state.item.descricao);

                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Banco atualizado com sucesso.';
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
                this.modal = '#modalBancoRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Banco deletado com sucesso.';
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