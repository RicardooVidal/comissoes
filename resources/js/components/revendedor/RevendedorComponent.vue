<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- Início do card de buscas -->
                    <card-component :title="'Busca de revendedores'">
                        <template v-slot:body>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="CPF" id="inputCpfSearch" id-help="cpfHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control cpf" id="inputCpfSearch" aria-describedby="cpfHelp" placeholder="CPF"  v-model="search.id">
                                    </input-container-component>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="RG" id="inputRgSearch" id-help="rgHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control rg" id="inputRgSearch" aria-describedby="rgHelp" placeholder="RG"  v-model="search.rg" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Nome" id="inputNomeSearch" id-help="nomeHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control" id="inputNomeSearch" aria-describedby="nomeHelp" placeholder="Nome"  v-model="search.nome" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <input-container-component titulo="E-mail" id="inputEmailSearch" id-help="emailHelp" texto-ajuda=""> 
                                        <input type="email" class="form-control email" id="inputEmailSearch" aria-describedby="emailHelp" placeholder="E-mail"  v-model="search.email" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input-container-component titulo="Celular" id="inputCelularSearch" id-help="celularHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control phone_with_ddd" id="inputCelularSearch" aria-describedby="celularHelp" placeholder="Celular"  v-model="search.celular" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="Ativo" id="selectAtivoSearch" id-help="ativoHelp" texto-ajuda=""> 
                                        <select class="form-select"   v-model="search.ativo" required>
                                            <option value="true">SIM</option>
                                            <option value="false">NÃO</option>
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
                <card-component :title="'Cadastro de Revendedores'">
                    <template v-slot:header>
                        <a href="/revendedores/create" class="btn btn-primary float-end me-2 mt-2">Inserir Revendedor</a>
                    </template>
                    <template v-slot:alert>
                        <alert-component type="success" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'ok'"></alert-component>
                    </template>
                    <template v-slot:body>
                        <table-component
                            :titles="{
                                id: {title: 'CPF', type: 'text', align: 'center', mask: 'cpf'},
                                nome: {title: 'Nome', type: 'text', align: 'left'},
                                celular: {title: 'Celular', type: 'text', align: 'left'},
                                indicador: {title: 'Indicado por', type: 'function', align: 'left', function_parameters: 'indicador_id'},
                                ativo: {title: 'Ativo', type: 'text', align: 'center', function: $verifyBoolean},
                                rg: {},
                                email: {},
                                indicador_function: {},
                                indicador_id: {},
                                banco: {},
                                agencia: {},
                                conta: {},
                                digito_conta: {},
                                tipo: {},
                                pix: {}
                            }"
                            :view="{active: false, dataToggle: 'modal', dataTarget: '#modalRevendedorView'}"
                            :update="{active: true, dataToggle: 'modal', dataTarget: '#modalRevendedorUpdate'}"
                            :remove="{active: true, dataToggle: 'modal', dataTarget: '#modalRevendedorRemove'}"
                            :extraButton="{active: true, name: 'Indicados', dataToggle: 'modal', dataTarget: '#modalRevendedorIndicados'}"
                            :infoButton="{active: true, name: 'Comissões', dataToggle: 'modal', dataTarget: '#modalRevendedorComissoes'}"
                            :data="revendedores.data">
                        </table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-12">
                                <paginate-component>
                                    <li v-for="l, key in revendedores.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                        </div>
                    </template>
                </card-component>

                <view-indicados-component
                    v-if="$store.state.item != {}"
                    :cpf="$store.state.item.id"
                ></view-indicados-component>

                <view-comissoes-revendedor-component
                    v-if="$store.state.item != {}"
                    :cpf="$store.state.item.id"
                ></view-comissoes-revendedor-component>
            </div>
        </div>

        <!-- Inicio do modal de atualização de revendedor -->
        <modal-component id="modalRevendedorUpdate" titulo="Atualizar revendedor" width="modal-xl">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>
            <template v-slot:body>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="CPF" id="updateCpf" id-help="updateCpfHelp" texto-ajuda=""> 
                            <input type="text" class="form-control cpf" id="updateCpf" aria-describedby="updateCpfHelp" placeholder="CPF" 
                                v-model="$store.state.item.id"
                                @change="$getInputValueWithMask('#inputCpf', 'cpf')" disabled>
                        </input-container-component>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input-container-component titulo="RG" id="updateRg" id-help="updateRgHelp"> 
                            <input type="text" class="form-control rg" id="updateRg" aria-describedby="updateRgHelp" placeholder="RG" 
                                v-model="$store.state.item.rg"
                                @change="$store.state.item.rg = $getInputValueWithMask('#inputRg', 'rg')" disabled>
                        </input-container-component>
                    </div>
                    <div class="col-md-4 mb-3">
                        <input-container-component titulo="E-mail" id="updateEmail" id-help="updateEmailHelp" texto-ajuda=""> 
                            <input type="email" class="form-control email" id="updateEmail" aria-describedby="updateEmailHelp" placeholder="E-mail" v-model="$store.state.item.email" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="Celular" id="updateCelular" id-help="updateCelularHelp" texto-ajuda=""> 
                            <input type="email" class="form-control phone_with_ddd" id="updateCelular" aria-describedby="updateCelularHelp" placeholder="Celular" v-model="$store.state.item.celular" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input-container-component titulo="Nome" id="updateNome" id-help="updateNomeHelp"> 
                            <input type="text" class="form-control" id="updateNome" aria-describedby="updateNomeHelp" placeholder="Nome do revendedor" 
                                v-model="$store.state.item.nome" 
                                @blur="$store.state.item.nome = $store.state.item.nome.toUpperCase()" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="Banco" id="updateBanco" id-help="updateBancoHelp" texto-ajuda=""> 
                            <select class="form-select" v-model="$store.state.item.banco" 
                                @change="verifyBanco()" required>
                                <option v-for="banco, key in bancos" :key="key"
                                    :value="banco.id">{{banco.descricao}}
                                </option>
                            </select>
                        </input-container-component>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input-container-component titulo="Agência" id="updateAgencia" id-help="updateAgenciaHelp" texto-ajuda=""> 
                            <input type="text" class="form-control agencia" id="updateAgencia" aria-describedby="updateAgenciaHelp" placeholder="Ag" v-model="$store.state.item.agencia" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="Conta" id="updateConta" id-help="updateContaHelp" texto-ajuda=""> 
                            <input type="text" class="form-control conta_banco" id="updateConta" aria-describedby="updateContaHelp" placeholder="Conta" v-model="$store.state.item.conta" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-1 mb-3">
                        <input-container-component titulo="Dígito" id="updateDigitoAgencia" id-help="updateDigitoAgenciaHelp" texto-ajuda=""> 
                            <input type="text" class="form-control digito" id="updateDigitoAgencia" aria-describedby="updateDigitoAgenciaHelp" placeholder="Digito" v-model="$store.state.item.digito_conta" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="Tipo de Conta" id="updateTipoConta" id-help="updateTipoContaHelp" texto-ajuda=""> 
                            <select class="form-select" v-model="$store.state.item.tipo" required>
                                <option value="NI">NÃO INFORMADO</option>
                                <option value="CORRENTE">CORRENTE</option>
                                <option value="POUPANÇA">POUPANÇA</option>
                            </select>
                        </input-container-component>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input-container-component titulo="Pix" id="updatePix" id-help="updatePixHelp" texto-ajuda=""> 
                            <input type="text" class="form-control" id="updatePix" aria-describedby="updatePixHelp" placeholder="Pix" v-model="$store.state.item.pix" required>
                        </input-container-component>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input-container-component titulo="Ativo" id="updateAtivo" id-help="updateAtivoHelp" texto-ajuda=""> 
                            <select class="form-select" v-model="$store.state.item.ativo" required>
                                <option value="true">SIM</option>
                                <option value="false">NÃO</option>
                            </select>
                        </input-container-component>
                    </div>
                </div>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button id="buttonInsert" type="button" class="btn btn-primary float-end" @click="update()">Atualizar</button>
            </template>
        </modal-component>
        <!-- Fim do modal de atualização de revendedor -->

        <!-- Inicio do modal de remoção de revendedor -->
        <modal-component id="modalRevendedorRemove" titulo="Remover revendedor" width="modal-lg">
            <template v-slot:alert>
                <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
            </template>

            <template v-slot:body>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>
                    </div>
                    <div class="col-md-6 mb-3">
                        <input-container-component titulo="Nome">
                            <input type="text" class="form-control" id="deleteNome" aria-describedby="deleteNomeHelp" placeholder="" v-model="$store.state.item.nome" disabled>
                        </input-container-component>
                    </div>
                </div>
                <hr>
                <h3>Você confirma a exclusão deste revendedor?</h3>
                <ul>
                    <li>Todas as vendas serão excluídas;</li>
                    <li>Todos os registros de comissões serão excluídas;</li>
                    <li>Se este revendedor tiver outros como indicados, não será possível deletar.</li>
                </ul>
            </template>

            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remove()">Remover</button>
            </template>
        </modal-component>
        <!-- Fim do modal de remoção de revendedor -->
    </div>
