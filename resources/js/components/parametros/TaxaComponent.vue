<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <!-- Início do card de buscas -->
                <card-component :title="'Busca de taxas'">
                    <template v-slot:header>
                    </template>
                    <template v-slot:body>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID da taxa"> 
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="search.id">
                                </input-container-component>
                            </div>
                            <div class="col-md-5 mb-3">
                                <input-container-component titulo="Percentual" id="inputPercentual" id-help="percentualHelp" texto-ajuda="Opcional. Informe a percentual da taxa"> 
                                    <input type="text" class="form-control percent" id="inputPercentual" aria-describedby="percentualHelp" placeholder="Percentual da taxa" 
                                        v-model="search.taxa_percentual"
                                        @blur="search.taxa_percentual = $getInputValueWithMask('#inputPercentual', 'percent')" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-5 mb-3">
                                <input-container-component titulo="Ativo" id="selectAtivo" id-help="ativoHelp" texto-ajuda="Opcional. Informe a situação"> 
                                    {{search.ativo}}
                                    <select class="form-select" v-model="search.ativo" required>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input-container-component titulo="Descrição" id="inputDescricao" id-help="descricaoHelp" texto-ajuda="Opcional. Informe a descrição da taxa"> 
                                    <input type="text" class="form-control" id="inputDescricao" aria-describedby="descricaoHelp" placeholder="Descrição da taxa" v-model="search.descricao">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-end" @click="searchTerms()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de buscas -->
                <br>
                <card-component :title="'Parâmetro de taxas'">
                    <template v-slot:header>
                        <button class="btn btn-primary float-end me-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalTaxaInsert" @click="$setStore({descricao: '', taxa_percentual: 0, ativo: 1})">Inserir Taxa</button>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'ID', type: 'text', align: 'center'},
                                descricao: {title: 'Descrição', type: 'text', align: 'left'},
                                taxa_percentual: {title: 'Percentual', type: 'percent', align: 'center'},
                                ativo: {title: 'Ativo', type: 'text', align: 'center', function: $verifyBoolean}
                            }"
                            :view="{active: false, dataToggle: 'modal', dataTarget: '#modalTaxaView'}"
                            :update="{active: true, dataToggle: 'modal', dataTarget: '#modalTaxaUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalTaxaRemove'}"
                            :data="taxas.data">
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-12">
                                <paginate-component>
                                    <li v-for="l, key in taxas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Inicio do modal de inclusão de taxa -->
        <modal-component id="modalTaxaInsert" titulo="Incluir taxa">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="insertDescricao" aria-describedby="insertDescricaoHelp" placeholder="Descrição da taxa" v-model="$store.state.item.descricao" required>
                </input-container-component>
                <input-container-component titulo="Taxa Percentual">
                    <input type="text" class="form-control percent" id="insertTaxaPercentual" aria-describedby="insertTaxaPercentualHelp" placeholder="Percentual" 
                        v-model="$store.state.item.taxa_percentual" 
                        @blur="$store.state.item.taxa_percentual = $getInputValueWithMask('#insertTaxaPercentual', 'percent')" required>
                </input-container-component>
                <input-container-component titulo="Ativo">
                    <select class="form-select" v-model="$store.state.item.ativo" required>
                        <option value="true">SIM</option>
                        <option value="false">NÃO</option>
                    </select>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="insert()">Incluir</button>
            </template>
        </modal-component>
        <!-- Fim do modal de inclusão de taxa -->
    
        <!-- Inicio do modal de atualização de taxa -->
        <modal-component id="modalTaxaUpdate" titulo="Atualizar taxa">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="updateDescricao" aria-describedby="updateDescricaoHelp" placeholder="Descrição da taxa" v-model="$store.state.item.descricao" required>
                </input-container-component>
                <input-container-component titulo="Taxa Percentual">
                    <input type="text" class="form-control percent" id="updateTaxaPercentual" aria-describedby="updateTaxaPercentualHelp" placeholder="Percentual" 
                        v-model="$store.state.item.taxa_percentual"
                        @blur="$store.state.item.taxa_percentual = $getInputValueWithMask('#updateTaxaPercentual', 'percent')" required>
                </input-container-component>
                <input-container-component titulo="Ativo">
                    <select class="form-select" v-model="$store.state.item.ativo" required>
                        <option value=true>SIM</option>
                        <option value=false>NÃO</option>
                    </select>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="update()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de atualização de taxa -->

        <!-- Inicio do modal de remoção de taxa -->
        <modal-component id="modalTaxaRemove" titulo="Remover taxa">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="deleteDescricao" aria-describedby="deleteDescricaoHelp" placeholder="Descrição da taxa" v-model="$store.state.item.descricao" disabled>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de taxa -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                taxas: { data: [] },
                modal: '',
                url: 'parametros/taxas_parametros',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    descricao: '',
                    taxa_percentual: '',
                    ativo: ''
                }
            }
        },
        computed: {
            taxasTreatment() {
                this.taxas.data.forEach((data) => {
                    data.taxa_percentual = parseInt(data.taxa_percentual * 100);
                })
            }
        },
        methods: {
            paginate(l) {
                if (l.url) {
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadContent();
                }
            },
            searchTerms() {
                let filter = '';

                for(let key in this.search) {
                    if (this.search[key]) {
                        // Primeira pagina padrão
                        if (filter != '') {
                            this.urlPaginate = 'page=1';
                            filter += ";";
                        }
                        switch (key) {
                            case 'taxa_percentual':
                                this.search[key] = this.$convertToDecimal($('#inputPercentual').cleanVal());
                                break;
                        }
                        filter += key + ':ilike:' + this.$getFilter(key, this.search[key]);
                        // filter += key + ':like:' + `%${this.search[key]}%`;
                    }
                }

                this.urlFilter = '';

                if (filter != '') {
                    this.urlFilter = '&filter=' + filter 
                }
                console.log(this.urlFilter);
                this.loadContent();

                for(let key in this.search) {
                    this.search[key] = '';
                }
            },
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                console.log(url);
                await axios.get(url)
                    .then(response => {
                        this.taxas = response.data.result;
                        this.taxasTreatment;
                    })
                    .catch(errors => {
                        console.log(errors);
                })
            },
            insert() {
                this.modal = '#modalTaxaInsert';
                this.$showLoading();
                let formData = new FormData();
                formData.append('descricao', this.$store.state.item.descricao);
                formData.append('taxa_percentual', this.$convertToDecimal(this.$store.state.item.taxa_percentual));
                formData.append('ativo', this.$verifyBooleanString(this.$store.state.item.ativo));

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Taxa incluída com sucesso';
                        this.$store.state.transaction.data = '';
                        this.urlPaginate = 'page=1';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message =  errors.response.data.message;
                        this.$store.state.transaction.data =  errors.response.data.errors;
                    })
            },
            update() {
                this.modal = '#modalTaxaUpdate';
                this.$showLoading();
                let formData = new FormData();
                formData.append('_method', 'put');
                formData.append('taxa_percentual', this.$convertToDecimal(this.$store.state.item.taxa_percentual));
                formData.append('descricao', this.$store.state.item.descricao);
                formData.append('ativo', this.$verifyBooleanString(this.$store.state.item.ativo));

                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Taxa atualizada';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message =  errors.response.data.message;
                        this.$store.state.transaction.data =  errors.response.data.errors;
                    })
            },
            remove() {
                this.modal = '#modalTaxaRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Taxa deletada';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message =  errors.response.data.message;
                        this.$store.state.transaction.data =  errors.response.data.errors;
                    })
            }
        },
        mounted() {
            this.loadContent();
        }
    }
</script>