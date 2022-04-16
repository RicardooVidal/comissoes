<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Início do card de buscas -->
                    <card-component :title="'Busca de vendas'">
                        <template v-slot:body>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <input-container-component titulo="ID" id="inputIdSearch" id-help="idHelp" texto-ajuda=""> 
                                        <input type="number" class="form-control" id="inputIdSearch" aria-describedby="idHelp" placeholder="ID da Venda"  v-model="search.id">
                                    </input-container-component>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Revendedor" id="inputRevendedorSearch" id-help="revendedorHelp" texto-ajuda="Selecione o Revendedor"> 
                                        <input type="text" class="form-control" id="inputRevendedorSearch" aria-describedby="revendedorHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                            :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                            disabled>
                                    </input-container-component>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input-container-component titulo="Data" id="inputDataSearch" id-help="dataSearchHelp" texto-ajuda=""> 
                                        <input type="date" class="form-control" id="inputDataSearch" aria-describedby="dataSearchHelp" placeholder="Data da Venda"  v-model="search.data_venda">
                                    </input-container-component>
                                </div>
                               <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Descrição" id="inputDescricaoSearch" id-help="descricaoHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control" id="inputDescricaoSearch" aria-describedby="descricaoHelp" placeholder="Descrição"  v-model="search.descricao">
                                    </input-container-component>
                                </div>
                               <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Link Anúncio" id="inputLinkAnuncioSearch" id-help="LinkAnuncioHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control" id="inputAnuncioSearch" aria-describedby="LinkAnuncioHelp" placeholder="Link Anúncio"  v-model="search.link_venda" disabled>
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
                <card-component :title="'Vendas'">
                    <template v-slot:header>
                        <a href="/vendas/create" class="btn btn-primary float-end me-2 mt-2">Nova Venda</a>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'ID', type: 'text', align: 'center'},
                                descricao: {},
                                link_venda: {},
                                revendedor: {title: 'Revendedor', type: 'text', align: 'left'},
                                quantidade: {title: 'Quantidade', type: 'text', align: 'center'},
                                preco_unitario: {title: 'Preço Unitário', type: 'text', align: 'right', mask: 'money'},
                                outras_despesas_valor: {title: 'Valor Outras Despesas', type: 'text', align: 'right', mask: 'money'},
                                data_venda: {title: 'Data da Venda', type: 'date', align: 'center'},
                                total_bruto: {title: 'Total Bruto', type: 'text', align: 'right', mask: 'money'},
                                total_liquido: {title: 'Total Liquido', type: 'text', align: 'right', mask: 'money'},
                                revendedor_id: {},
                                taxa_calculado: {},
                                taxa_percentual: {},
                                comissao_calculado: {},
                                comissao_percentual: {},
                                comissao_indicado_calculado: {},
                                comissao_indicado_percentual: {},
                                indicador_id: {},
                                indicador: {},
                                outras_despesas_valor: {},
                                outras_despesas_descricao: {},
                                venda_lucro_id: {},
                                status_indicacao: {}
                            }"
                            :view="{active: true, dataToggle: 'modal', dataTarget: '#modalVendaView'}"
                            :update="{active: false, dataToggle: 'modal', dataTarget: '#modalVendaUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalVendaRemove'}"
                            :extraButton="{active: false, name: '', dataToggle: 'modal', dataTarget: ''}"
                            :infoButton="{active: true, name: 'Comissão', dataToggle: 'modal', dataTarget: '#modalVendaComissoes'}"
                            :data="vendas.data">
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-12">
                                <paginate-component>
                                    <li v-for="l, key in vendas.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                        </div>
                    </template>
                </card-component>

                <view-comissoes-venda-component
                    v-if="$store.state.item != {}"
                    :idVendaLucro="$store.state.item.venda_lucro_id"
                ></view-comissoes-venda-component>

                <select-revendedor-component></select-revendedor-component>

            <!-- Inicio do modal de visualização de venda -->
            <modal-component id="modalVendaView" titulo="Informações da venda" width="modal-lg">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>
                <template v-slot:body>
                    <div class="float-end" style="text-decoration: strong">
                        <b>{{$store.state.item.status_indicacao ? $store.state.item.status_indicacao : 'SEM INDICAÇÃO'}}</b>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="ID" id="viewId" id-help="viewIdHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewId" aria-describedby="viewCpfHelp"
                                    :value="$store.state.item.id" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-8 mb-3">
                            <input-container-component titulo="Descrição" id="viewDescricao" id-help="viewDescricaoHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewDescricao" aria-describedby="viewDescricaoHelp"
                                    :value="$store.state.item.descricao" disabled>
                            </input-container-component>
                        </div>
                    <div class="col-md-2 mb-3">
                            <input-container-component titulo="Data" id="viewData" id-help="viewDataoHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewData" aria-describedby="viewDataoHelp"
                                    :value="$formatDate($store.state.item.data_venda)" disabled>
                            </input-container-component>
                        </div>
                      <div class="col-md-12 mb-3">
                            <input-container-component titulo="Link anúncio" id="viewId" id-help="viewIdHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewId" aria-describedby="viewCpfHelp"
                                    :value="$store.state.item.link_venda"
                                    disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Revendedor" id="viewRevendedor" id-help="viewRevendedorHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewRevendedor" aria-describedby="viewRevendedorHelp"
                                    :value="$store.state.item.revendedor_id + ' - ' + $store.state.item.revendedor" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Indicador" id="viewIndicador" id-help="viewIndicadorHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewIndicador" aria-describedby="viewIndicadorHelp"
                                    :value="$store.state.item.indicador_id + ' - ' + $store.state.item.indicador" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Taxa Percentual" id="viewTaxaPercentual" id-help="viewTaxaHelp"> 
                                <input type="text" class="form-control" id="viewTaxaPercentual" aria-describedby="viewTaxaHelp"
                                    :value="$toPercentage($store.state.item.taxa_percentual) + '%'" disabled>
                            </input-container-component>
                        </div>

                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Taxa Calculado" id="viewTaxaCalculado" id-help="viewTaxaHelp"> 
                                <input type="text" class="form-control money" id="viewTaxaCalculado" aria-describedby="viewTaxaHelp"
                                    v-model="$store.state.item.taxa_calculado" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Comissão Revendedor Percentual" id="viewComissaoRevendedorPercentual" id-help="viewComissaoRevendedorPercentualHelp"> 
                                <input type="text" class="form-control" id="viewComissaoRevendedorPercentual" aria-describedby="viewComissaoRevendedorPercentualHelp"
                                    :value="$toPercentage($store.state.item.comissao_percentual) + '%'" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Comissão Calculado" id="viewComissaoCalculado" id-help="viewComissaoCalculadoTaxaHelp"> 
                                <input type="text" class="form-control money" id="viewComissaoCalculado" aria-describedby="viewComissaoCalculadoTaxaHelpTaxaHelp"
                                    v-model="$store.state.item.comissao_calculado" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-6 mb-3">
                            <input-container-component titulo="Comissão para Indicador Percentual" id="viewComissaoIndicadorPercentual" id-help="viewComissaoIndicadorPercentualHelp"> 
                                <input type="text" class="form-control" id="viewComissaoIndicadorPercentual" aria-describedby="viewComissaoIndicadorPercentualHelp"
                                    :value="$toPercentage($store.state.item.comissao_indicado_percentual) + '%'" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Comissão para Indicador Calculado" id="viewComissaoIndicadorCalculado" id-help="viewComissaoIndicadorCalculadoTaxaHelp"> 
                                <input type="text" class="form-control money" id="viewComissaoIndicadorCalculado" aria-describedby="viewComissaoIndicadorCalculadoTaxaHelp"
                                    :value="$store.state.item.comissao_indicado_calculado" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-6 mb-3">
                            <input-container-component titulo="Valor Outras Despesas" id="viewValorOutrasDespesas" id-help="viewValorOutrasDespesasHelp"> 
                                <input type="text" class="form-control money" id="viewValorOutrasDespesas" aria-describedby="viewValorOutrasDespesas"
                                    :value="$store.state.item.outras_despesas_valor" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-6 mb-3">
                            <input-container-component titulo="Descrição Outras Despesas" id="viewDescricaoOutrasDespesas" id-help="viewDescricaoOutrasDespesasHelp"> 
                                <input type="text" class="form-control money" id="viewDescricaoOutrasDespesas" aria-describedby="viewDescricaoOutrasDespesasHelp"
                                    :value="$store.state.item.outras_despesas_descricao" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-6 mb-3">
                            <input-container-component titulo="Total Bruto" id="viewTotalBruto" id-help="viewTotalBrutoHelp"> 
                                <input type="text" class="form-control money" id="viewTotalBruto" aria-describedby="viewTotalBrutoHelp"
                                    :value="$store.state.item.total_bruto" disabled>
                            </input-container-component>
                        </div>
                       <div class="col-md-6 mb-3">
                            <input-container-component titulo="Total Líquido" id="viewTotalLiquido" id-help="viewTotalLiquidoHelp"> 
                                <input type="text" class="form-control money" id="viewTotalLiquido" aria-describedby="viewTotalLiquidoHelp"
                                    :value="$store.state.item.total_liquido" disabled>
                            </input-container-component>
                        </div>    
                    </div>
                </template>

                <template v-slot:footer>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </template>
            </modal-component>
            <!-- Fim do modal de visualização de venda -->

        <!-- Inicio do modal de remoção de venda -->
        <modal-component id="modalVendaRemove" titulo="Remover Venda" width="modal-lg">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

            <template v-slot:body>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>
                    </div>
                </div>
                <hr>
                <h3>Você confirma a exclusão desta venda?</h3>
                <ul>
                    <li>A exclusão só será possível mediante comissão EM ABERTO!;</li>
                </ul>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de venda -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                vendas: { data: [] },
                modal: '',
                url: 'venda',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    nome: '',
                    data_venda: '',
                    revendedor_id: '',
                    descricao: '',
                    link_venda: ''
                }
            }
        },
        computed: {
            vendaTreatment() {
                let dataTreated = [];
                this.vendas.data.forEach((data) => {
                    data.venda_lucro_id = data.calculos.id;
                    data.indicador_id = '';
                    data.indicador = '';
                    data.status_indicacao = '';
                    if (data.revendedor.indicador) {
                        data.indicador_id = data.revendedor.indicador.id_revendedor;
                        data.indicador = data.revendedor.indicador.nome;
                        data.status_indicacao = this.$verifyValidadeIndicacao(
                            data.revendedor.data_indicacao,
                            data.revendedor.validade_indicacao
                        );
                        data.status_indicacao = data.status_indicacao === 'ATIVO' ? `ATIVO ATÉ ${this.$formatDate(data.revendedor.validade_indicacao)}`
                            : `EXPIRADO EM ${this.$formatDate(data.revendedor.validade_indicacao)}`;
                        data.status_indicacao = `INDICAÇÃO: ${data.status_indicacao}`;
                    }
                    data.revendedor = data.revendedor.nome;
                    data.revendedor_id = data.revendedor_id;
                    data.total_bruto = parseFloat(data.quantidade * data.preco_unitario).toFixed(2);
                    data.total_liquido = data.calculos.lucro_geral_calculado;
                    data.taxa_calculado = data.calculos.taxa_calculado;
                    data.comissao_calculado = data.calculos.comissao_calculado;
                    data.comissao_indicado_calculado = data.calculos.comissao_indicado_calculado;
                    data.outras_despesas_bruto = data.calculos.outras_despesas_bruto;
                    data.taxa_percentual = data.calculos.taxa_percentual;
                    data.comissao_percentual = data.calculos.comissao_percentual;
                    data.comissao_indicado_percentual = data.calculos.comissao_indicado_percentual;
                    dataTreated.push(data);
                })
                
                this.vendas.data = dataTreated;
            }
        },
        methods: {
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                await axios.get(url)
                    .then(response => {
                        this.vendas = response.data.result;
                        this.vendaTreatment;
                    })
                    .catch(errors => {
                        console.log(errors)
                        this.$errorTreatment(errors);
                })
            },
            remove() {
                this.modal = '#modalVendaRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Venda deletada';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
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
                            case 'data':
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
