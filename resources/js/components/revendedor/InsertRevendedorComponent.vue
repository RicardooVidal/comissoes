<template>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Início do card de inclusão -->
                <card-component :title="'Cadastro de revendedores'">
                    <template v-slot:alert>
                        <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="CPF" id="inputCpf" id-help="cpfHelp" texto-ajuda="Obrigatório. Informe o CPF"> 
                                    <input type="text" class="form-control cpf" id="inputCpf" aria-describedby="cpfHelp" placeholder="CPF" 
                                        v-model="$store.state.item.id"
                                        @blur="$store.state.item.id = $getInputValueWithMask('#inputCpf', 'cpf')" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="RG" id="inputRg" id-help="rgHelp" texto-ajuda="Opcional. Informe o RG"> 
                                    <input type="text" class="form-control rg" id="inputRg" aria-describedby="rgHelp" placeholder="RG" 
                                        v-model="$store.state.item.rg"
                                        @blur="$store.state.item.rg = $getInputValueWithMask('#inputRg', 'rg')" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-5 mb-3">
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp" texto-ajuda="Obrigatório. Informe o nome do revendedor"> 
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome do revendedor" 
                                        v-model="$store.state.item.nome" 
                                        @blur="$store.state.item.nome = $store.state.item.nome.toUpperCase()" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="E-mail" id="inputEmail" id-help="emailHelp" texto-ajuda="Opcional. Informe o email do revendedor"> 
                                    <input type="email" class="form-control email" id="inputEmail" aria-describedby="emailHelp" placeholder="E-mail" v-model="$store.state.item.email" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="Banco" id="selectBanco" id-help="bancoHelp" texto-ajuda="Opcional. Informe o banco"> 
                                    <select class="form-select" v-model="$store.state.item.banco" 
                                        @change="verifyBanco()" required>
                                        <option v-for="banco, key in bancos" :key="key"
                                            :value="banco.id">{{banco.descricao}}
                                        </option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-1 mb-3">
                                <input-container-component titulo="Agência" id="inputAgencia" id-help="agenciaHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control agencia" id="inputAgencia" aria-describedby="agenciaHelp" placeholder="Ag" v-model="$store.state.item.agencia" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="Conta" id="inputConta" id-help="contaHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control conta_banco" id="inputConta" aria-describedby="contaHelp" placeholder="Conta" v-model="$store.state.item.conta" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-1 mb-3">
                                <input-container-component titulo="Dígito" id="inputDigitoAgencia" id-help="digitoAgenciaHelp" texto-ajuda=""> 
                                    <input type="text" class="form-control digito" id="inputDigitoAgencia" aria-describedby="digitoAgenciaHelp" placeholder="Digito" v-model="$store.state.item.digito_conta" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Tipo de Conta" id="selectTipoConta" id-help="ativoHelp" texto-ajuda=""> 
                                    <select class="form-select" v-model="$store.state.item.tipo" required>
                                        <option value="NI">NÃO INFORMADO</option>
                                        <option value="CORRENTE">CORRENTE</option>
                                        <option value="POUPANÇA">POUPANÇA</option>
                                    </select>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Pix" id="inputPix" id-help="pixHelp" texto-ajuda="Opcional. Informe o pix"> 
                                    <input type="text" class="form-control" id="inputPix" aria-describedby="pixHelp" placeholder="Pix" v-model="$store.state.item.pix" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input-container-component titulo="Celular" id="inputCelular" id-help="celularHelp" texto-ajuda="Opcional. Informe o celular do revendedor"> 
                                    <input type="email" class="form-control phone_with_ddd" id="inputCelular" aria-describedby="celularHelp" placeholder="Celular" v-model="$store.state.item.celular" required>
                                </input-container-component>
                            </div>
                            <div class="col-md-7 mb-3">
                                <input-container-component titulo="Indicado por" id="inputIndicado" id-help="indicadoHelp" texto-ajuda="Se o revendedor foi indicado por outro. Escolher aqui."> 
                                    <input type="email" class="form-control" id="inputIndicado" aria-describedby="indicadoHelp" data-bs-toggle="modal" data-bs-target="#modalRevendedorSelect" placeholder="Clique para selecionar"
                                        :value="$store.state.select.id ? $store.state.select.id + ' - ' + $store.state.select.nome : ''" 
                                        disabled>
                                </input-container-component>
                            </div>
                            <div class="col-md-2 mb-3">
                                <input-container-component titulo="Ativo" id="selectAtivo" id-help="ativoHelp" texto-ajuda="Obrigatório. Informe a situação"> 
                                    <select class="form-select" v-model="$store.state.item.ativo" required>
                                        <option value="true">SIM</option>
                                        <option value="false">NÃO</option>
                                    </select>
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:footer>
                        <button id="buttonInsert" type="button" class="btn btn-primary float-end" @click="insert()">Inserir</button>
                        <a href="/revendedores" type="button" class="btn btn-secondary float-end me-2">Voltar</a>
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
                bancos: { data: [] },
                modal: '',
                url: 'revendedor',
                urlContas: 'parametros/conta_pagamentos',
                urlBancos: 'parametros/bancos',
            }
        },
        computed: {
            emptyObject() {
                return {
                    id: '',
                    indicador_id: 0,
                    rg: '',
                    nome: '',
                    email: '',
                    banco: '999',
                    agencia: '',
                    conta: '',
                    digito_conta: '',
                    tipo: 'NI',
                    pix: '',
                    celular: '',
                    ativo: 1
                }
            },
        },
        methods: {
            verifyBanco() {
                this.$activateBancoFields();
                if (this.$store.state.item.banco == 999) {
                    this.$deactivateBancoFields();
                }
            },
            loadBancos() {
                let url = `${this.$urlBase}/${this.urlBancos}`;
                axios.get(url)
                    .then((response) => {
                        this.bancos = response.data.result;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            insert() {
                this.$store.state.item.id = $('#inputCpf').cleanVal();
                this.$store.state.item.rg = $('#inputRg').cleanVal();
                this.$showLoading();

                if (this.$store.state.item.banco == 999) {
                    this.$store.state.item.banco = '';
                }

                this.$store.state.item.indicador_id = 0;

                // Verificar se algum revendedor foi selecionado como indicador
                if (this.$store.state.select.id) {
                    this.$store.state.item.indicador_id = this.$store.state.select.id;
                }

                let formData = new FormData();
                formData.append('id', this.$store.state.item.id);
                formData.append('rg', this.$store.state.item.rg);
                formData.append('indicador_id', this.$store.state.item.indicador_id);
                formData.append('nome', this.$store.state.item.nome);
                formData.append('email', this.$store.state.item.email);
                formData.append('celular', this.$store.state.item.celular);
                formData.append('banco_id', this.$store.state.item.banco);
                formData.append('agencia', this.$store.state.item.agencia);
                formData.append('conta', this.$store.state.item.conta);
                formData.append('digito_conta', this.$store.state.item.digito_conta);
                formData.append('tipo', this.$store.state.item.tipo);
                formData.append('pix', this.$store.state.item.pix);
                formData.append('ativo', this.$verifyBooleanString(this.$store.state.item.ativo));

                let url = `${this.$urlBase}/${this.url}`;

                axios.post(url, formData)
                    .then((response) => {
                        if (response.status == 201) {
                            this.$setStore(this.emptyObject);
                            this.$store.state.transaction.status = 'ok';
                            this.$store.state.transaction.message =  'Revendedor incluído com sucesso. Você será redirecionado em 5 (cinco) segundos.';
                            this.$store.state.transaction.data =  '';
                            $('#buttonInsert').attr('disabled', true);
                            setTimeout(() => {window.location.href = "/revendedores"}, 5000);
                        }
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            }
        },
        mounted() {
            this.$emptySelect();
            this.loadBancos();
            this.$setStore(this.emptyObject);
            this.verifyBanco();
        }
    }
</script>
