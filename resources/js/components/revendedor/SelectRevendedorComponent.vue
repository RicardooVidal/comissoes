
<template>
    <div>
        <!-- Inicio do modal de seleção de revendedor -->
            <modal-component id="modalRevendedorSelect" titulo="Selecionar revendedor" width="modal-lg">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>

                <template v-slot:body>
                    <!-- Início do card de buscas -->
                    <card-component :title="'Busca de revendedores'">
                        <template v-slot:body>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="CPF" id="inputCpfSearch" id-help="cpfSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control cpf" id="inputCpfSearch" aria-describedby="cpfSearchHelp" placeholder="CPF"  v-model="search.id">
                                    </input-container-component>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="RG" id="inputRgSearch" id-help="rgSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control rg" id="inputRgSearch" aria-describedby="rgSearchHelp" placeholder="RG"  v-model="search.rg" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input-container-component titulo="Nome" id="inputNomeSearch" id-help="nomeSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control" id="inputNomeSearch" aria-describedby="nomeSearchHelp" placeholder="Nome"  v-model="search.nome" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <input-container-component titulo="E-mail" id="inputEmailSearch" id-help="emailSearchHelp" texto-ajuda=""> 
                                        <input type="email" class="form-control email" id="inputEmailSearch" aria-describedby="emailSearchHelp" placeholder="E-mail"  v-model="search.email" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input-container-component titulo="Celular" id="inputCelularSearch" id-help="celularSearchHelp" texto-ajuda=""> 
                                        <input type="text" class="form-control phone_with_ddd" id="inputCelularSearch" aria-describedby="celularSearchHelp" placeholder="Celular"  v-model="search.celular" required>
                                    </input-container-component>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <input-container-component titulo="Ativo" id="selectAtivoSearch" id-help="ativoSearchHelp" texto-ajuda=""> 
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

                    <table-component
                        :titles="{
                            id: {title: 'CPF', type: 'text', align: 'center', mask: 'cpf'},
                            nome: {title: 'Nome', type: 'text', align: 'left'},
                            email: {title: 'Email', type: 'text', align: 'left'},
                            ativo: {title: 'Ativo', type: 'text', align: 'center', function: $verifyBoolean}
                        }"
                        :click="{function: selectRevendedor}"
                        :data="revendedores.data">
                    </table-component>

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

                <template v-slot:footer>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="$emptySelect()">Limpar</button>
                </template>
            </modal-component>
        <!-- Fim do modal de remoção de taxa -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                revendedores: { data: [] },
                modal: '#modalRevendedorSelect',
                url: 'revendedor',
                urlPaginate: 'page=1',
                urlFilter: '',
                search: {
                    id: '',
                    rg: '',
                    nome: '',
                    email: '',
                    celular: '',
                    ativo: ''
                }
            }
        },
        methods: {
            selectRevendedor(id, nome) {
                this.$store.state.select = {
                    id,
                    nome
                };
                this.$closeModal(this.modal);
            },
            paginate(l) {
                if (l.url) {
                    this.urlPaginate = l.url.split('?')[1];
                    this.loadContent();
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
                console.log(this.urlFilter);
                this.loadContent();

                // for(let key in this.search) {
                //     this.search[key] = '';
                // }
            },
            async loadContent() {
                let url = `${this.$urlBase}/${this.url}?` + this.urlPaginate + this.urlFilter;
                await axios.get(url)
                    .then(response => {
                        this.revendedores = response.data.result;
                        this.revendedorTreatment;
                    })
                    .catch(errors => {
                        console.log(errors);
                })
            },
        },
        mounted() {
            this.$emptySelect();
            this.loadContent();
        }
    }
</script>
