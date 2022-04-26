
<template>
    <div>
        <!-- Inicio do modal de revendedores indicados -->
            <modal-component id="modalRevendedorComissoes" :titulo="'Comissões de ' + $store.state.item.nome" width="modal-xl">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>

                <template v-slot:body>
                    <!-- Início do card de buscas -->
                        <card-component :title="'Busca de comissões'">
                            <template v-slot:body>
                                <div class="row">
                                    <div class="col-md-2 mb-3">
                                        <input-container-component titulo="ID da Venda" id="inputIdSearch" id-help="idHelp" texto-ajuda=""> 
                                            <input type="number" class="form-control" id="inputIdSearch" aria-describedby="idHelp" placeholder="ID da Venda"  v-model="search.venda_id">
                                        </input-container-component>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <input-container-component titulo="Data Gerado" id="inputDataGeradoSearch" id-help="dataGeradoSearchHelp" texto-ajuda=""> 
                                            <input type="date" class="form-control" id="inputDataGeradoSearch" aria-describedby="dataGeradoSearchHelp" placeholder="Data gerado"  v-model="search.data_gerado">
                                        </input-container-component>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <input-container-component titulo="Data Pagamento" id="inputDataPagamentoSearch" id-help="dataPagamentoSearchHelp" texto-ajuda=""> 
                                            <input type="date" class="form-control" id="inputDataPagamentoSearch" aria-describedby="dataPagamentoSearchHelp" placeholder="Data de Pagamento"  v-model="search.data_pagamento">
                                        </input-container-component>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <input-container-component titulo="Tipo" id="selectDescricao" id-help="descricaoHelp" texto-ajuda=""> 
                                            <select class="form-select" v-model="search.descricao">
                                                <option value="COMISSÃO POR VENDA">COMISSÃO POR VENDA</option>
                                                <option value="COMISSÃO POR INDICAÇÃO">COMISSÃO POR INDICAÇÃO</option>
                                            </select>
                                        </input-container-component>
                                    </div>
                                </div>
                            </template>

                            <template v-slot:footer>
                                <button type="button" class="btn btn-primary btn-sm float-end" @click="searchTerms()">Pesquisar</button>
                            </template>
                        </card-component>
                    <!-- fim do card de buscas -->

                    <table-component
                        :titles="{
                            id: {title: 'ID Comissão', type: 'text', align: 'center'},
                            venda_id: {title: 'ID Venda', type: 'text', align: 'center'},
                            descricao: {title: 'Tipo', type: 'text', align: 'center'},
                            data_gerado: {title: 'Data', type: 'date', align: 'center'},
                            valor: {title: 'Valor', type: 'text', align: 'center', mask: 'money'},
                            pago: {title: 'Pago', type: 'text', align: 'center', function: $verifyBoolean},
                            data_pagamento: {title: 'Data Pagamento', type: 'date', align: 'center'},
                        }"
                        :click="{function: redirectToComissao}"
                        :data="comissoes.data">
                    </table-component>
                    <div v-if="loading == true">
                        <div class="text-center mt-3">
                            <h4>Carregando comissões...</h4>
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Aguarde...</span>
                            </div>
                        </div>
                    </div>
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

                <template v-slot:footer>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </template>
            </modal-component>
        <!-- Fim do modal de remoção de taxa -->
    </div>
</template>

<script>
    export default {
        props: ['cpf'],
        watch: {
            cpf: function(cpf) {
                this.emptyObject;
                if (cpf != '' || cpf != null) {
                    this.loadContent(cpf);
                }
            }
        },
        data() {
            return {
                loading: true,
                comissoes: { data: [] },
                modal: '#modalRevendedorComissoes',
                url: 'comissao/getAllComissoesByRevendedor',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    venda_id: '',
                    data_gerado: '',
                    data_pagamento: '',
                    descricao: 'COMISSÃO POR VENDA'
                }
            }
        },
        computed: {
            comissoesTreatment() {
                let dataTreated = [];
                this.comissoes.data.forEach((data) => {
                    data.cpf_nome = data.revendedor_id + ' - ' + data.revendedor.nome;
                    data.valor = this.$store.state.item.comissao_calculado;

                    if (data.descricao === 'COMISSÃO POR INDICAÇÃO') {
                        data.valor = this.$store.state.item.comissao_indicado_calculado;
                    }

                    dataTreated.push(data);
                })
                this.loading = false;
                this.comissoes.data = dataTreated;
            },
            emptyObject() {
                this.comissoes.data.data = {
                    descricao: '',
                    data_gerado: '',
                    valor: '',
                    pago: false,
                    data_pagamento: ''
                }
            },
        },
        methods: {
            redirectToComissao(id, nome) {
                this.$showLoading();
                this.$closeModal(this.modal);
                window.location.href = `/comissoes?search=${id}`;
            },
            paginate(l) {
                if (l.url) {
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadContent(this.cpf);
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
                            case 'venda_id':
                                this.search[key] = this.search[key];
                                break;
                            case 'data_gerado':
                                this.search[key] = this.$formatDateToUS(this.search[key]);
                                break;
                            case 'data_pagamento':
                                this.search[key] = this.$formatDateToUS(this.search[key]);
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

                this.loadContent(this.cpf);

                // for(let key in this.search) {
                //     this.search[key] = '';
                // }
            },
            async loadContent(cpf) {
                let url = `${this.$urlBase}/${this.url}?revendedor_id=${cpf}&` + this.urlPaginate + this.urlFilter; 
                await axios.get(url)
                    .then(response => {
                        console.log(response);
                        this.comissoes = response.data.result;
                        this.comissoesTreatment;
                    })
                    .catch(errors => {
                        this.loading = false;
                        this.comissoes.data = {};
                        this.$errorTreatment(errors);
                })
            },
        },
        mounted() {}
    }
</script>
