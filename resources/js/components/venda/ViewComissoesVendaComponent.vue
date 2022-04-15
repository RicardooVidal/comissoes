
<template>
    <div>
        <!-- Inicio do modal de revendedores indicados -->
            <modal-component id="modalVendaComissoes" :titulo="'Comissões da venda'" width="modal-xl">
                <template v-slot:alert>
                    <alert-component type="danger" :title="$store.state.transaction.message" :details="$store.state.transaction" v-if="$store.state.transaction.status == 'error'"></alert-component>
                </template>

                <template v-slot:body>
                    <table-component
                        :titles="{
                            id: {},
                            cpf_nome: {title: 'Revendedor', type: 'text', align: 'left'},
                            descricao: {title: 'Tipo', type: 'text', align: 'center'},
                            data_gerado: {title: 'Data', type: 'date', align: 'center'},
                            valor: {title: 'Valor', type: 'text', align: 'center', mask: 'money'},
                            pago: {title: 'Pago', type: 'text', align: 'center', function: $verifyBoolean},
                            data_pagamento: {title: 'Data Pagamento', type: 'date', align: 'center'},
                        }"
                        :click="{function: redirectToComissao}"
                        :data="comissoes.data">
                    </table-component>
                    <div v-if="loading == true">
                        <div class="text-center mt-3">
                            <h4>Carregando comissões...</h4>
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Aguarde...</span>
                            </div>
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
        props: ['idVendaLucro'],
        watch: {
            idVendaLucro: function(idVendaLucro) {
                this.emptyObject;
                if (idVendaLucro != '' || idVendaLucro != null) {
                    this.loadContent(idVendaLucro);
                }
            }
        },
        data() {
            return {
                loading: true,
                comissoes: { data: [] },
                modal: '#modalVendaComissoes',
                url: 'comissao/getComissoesByVenda',
            }
        },
        computed: {
            comissoesTreatment() {
                let dataTreated = [];
                this.comissoes.forEach((data) => {
                    data.cpf_nome = data.revendedor_id + ' - ' + data.revendedor.nome;
                    data.valor = this.$store.state.item.comissao_calculado;

                    if (data.descricao === 'COMISSÃO POR INDICAÇÃO') {
                        data.valor = this.$store.state.item.comissao_indicado_calculado;
                    }

                    dataTreated.push(data);
                })
                this.loading = false;
                this.comissoes.data = dataTreated;
            },
            emptyObject() {
                this.comissoes.data.data = {
                    descricao: '',
                    data_gerado: '',
                    valor: '',
                    pago: false,
                    data_pagamento: ''
                }
            },
        },
        methods: {
            redirectToComissao(id, nome) {
                this.$showLoading();
                this.$closeModal(this.modal);
                window.location.href = `/comissoes?search=${id}`;
            },
            async loadContent(idVendaLucro) {
                let url = `${this.$urlBase}/${this.url}?venda_lucro_id=${idVendaLucro}`; 
                await axios.get(url)
                    .then(response => {
                        console.log(response);
                        this.comissoes = response.data.result;
                        this.comissoesTreatment;
                    })
                    .catch(errors => {
                        this.loading = false;
                        this.comissoes.data = {};
                        this.$errorTreatment(errors);
                })
            },
        },
        mounted() {}
    }
</script>