</template>

<script>
    export default {
        watch: {
            '$store.state.select.updateUrlFilter': function() {
                this.urlPaginate = 'page=1';
                this.urlFilter = this.$store.state.select.updateUrlFilter;

                this.loadContent();
            }
        },
        data() {
            return {
                bancos: { data: [] },
                revendedores: { data: [] },
                modal: '',
                url: 'revendedor',
                urlBancos: 'parametros/bancos',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    nome: '',
                    email: '',
                    celular: '',
                    ativo: ''
                }
            }
        },
        computed: {
            revendedorTreatment() {
                let dataTreated = [];
                this.revendedores.data.forEach((data) => {
                    if (data.indicador) {
                        data.indicador_id = data.indicador.revendedor_id,
                        data.indicador_function = this.getRevendedorLink;
                        data.indicador = data.indicador.nome;
                    }
                    data.banco = data.conta_pagamento.banco_id;
                    data.agencia = data.conta_pagamento.agencia == null ? '' : data.conta_pagamento.conta;
                    data.conta = data.conta_pagamento.conta == null ? '' : data.conta_pagamento.conta;
                    data.digito_conta = data.conta_pagamento.digito_conta == null ? '' : data.conta_pagamento.digito_conta;
                    data.tipo = data.conta_pagamento.tipo;
                    data.pix = data.conta_pagamento.pix;
                    dataTreated.push(data);
                })
                this.revendedores.data = dataTreated;
            },
            indicadosTreatment() {
                let dataTreated = [];
                this.$store.state.item.indicados.forEach((data) => {
                    data.situacao_indicacao = $verifyValidadeIndicacao(
                        data.indicacao,
                        validade_indicacao
                    );

                    dataTreated.push(data);
                })
                this.$store.state.item.indicados = dataTreated;
            }
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
            paginate(l) {
                if (l.url) {
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadContent();
                }
            },
            update() {
                this.modal = '#modalRevendedorUpdate';
                this.$showLoading();

                if (this.$store.state.item.banco == 999) {
                    this.$store.state.item.banco = '';
                }

                let formData = new FormData();
                formData.append('_method', 'put');
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

                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Revendedor atualizado';
                        this.$store.state.transaction.data = '';
                        //limpar o campo de seleção de arquivos
                        this.loadContent();
                        this.$closeModal(this.modal); 
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                    })
            },
            remove() {
                this.modal = '#modalRevendedorRemove';
                let formData = new FormData();
                formData.append('_method', 'delete');
                let url = `${this.$urlBase}/${this.url}/${this.$store.state.item.id}`;

                axios.post(url, formData)
                    .then((response) => {
                        this.$store.state.transaction.status = 'ok';
                        this.$store.state.transaction.message = 'Revendedor deletado';
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
                    if (this.search[key]) {
                        // Primeira pagina padrão
                        if (filter != '') {
                            this.urlPaginate = 'page=1';
                            filter += ";";
                        }
                        switch (key) {
                            case 'id':
                                this.search[key] = $('#inputCpfSearch').cleanVal();
                                break;
                            case 'rg':
                                this.search[key] = $('#inputRgSearch').cleanVal();
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
            getRevendedorLink(cpf) {
                this.urlFilter = '&filter=' + 'id' + ':ilike:' + this.$getFilter('cpf', cpf);
                this.loadContent();
            },
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                await axios.get(url)
                    .then(response => {
                        this.revendedores = response.data.result;
                        this.revendedorTreatment;
                    })
                    .catch(errors => {
                        this.$errorTreatment(errors);
                })
            },
        },
        mounted() {
            this.$setStore({});
            this.loadBancos();
            this.verifyBanco();
            this.loadContent();
        }
    }
</script>