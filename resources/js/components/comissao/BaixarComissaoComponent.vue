
<template>
    <div>
        <!-- Inicio do modal de baixar comissão -->
            <modal-component id="modalComissaoBaixar" titulo="Baixar comissão" width="modal-lg">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>
                <template v-slot:body>
                    <h4>Dados da comissão</h4>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="ID Comissão" id="viewId" id-help="viewIdHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewId" aria-describedby="viewCpfHelp"
                                    :value="$store.state.item.id" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="ID Venda" id="viewVendaId" id-help="viewVendaIdHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewVendaId" aria-describedby="viewCpfHelp"
                                    :value="$store.state.item.venda_id" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-8 mb-3">
                                <input-container-component titulo="Revendedor" id="viewRevendedor" id-help="viewRevendedorHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control" id="viewRevendedor" aria-describedby="viewRevendedorHelp"
                                        :value="$store.state.item.nome" disabled>
                                </input-container-component>
                            </div>
                        <div class="col-md-7 mb-3">
                            <input-container-component titulo="Tipo de Comissão" id="viewDescricao" id-help="viewDescricaoHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewDescricao" aria-describedby="viewDescricaoHelp"
                                    :value="$store.state.item.descricao" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-3 mb-3">
                            <input-container-component titulo="Valor a Pagar" id="viewValorPagar" id-help="viewValorPagarHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewValorPagar" aria-describedby="viewValorPagarHelp"
                                    :value="$store.state.item.valor" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="Data" id="viewData" id-help="viewDataHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewData" aria-describedby="viewDataHelp"
                                    :value="$formatDate($store.state.item.data_gerado)" disabled>
                            </input-container-component>
                        </div>
                        <hr>
                        <h4>Dados bancários para pagamento</h4>
                        <div class="col-md-6 mb-3">
                            <input-container-component titulo="Banco" id="viewBanco" id-help="viewBancoHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewBanco" aria-describedby="viewBancoHelp"
                                    :value="revendedor_dados_bancarios.banco_id + '-' + revendedor_dados_bancarios.banco_nome" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="Agência" id="viewAgencia" id-help="viewAgenciaHelp" texto-ajuda=""> 
                                <input type="text" class="form-control" id="viewAgencia" aria-describedby="viewAgenciaHelp"
                                    :value="revendedor_dados_bancarios.agencia" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="Conta" id="viewConta" id-help="viewContaHelp" viewContaHelp-ajuda=""> 
                                <input type="text" class="form-control" id="viewConta" aria-describedby="viewAgenciaHelp"
                                    :value="revendedor_dados_bancarios.conta" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input-container-component titulo="Dígito Conta" id="viewDigitoConta" id-help="viewDigitoContaHelp" viewContaHelp-ajuda=""> 
                                <input type="text" class="form-control" id="viewDigitoConta" aria-describedby="viewDigitoContaHelp"
                                    :value="revendedor_dados_bancarios.digito_conta" disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-4 mb-3">
                            <input-container-component titulo="Tipo de Conta" id="viewTipoDeConta" id-help="viewTipoDeContaHelp" viewContaHelp-ajuda=""> 
                                <input type="text" class="form-control" id="viewTipoDeConta" aria-describedby="viewTipoDeContaHelp"
                                    :value="revendedor_dados_bancarios.tipo == 'NI' ? 'NÃO INFORMADO' :revendedor_dados_bancarios.tipo " disabled>
                            </input-container-component>
                        </div>
                        <div class="col-md-8 mb-3">
                            <input-container-component titulo="Pix" id="viewPix" id-help="viewPixHelp" viewContaHelp-ajuda=""> 
                                <input type="text" class="form-control" id="viewPix" aria-describedby="viewPixHelp"
                                    :value="revendedor_dados_bancarios.pix" disabled>
                            </input-container-component>
                        </div>
                    </div>
                    <hr>
                    <h4>Forma de pagamento</h4>
                    <div class="col-md-12 mb-3">
                        <input-container-component titulo="" id="selectTaxa" id-help="taxaHelp" texto-ajuda=""> 
                            <select class="form-select" v-model="$store.state.item.forma_pagamento_id" required>
                                <option v-for="parametro, key in forma_pagamentos" :key="key"
                                    :value="parametro.id">{{parametro.descricao}}
                                </option>
                            </select>
                        </input-container-component>
                    </div>
                </template>

                <template v-slot:footer>
                    <p>Para o melhor controle, baixe apenas depois de efetuar o pagamento.</p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button id='buttonBaixar' type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="baixarComissao()">Baixar</button>
                </template>
            </modal-component>
        <!-- Inicio do modal de baixar comissão -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                forma_pagamentos: { data: []},
                revendedor_dados_bancarios: { data: []},
                modal: '#modalComissaoBaixar',
                urlRevendedor: 'revendedor',
                urlFormaPagamento: 'parametros/formas_pagamentos',
                urlBaixar: 'comissao/baixar'
            }
        },
        watch: {
            '$store.state.item.revendedor_id': function(cpf) {
                this.loadDadosBancarios(cpf);
            }
        },
        methods: {
            async loadFormaPagamentos() {
                let url = `${this.$urlBase}/${this.urlFormaPagamento}`;
                await axios.get(url)
                    .then(response => {
                        this.forma_pagamentos = response.data.result;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            },
            async loadDadosBancarios(cpf) {
                let url = `${this.$urlBase}/${this.urlRevendedor}/${cpf}/dados_bancarios`;
                await axios.get(url)
                    .then(response => {
                        this.revendedor_dados_bancarios = response.data.result[0];
                        this.revendedor_dados_bancarios.pix = this.revendedor_dados_bancarios.pix == null ? 'NÃO INFORMADO' : this.revendedor_dados_bancarios.pix;
                        this.$store.state.item.forma_pagamento_id = 1;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            },
            baixarComissao() {
                let url = `${this.$urlBase}/${this.urlBaixar}`;

                // Tratamento temporário (estou muito bêbado pra arrumar isso agora)
                if (this.$store.state.item.forma_pagamento_id == undefined) {
                    this.$store.state.item.forma_pagamento_id = 1;
                }

                let formData = new FormData();
                formData.append('_method', 'post');
                formData.append('id', this.$store.state.item.id);
                formData.append('forma_pagamento_id', this.$store.state.item.forma_pagamento_id);

                axios.post(url, formData)
                    .then(response => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Comissão baixada com sucesso.';
                        this.$store.state.transaction.data = '';
                        this.$store.state.item.forma_pagamento_id = 1;
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            }
        },
        mounted() {
            this.loadFormaPagamentos();
        },
    }
</script>
