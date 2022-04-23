<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                <card-component :title="'Relatórios de Comissões'">
                    <template v-slot:alert>
                        <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <h4>Filtros</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input-container-component titulo="Revendedor" id="inputRevendedorComissoes" id-help="revendedorComissoesHelp" texto-ajuda="Selecione o Revendedor"> 
                                    <input type="text" class="form-control" id="inputRevendedorComissoes" aria-describedby="revendedorComissoesHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                        :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                        disabled>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="De:" id="inputDeDiasComissoes" id-help="deDiasComissoesHelp" texto-ajuda=""> 
                                    <input type="date" class="form-control" id="inputDeDiasComissoes" aria-describedby="deDiasComissoesHelp" placeholder="De" 
                                        v-model="parametros_comissoes.de_dias_comissoes" required>
                                </input-container-component>
                            </div>
                             <div class="col-md-3 mb-3">
                                <input-container-component titulo="Até:" id="inputAteDiasComissoes" id-help="ateDiasComissoesHelp" texto-ajuda=""> 
                                    <input type="date" class="form-control" id="inputAteDiasComissoes" aria-describedby="ateDiasComissoesHelp" placeholder="Até" 
                                        v-model="parametros_comissoes.ate_dias_comissoes" required>
                                </input-container-component>
                            </div>
                           <div class="col-md-4 mb-3">
                                <input-container-component titulo="Tipo" id="selectTipoComissao" id-help="tipoComissaoHelp" texto-ajuda=""> 
                                    <select class="form-select" v-model="parametros_comissoes.tipo" required>
                                        <option :value="'todos'">TODOS</option>
                                        <option :value="1">{{descricao_comissao}}</option>
                                        <option :value="2">{{descricao_indicacao_comissao}}</option>
                                    </select>
                                </input-container-component>
                            </div>
                          <div class="col-md-2 mb-3">
                                <input-container-component titulo="Pago" id="selectPagoComissao" id-help="pagoComissaoHelp" texto-ajuda=""> 
                                    <select class="form-select" v-model="parametros_comissoes.pago" required>
                                        <option :value="'todos'">TODOS</option>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-end" @click="generateReportComissions()">Emitir</button>
                    </template>
                </card-component>
                </div>

                <div class="card">
                <card-component :title="'Relatórios de Vendas'">
                    <template v-slot:alert>
                        <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <h4>Filtros</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input-container-component titulo="Revendedor" id="inputRevendedorVendas" id-help="revendedorVendasHelp" texto-ajuda="Selecione o Revendedor"> 
                                    <input type="text" class="form-control" id="inputRevendedorVendas" aria-describedby="revendedorVendasHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                        :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                        disabled>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="De:" id="inputDeDiasVendas" id-help="deDiasVendasHelp" texto-ajuda=""> 
                                    <input type="date" class="form-control" id="inputDeDiasVendas" aria-describedby="deDiasVendasHelp" placeholder="De" 
                                        v-model="parametros_vendas.de_dias_vendas" required>
                                </input-container-component>
                            </div>
                             <div class="col-md-3 mb-3">
                                <input-container-component titulo="Até:" id="inputAteDiasVendas" id-help="ateDiasVendasHelp" texto-ajuda=""> 
                                    <input type="date" class="form-control" id="inputAteDiasVendas" aria-describedby="ateDiasVendasHelp" placeholder="Até" 
                                        v-model="parametros_vendas.ate_dias_vendas" required>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-end" @click="generateReportSells()">Emitir</button>
                    </template>
                </card-component>
                </div>
            </div>
        </div>
        <select-revendedor-component></select-revendedor-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                descricao_comissao: 'COMISSÃO POR VENDA',
                descricao_indicacao_comissao: 'COMISSÃO POR INDICAÇÃO',
                url: 'relatorio',
                urlComissoes: 'comissoes',
                urlVendas: 'vendas',
                parametros_comissoes: {
                    tipo: 'todos',
                    de_dias_comissoes: '',
                    ate_dias_comissoes: '',
                    pago: 'todos'
                },
                parametros_vendas: {
                    de_dias_vendas: '',
                    ate_dias_vendas: '',
                }
            }
        },
        methods: {
            generateReportSells() {
                let revendedor = 'todos';
                if (this.$store.state.select.id != '') {
                    revendedor = this.$store.state.select.id;
                }
                let de = this.parametros_vendas.de_dias_vendas;
                let ate = this.parametros_vendas.ate_dias_vendas;

                if (de == '') {
                    de = '2000-01-01';
                }

                if (ate == '') {
                    ate = this.$getTodayDateUS();
                }

                let url = `${this.$urlBase}/${this.url}/${this.urlVendas}?revendedor=${revendedor}&de_dias_vendas=${de}&ate_dias_vendas=${ate}`;
                this.generateReport(url);
            },
            generateReportComissions() {
                let revendedor = 'todos';
                if (this.$store.state.select.id != '' || this.$store.state.select.id != null) {
                    revendedor = this.$store.state.select.id;
                }
                let de = this.parametros_comissoes.de_dias_comissoes;
                let ate = this.parametros_comissoes.ate_dias_comissoes;
                let tipo = this.parametros_comissoes.tipo;
                let pago = this.parametros_comissoes.pago;

                if (de == '') {
                    de = '2000-01-01';
                }

                if (ate == '') {
                    ate = this.$getTodayDateUS();
                }

                let url = `${this.$urlBase}/${this.url}/${this.urlComissoes}?revendedor=${revendedor}&tipo=${tipo}&de_dias_comissoes=${de}&ate_dias_comissoes=${ate}&pago=${pago}`;
                this.generateReport(url);
            },
            generateReport(url) {
                this.$showLoading();
                window.open(url, "_blank");
                this.$hideLoading();
            }
        },
        mounted() {}
    }
</script>
