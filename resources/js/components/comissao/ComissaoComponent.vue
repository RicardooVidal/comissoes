<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Início do card de buscas -->
                    <card-component :title="'Busca de comissões'">
                        <template v-slot:body>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <input-container-component titulo="ID da Venda" id="inputIdSearch" id-help="idHelp" texto-ajuda=""> 
                                        <input type="number" class="form-control" id="inputIdSearch" aria-describedby="idHelp" placeholder="ID da Venda"  v-model="search.venda_id">
                                    </input-container-component>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Revendedor" id="inputRevendedorSearch" id-help="revendedorHelp" texto-ajuda="Selecione o Revendedor"> 
                                        <input type="text" class="form-control" id="inputRevendedorSearch" aria-describedby="revendedorHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                            :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                            disabled>
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
                <br>
                <card-component :title="'Comissões'">
                    <template v-slot:header>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'ID', type: 'text', align: 'left'},
                                revendedor_id: {},
                                nome: {title: 'Revendedor', type: 'function', align: 'left', function_parameters: 'revendedor_id'},
                                venda_id: {title: 'ID Venda', type: 'function', align: 'center', function_parameters: 'venda_id'},
                                descricao: {title: 'Tipo', type: 'text', align: 'center'},
                                data_gerado: {title: 'Data', type: 'date', align: 'center'},
                                valor: {title: 'Valor', type: 'text', align: 'center', mask: 'money'},
                                pago: {title: 'Pago', type: 'text', align: 'center', function: $verifyBoolean},
                                forma_pagamento_descricao: {title: 'Forma de Pagamento', type: 'text', align: 'center'},
                                data_pagamento: {title: 'Data Pagamento', type: 'date', align: 'center'},
                                venda_id_function: {},
                                nome_function: {},
                            }"
                            :view="{active: false, dataToggle: 'modal', dataTarget: ''}"
                            :update="{active: false, dataToggle: 'modal', dataTarget: ''}"
                            :remove="{active: false, dataToggle: 'modal', dataTarget: ''}"
                            :extraButton="{active: true, name: 'Estornar', dataToggle: 'modal', dataTarget: '#modalEstornoComissao'}"
                            :infoButton="{active: true, name: 'Baixar', dataToggle: 'modal', dataTarget: '#modalComissaoBaixar'}"
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
                <select-revendedor-component></select-revendedor-component>
                <baixar-comissao-component></baixar-comissao-component>
            </div>
        </div>

        <!-- Inicio do modal de estorno de comissão -->
        <modal-component id="modalEstornoComissao" titulo="Estornar Comissão">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

            <template v-slot:body>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>
                    </div>
                </div>
                <hr>
                <h3>Você confirma o estorno desta comissão?</h3>
                <ul>
                    <li>O status PAGO será atualizado para NÃO;</li>
                    <li>Forma de pagamento será deixado em branco;</li>
                    <li>Data de pagamento será deixado em branco.</li>
                </ul>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="estornarComissao()">Estornar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de revendedor -->
    </div>
</template>

<script>
import BaixarComissaoComponent from './BaixarComissaoComponent.vue';
    export default {
  components: { BaixarComissaoComponent },
        watch: {
            '$store.state.transaction.message': function(message) {
                if (message === 'Comissão baixada com sucesso.' || message === 'Comissão estornada com sucesso.') {
                    this.loadContent();
                }
            }
        },
        data() {
            return {
                comissoes: { data: [] },
                forma_pagamentos: { data: []},
                modal: '#modalComissaoBaixar',
                url: 'comissao',
                urlFormaPagamento: 'parametros/formas_pagamentos',
                urlEstornar: 'comissao/estornar',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    venda_id: '',
                    revendedor_id: '',
                    data_gerado: '',
                    data_pagamento: '',
                    descricao: ''
                }
            }
        },
        computed: {
            comissaoTreatment() {
                let dataTreated = [];
                this.comissoes.data.forEach((data) => {
                    data.venda_id = data.calculos.venda_id;
                    data.venda_id_function = this.redirectToVenda;
                    data.forma_pagamento_descricao = '';
                    if (data.forma_pagamento != null) {
                        data.forma_pagamento_descricao = data.forma_pagamento.descricao
                    }
                    data.nome = data.revendedor.nome;
                    data.nome_function = this.redirectToRevendedor;
                    data.valor = data.calculos.comissao_calculado;

                    if (data.descricao === 'COMISSÃO POR INDICAÇÃO') {
                        data.valor = data.calculos.comissao_indicado_calculado;
                    }

                    dataTreated.push(data);
                })
                this.comissoes.data = dataTreated;
            }
        },
        methods: {
            redirectToVenda(id) {
                this.$showLoading();
                this.$closeModal(this.modal);
                window.location.href = `/vendas?search=${id}`;
            },
            redirectToRevendedor(id) {
                this.$showLoading();
                this.$closeModal(this.modal);
                window.location.href = `/revendedores?search=${id}`;
            },
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                console.log(url);
                await axios.get(url)
                    .then(response => {
                        this.comissoes = response.data.result;
                        this.comissaoTreatment;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            },
            searchTerms() {
                let filter = '';

                for(let key in this.search) {
                    if (this.search[key] || key === 'revendedor_id') {
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
                            case 'revendedor_id':
                                this.search[key] = this.$store.state.select.id;
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
            estornarComissao() {
                let url = `${this.$urlBase}/${this.urlEstornar}`;

                let formData = new FormData();
                formData.append('_method', 'post');
                formData.append('id', this.$store.state.item.id);

                axios.post(url, formData)
                    .then(response => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Comissão estornada com sucesso.';
                        this.$store.state.transaction.data = '';
                        this.$store.state.item.id = 0;
                        this.$store.state.item.forma_pagamento_id = 1;
                        $('.modal').modal('hide');
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            }
        },
        mounted() {
            let urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) {
                this.search.id = urlParams.get('search');
                this.searchTerms();
            } else {
                this.loadContent();
            }
        }
    }
</script>