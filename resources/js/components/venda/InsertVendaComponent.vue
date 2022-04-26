<template>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Início do card de inclusão -->
                <card-component :title="'Cadastro de vendas'">
                    <template v-slot:alert>
                        <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Taxa" id="selectTaxa" id-help="taxaHelp" texto-ajuda=""> 
                                    <select class="form-select" v-model="$store.state.item.taxa_parametro_id" required>
                                        <option v-for="parametro, key in taxas_parametros" :key="key"
                                            :value="parametro.id">{{parametro.descricao}} - {{$toPercentage(parametro.taxa_percentual)}}%
                                        </option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Comissão" id="selectComissao" id-help="comissaoHelp" texto-ajuda=""> 
                                    <select class="form-select" v-model="$store.state.item.comissao_parametro_id" required>
                                        <option v-for="parametro, key in comissoes_parametros" :key="key"
                                            :value="parametro.id">{{parametro.descricao}} 
                                            - Revendedor: {{$toPercentage(parametro.comissao_percentual)}}% 
                                            - Indicado: {{$toPercentage(parametro.comissao_indicado_percentual)}}%
                                        </option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Valor Outras Despesas" id="inputValorOutrasDespesas" id-help="outrasDespesasValorHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control money" id="inputValorOutrasDespesas" aria-describedby="outrasDespesasValorHelp" placeholder="Valor de outras despesas"
                                        v-model="$store.state.item.outras_despesas_valor"
                                        @change="$store.state.item.outras_despesas_valor = $getInputValueWithMask('#inputValorOutrasDespesas', 'money')"
                                        @blur="calculateTotal()" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Descrição Outras Despesas" id="inputOutrasDescricaoDespesas" id-help="outrasDespesasDescricaoHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control" id="inputOutrasDescricaoDespesas" aria-describedby="outrasDespesasDescricaoHelp" placeholder="Descrição de outras despesas "
                                        v-model="$store.state.item.outras_despesas_descricao">
                                </input-container-component>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input-container-component titulo="Vendido por" id="inputRevendedor" id-help="revendedorHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control" id="inputRevendedor" aria-describedby="revendedorHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                        :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                        disabled>
                                </input-container-component>
                            </div>
                            <div class="col-md-8 mb-3">
                                <input-container-component titulo="Link anúncio" id="inputAnuncio" id-help="anuncioHelp" texto-ajuda="Válido para plataformas Mercado Livre"> 
                                    <input type="text" class="form-control" id="inputAnuncio" aria-describedby="anuncioHelp" placeholder="Link do anúncio"
                                        v-model="$store.state.item.link_venda"
                                        @change="carregarInformacoesAnuncio()">
                                </input-container-component>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input-container-component titulo="Descrição" id="inputDescricao" id-help="descricaoHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control" id="inputDescricao" aria-describedby="descricaoHelp" placeholder="Descrição da venda"
                                        v-model="$store.state.item.descricao" >
                                </input-container-component>
                            </div>
                           <div class="col-md-2 mb-3">
                                <input-container-component titulo="Quantidade" id="inputQuantidade" id-help="quantidadeHelp" texto-ajuda=""> 
                                    <input type="number" class="form-control" id="inputQuantidade" aria-describedby="quantidadeHelp" placeholder="Quantidade"
                                        v-model="$store.state.item.quantidade"
                                        @blur="calculateTotal()">
                                </input-container-component>
                            </div>
                           <div class="col-md-2 mb-3">
                                <input-container-component titulo="Valor unitário" id="inputValorUnitario" id-help="valorUnitarioHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control money" id="inputValorUnitario" aria-describedby="valorUnitarioHelp" placeholder="Valor Unitário"
                                        v-model="$store.state.item.preco_unitario" 
                                        @change="$store.state.item.preco_unitario = $getInputValueWithMask('#inputValorUnitario', 'money')"
                                        @blur="calculateTotal()" required>
                                </input-container-component>
                            </div>
                           <div class="col-md-2 mb-3">
                                <input-container-component titulo="Valor Total Bruto" id="inputValorTotal" id-help="valorTotalHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control money" id="inputValorTotal" aria-describedby="valorTotalHelp" placeholder="Valor Total"
                                        :value="$store.state.item.preco_total" 
                                        @change="$store.state.item.preco_total = $getInputValueWithMask('#inputValorTotal', 'money')"
                                        disabled>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button id="buttonInsert" type="button" class="btn btn-primary float-end" @click="insert()">Inserir</button>
                        <a href="/vendas" type="button" class="btn btn-secondary float-end me-2">Voltar</a>
                    </template>
                </card-component>
                <!-- fim do card de inclusão -->

                <select-revendedor-component></select-revendedor-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                taxas_parametros: {},
                comissoes_parametros: {},
                modal: '',
                url: 'venda',
                urlRevendedor: 'revendedor',
                urlCrawler: 'crawler',
                urlTaxasParametro: 'parametros/taxas_parametros',
                urlComissoesParametro: 'parametros/comissoes_parametros',
            }
        },
        computed: {
            emptyObject() {
                return {
                    id: '',
                    revendedor_id: 0,
                    taxa_parametro_id: '',
                    comissao_parametro_id: '',
                    outras_despesas_valor: '',
                    outras_despesas_descricao: '',
                    descricao: '',
                    preco_unitario: '',
                    quantidade: '',
                    preco_total: '',
                    link_venda: ''
                }
            },
        },
        methods: {
            carregarInformacoesAnuncio() {
                let url = `${this.$urlBase}/crawler?plataforma=mercado_livre&url=${this.$store.state.item.link_venda}`;

                axios.get(url)
                    .then((response) => {
                        if (response.data.result == "") {
                            return;
                        }
                        this.$store.state.item.descricao = response.data.result.titulo;
                        this.$store.state.item.quantidade = 1;
                        this.$store.state.item.preco_unitario = response.data.result.valor;
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Informações recuperadas com sucesso';
                        this.$store.state.transaction.data =  '';
                        $('#inputQuantidade').focus();
                        this.calculateTotal();
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            calculateTotal() {
                let quantidade = parseInt(this.$store.state.item.quantidade);

                if (quantidade == 0 || isNaN(quantidade)) {
                    this.$store.state.item.preco_total = parseInt(0);
                    return;
                }

                let outras_despesas_valor = this.$convertToDecimal($('#inputValorOutrasDespesas').cleanVal());
                let preco_unitario = this.$convertToDecimal($('#inputValorUnitario').cleanVal());
                let total = (quantidade * preco_unitario) + (+outras_despesas_valor);
                this.$store.state.item.preco_total = parseFloat(total).toFixed(2);
            }, 
            loadTaxasParametros() {
                let url = `${this.$urlBase}/${this.urlTaxasParametro}?ativo=true`;
                axios.get(url)
                    .then((response) => {
                        this.taxas_parametros = response.data.result;

                        //Definir última taxa inserida como padrão
                        this.$store.state.item.taxa_parametro_id = this.taxas_parametros[0].id;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            loadComissoesParametros() {
                let url = `${this.$urlBase}/${this.urlComissoesParametro}?ativo=true`;
                axios.get(url)
                    .then((response) => {
                        console.log(response);
                        this.comissoes_parametros = response.data.result;

                        //Definir último parâmetro de comissão inserido como padrão
                        this.$store.state.item.comissao_parametro_id = this.comissoes_parametros[0].id;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            insert() {
                this.$store.state.item.outras_despesas_valor = $('#inputValorOutrasDespesas').cleanVal();
                this.$store.state.item.preco_unitario = $('#inputValorUnitario').cleanVal();
                this.$showLoading();

                // Verificar se algum revendedor foi selecionado como indicador
                if (this.$store.state.select.id) {
                    this.$store.state.item.revendedor_id = this.$store.state.select.id;
                }

                let formData = new FormData();
                formData.append('revendedor_id', this.$store.state.item.revendedor_id);
                formData.append('taxa_parametro_id', this.$store.state.item.taxa_parametro_id);
                formData.append('comissao_parametro_id', this.$store.state.item.comissao_parametro_id);
                formData.append('outras_despesas_valor', this.$convertToDecimal(this.$store.state.item.outras_despesas_valor));
                formData.append('outras_despesas_descricao', this.$store.state.item.outras_despesas_descricao);
                formData.append('quantidade', this.$store.state.item.quantidade);
                formData.append('descricao', this.$store.state.item.descricao);
                formData.append('preco_unitario', this.$convertToDecimal(this.$store.state.item.preco_unitario));
                formData.append('preco_total', this.$store.state.item.preco_total);
                formData.append('link_venda', this.$store.state.item.link_venda);

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        if (response.status == 201) {
                            this.$setStore(this.emptyObject);
                            this.$store.state.transaction.status = 'ok';
                            this.$store.state.transaction.message =  'Venda incluída com sucesso. Você será redirecionado em 5 (cinco) segundos.';
                            this.$store.state.transaction.data =  '';
                            $('#buttonInsert').attr('disabled', true);
                            setTimeout(() => {window.location.href = "/vendas"}, 5000);
                        }
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            }
        },
        mounted() {
            this.$emptySelect();
            this.loadTaxasParametros();
            this.loadComissoesParametros();
            this.$setStore(this.emptyObject);
        }
    }
</script>
