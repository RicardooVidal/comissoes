
<template>
    <div>
        <!-- Inicio do modal de revendedores indicados -->
            <modal-component id="modalRevendedorIndicados" :titulo="'Revendedores indicados por: ' + this.$store.state.item.nome" width="modal-xl">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>

                <template v-slot:body>
                    <!-- Início do card de buscas -->
                    <card-component :title="'Busca de revendedores'">
                        <template v-slot:body>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <input-container-component titulo="CPF" id="inputCpfSearchIndicado" id-help="cpfSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control cpf" id="inputCpfSearchIndicado" aria-describedby="cpfSearchHelp" placeholder="CPF"  v-model="search.id">
                                    </input-container-component>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <input-container-component titulo="Nome" id="inputNomeSearchIndicado" id-help="nomeSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control" id="inputNomeSearchIndicado" aria-describedby="nomeSearchHelp" placeholder="Nome"  v-model="search.nome" required>
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
                            id: {title: 'CPF', type: 'text', align: 'center', mask: 'cpf'},
                            nome: {title: 'Nome', type: 'text', align: 'left'},
                            data_indicacao: {title: 'Data da indicação', type: 'date', align: 'center'},
                            validade_indicacao: {title: 'Validade da indicação', type: 'date', align: 'center'},
                            situacao_indicacao: {title: 'Situacao da Indicação', type: 'text', align: 'center'},
                        }"
                        :click="{function: selectRevendedor}"
                        :data="indicados.data">
                    </table-component>
                    <div v-if="loading == true">
                        <div class="text-center mt-3">
                            <h4>Carregando indicadores...</h4>
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Aguarde...</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <paginate-component>
                                <li v-for="l, key in indicados.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginate(l)">
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
                indicados: { data: [] },
                modal: '#modalRevendedorIndicados',
                url: 'revendedor',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    nome: ''
                }
            }
        },
        computed: {
            indicadosTreatment() {
                let dataTreated = [];
                this.indicados.data.forEach((data) => {
                    data.situacao_indicacao = this.$verifyValidadeIndicacao(
                        data.data_indicacao,
                        data.validade_indicacao
                    );

                    dataTreated.push(data);
                })
                this.loading = false;
                this.indicados.data = dataTreated;
            },
            emptyObject() {
                this.indicados.data.data = {
                    id: '',
                    nome: '',
                    data_indicacao: '',
                    validade_indicacao: '',
                    situacao_indicacao: ''
                }
            },
        },
        methods: {
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
                                this.search[key] = $('#inputCpfSearchIndicado').cleanVal();
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
            selectRevendedor(id, nome) {
                this.$emptyUrlFilter();

                this.$store.state.select = {
                    updateUrlFilter: '&filter=id' + ':ilike:' + id + '%',
                };

                this.$closeModal(this.modal);
            },
            paginate(l) {
                if (l.url) {
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadContent(this.cpf);
                }
            },
            async loadContent(cpf) {
                let url = `${this.$urlBase}/${this.url}/${cpf}/indicados?` + this.urlPaginate + this.urlFilter;
                await axios.get(url)
                    .then(response => {
                        this.indicados = response.data.result;
                        this.indicadosTreatment;
                    })
                    .catch(errors => {
                        this.loading = false;
                        this.indicados.data = {};
                        this.$errorTreatment(errors);
                })
            },
        },
        mounted() {}
    }
</script>
