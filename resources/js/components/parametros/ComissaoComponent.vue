<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Início do card de buscas -->
                <card-component :title="'Busca de parâmetro de comissões'">
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
                                <input-container-component titulo="Comissão Revendedor" id="inputComissaoRevendedorPercentual" id-help="percentualRevendedorHelp" texto-ajuda="Opcional. Informe a percentual da comissão do revendedor"> 
                                    <input type="text" class="form-control percent" id="inputComissaoRevendedorPercentual" aria-describedby="percentualRevendedorHelp" placeholder="Percentual da comissão do revendedor" 
                                        v-model="search.comissao_percentual"
                                        @blur="search.comissao_percentual = $getInputValueWithMask('#inputComissaoRevendedorPercentual', 'percent')" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-5 mb-3">
                                <input-container-component titulo="Comissão Indicador" id="inputComissaoIndicadorPercentual" id-help="percentualIndicadorHelp" texto-ajuda="Opcional. Informe a percentual da comissão do indicador"> 
                                    <input type="text" class="form-control percent" id="inputComissaoIndicadorPercentual" aria-describedby="percentualIndicadorHelp" placeholder="Percentual da comissão do indicador" 
                                        v-model="search.comissao_indicado_percentual"
                                        @blur="search.comissao_indicado_percentual = $getInputValueWithMask('#inputComissaoIndicadorPercentual', 'percent')" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input-container-component titulo="Ativo" id="selectAtivo" id-help="ativoHelp" texto-ajuda="Opcional. Informe a situação"> 
                                    <select class="form-select" v-model="search.ativo" required>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-8 mb-3">
                                <input-container-component titulo="Descrição" id="inputDescricao" id-help="descricaoHelp" texto-ajuda="Opcional. Informe a descrição da comissão"> 
                                    <input type="text" class="form-control" id="inputDescricao" aria-describedby="descricaoHelp" placeholder="Descrição da comissão" v-model="search.descricao">
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
                <card-component :title="'Parâmetro de comissões'">
                    <template v-slot:header>
                        <button class="btn btn-primary float-end me-2 mt-2" data-bs-toggle="modal" data-bs-target="#modalComissaoInsert" 
                            @click="$setStore({
                                descricao: '',
                                comissao_percentual: 0,
                                comissao_indicado_percentual: 0,
                                ativo: 1
                            })"
                        >Inserir Parâmetro de Comissão</button>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'ID', type: 'text', align: 'center'},
                                descricao: {title: 'Descrição', type: 'text', align: 'left'},
                                comissao_percentual: {title: 'Percentual Revendedor', type: 'percent', align: 'center'},
                                comissao_indicado_percentual: {title: 'Percentual Indicador do Revendedor', type: 'percent', align: 'center'},
                                ativo: {title: 'Ativo', type: 'text', align: 'center', function: $verifyBoolean}
                            }"
                            :view="{active: false, dataToggle: 'modal', dataTarget: '#modalComissaoView'}"
                            :update="{active: true, dataToggle: 'modal', dataTarget: '#modalComissaoUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalComissaoRemove'}"
                            :data="comissoes.data">
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-12">
                                <paginate-component>
                                    <li v-for="l, key in comissoes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Inicio do modal de inclusão de parâmetro de comissão -->
        <modal-component id="modalComissaoInsert" titulo="Incluir parâmetro de comissão">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="updateDescricao" aria-describedby="updateDescricaoHelp" placeholder="Descrição da comissão" 
                        v-model="$store.state.item.descricao" 
                        @blur="$store.state.item.descricao = $store.state.item.descricao.toUpperCase()" required>
                </input-container-component>
                <input-container-component titulo="Comissão para o revendedor">
                    <input type="text" class="form-control percent" id="updateComissaoPercentual" aria-describedby="updateComissaoPercentualHelp" placeholder="Percentual da Comissão Revendedor" 
                        v-model="$store.state.item.comissao_percentual"
                        @blur="$store.state.item.comissao_percentual = $getInputValueWithMask('#updateComissaoPercentual', 'percent')" required>
                </input-container-component>
                <input-container-component titulo="Comissão para o indicador do revendedor">
                    <input type="text" class="form-control percent" id="updateComissaoIndicadorPercentual" aria-describedby="updateComissaoIndicadorPercentualHelp" placeholder="Percentual da Comissão do Indicador do Revendedor" 
                        v-model="$store.state.item.comissao_indicado_percentual"
                        @blur="$store.state.item.comissao_indicado_percentual = $getInputValueWithMask('#updateComissaoIndicadorPercentual', 'percent')" required>
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
                <button type="button" class="btn btn-primary" @click="insert()">Incluir</button>
            </template>
        </modal-component>
        <!-- Fim do modal de inclusão de parâmetro de comissão -->

        <!-- Inicio do modal de atualização de parâmetro de comissão -->
        <modal-component id="modalComissaoUpdate" titulo="Atualizar parâmetro de comissão">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="updateDescricao" aria-describedby="updateDescricaoHelp" placeholder="Descrição da comissão" 
                        v-model="$store.state.item.descricao" 
                        @blur="$store.state.item.descricao = $store.state.item.descricao.toUpperCase()" required>
                </input-container-component>
                <input-container-component titulo="Comissão para o revendedor">
                    <input type="text" class="form-control percent" id="updateComissaoPercentual" aria-describedby="updateComissaoPercentualHelp" placeholder="Percentual da Comissão Revendedor" 
                        v-model="$store.state.item.comissao_percentual"
                        @blur="$store.state.item.comissao_percentual = $getInputValueWithMask('#updateComissaoPercentual', 'percent')" required>
                </input-container-component>
                <input-container-component titulo="Comissão para o indicador do revendedor">
                    <input type="text" class="form-control percent" id="updateComissaoIndicadorPercentual" aria-describedby="updateComissaoIndicadorPercentualHelp" placeholder="Percentual da Comissão do Indicador do Revendedor" 
                        v-model="$store.state.item.comissao_indicado_percentual"
                        @blur="$store.state.item.comissao_indicado_percentual = $getInputValueWithMask('#updateComissaoIndicadorPercentual', 'percent')" required>
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
        <!-- Fim do modal de atualização de parâmetro de comissão -->

        <!-- Inicio do modal de remoção de parâmetro de comissão -->
        <modal-component id="modalComissaoRemove" titulo="Remover taxa">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

            <template v-slot:body>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Descrição">
                    <input type="text" class="form-control" id="deleteDescricao" aria-describedby="deleteDescricaoHelp" placeholder="Descrição da comissão" v-model="$store.state.item.descricao" disabled>
                </input-container-component>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de parâmetro de comissão -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                comissoes: { data: [] },
                modal: '',
                url: 'parametros/comissoes_parametros',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    descricao: '',
                    comissao_percentual: '',
                    comissao_indicado_percentual: '',
                    ativo: ''
                }
            }
        },
        computed: {
            comissoesTreatment() {
                this.comissoes.data.forEach((data) => {
                    data.comissao_percentual = parseInt(data.comissao_percentual * 100);
                    data.comissao_indicado_percentual = parseInt(data.comissao_indicado_percentual * 100);
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
                            case 'comissao_percentual':
                                this.search[key] = this.$convertToDecimal($('#inputComissaoRevendedorPercentual').cleanVal());
                                break;
                            case 'comissao_indicado_percentual':
                                this.search[key] = this.$convertToDecimal($('#inputComissaoIndicadorPercentual').cleanVal());
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
                this.loadContent();

                for(let key in this.search) {
                    this.search[key] = '';
                }
            },
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                await axios.get(url)
                    .then(response => {
                        this.comissoes = response.data.result;
                        this.comissoesTreatment;
                    })
                    .catch(errors => {
                        console.log(errors);
                })
            },
            insert() {
                this.modal = '#modalComissaoInsert';
                this.$showLoading();
                let formData = new FormData();
                formData.append('descricao', this.$store.state.item.descricao);
                formData.append('comissao_percentual', this.$convertToDecimal(this.$store.state.item.comissao_percentual));
                formData.append('comissao_indicado_percentual', this.$convertToDecimal(this.$store.state.item.comissao_indicado_percentual));
                formData.append('ativo', this.$verifyBooleanString(this.$store.state.item.ativo));

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Parâmetro de comissão incluído com sucesso';
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
                this.modal = '#modalComissaoUpdate';
                this.$showLoading();
                let formData = new FormData();
                formData.append('_method', 'put');
                formData.append('descricao', this.$store.state.item.descricao);
                formData.append('comissao_percentual', this.$convertToDecimal(this.$store.state.item.comissao_percentual));
                formData.append('comissao_indicado_percentual', this.$convertToDecimal(this.$store.state.item.comissao_indicado_percentual));
                formData.append('ativo', this.$verifyBooleanString(this.$store.state.item.ativo));

                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Parâmetro de comissão atualizada';
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
                this.modal = '#modalComissaoRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Parâmetro de comissão deletada';
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