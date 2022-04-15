<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                <!-- Início do card de buscas -->
                <card-component :title="'Configurações Gerais'">
                    <template v-slot:alert>
                        <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input-container-component titulo="Validade (em dias) de indicações" id="inputValidadeDiasIndicacoes" id-help="validadeDiasHelp" texto-ajuda="Dias em que uma indicação será valida (não altera para indicações já cadastradas)"> 
                                    <input type="number" class="form-control" id="inputValidadeDiasIndicacoes" aria-describedby="validadeDiasHelp" placeholder="Dias" 
                                        v-model="$store.state.item.validade_comissao_indicado" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input-container-component titulo="Recuperar descrição de anúncio" id="selectDescricaoAnuncio" id-help="ativoHelp" texto-ajuda="Se sim, durante a venda, ao inserir o link do anúncio, o sistem vai recuperar a descrição do produto"> 
                                    <select class="form-select" v-model="$store.state.item.recuperar_descricao_compra" required>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-end" @click="update()">Atualizar</button>
                    </template>
                </card-component>
                <!-- fim do card de buscas -->      
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                configuracoes: { data: [] },
                modal: '',
                url: 'configuracoes'
            }
        },
        methods: {
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}`;
                await axios.get(url)
                    .then(response => {
                        this.configuracoes = response.data.result;
                        this.$setStore(this.configuracoes);
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message =  'Configurações não encontrada';
                        this.$store.state.transaction.data =  errors.response.data.errors;
                })
            },
            update() {
                this.$showLoading();
                let formData = new FormData();
                formData.append('_method', 'put');
                formData.append('validade_comissao_indicado', this.$store.state.item.validade_comissao_indicado);
                formData.append('recuperar_descricao_compra', this.$verifyBooleanString(this.$store.state.item.recuperar_descricao_compra));

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Processo finalizado com sucesso.';
                        this.$store.state.transaction.data =  '';
                        //limpar o campo de seleção de arquivos
                        // this.loadContent();
                    })
                    .catch(errors => {
                        this.$store.state.transaction.status = 'error';
                        this.$store.state.transaction.message =  errors.response.data.message;
                        this.$store.state.transaction.data =  errors.response.data.errors;
                    })
            },            
        },
        mounted() {
            this.loadContent();
        }
    }
</script>
