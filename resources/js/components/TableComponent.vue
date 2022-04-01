<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titles" :key="key" :style="'text-align:' + t.align">{{t.title}}</th>
                    <th v-if="view.active || update.active || remove.active"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in filteredData" :key="key">  
                    <td v-for="value, field in obj" :key="field" :style="'text-align:' + titles[field].align">
                        <span v-if="titles[field].type == 'text'" >
                            {{ !titles[field].function ? value :  titles[field].function(value) }}
                        </span>
                        <span v-if="titles[field].type == 'percent'">
                            {{value}}%
                        </span>
                        <span v-if="titles[field].type == 'picture'">
                            <img :src="'/storage/' + value">
                        </span>
                    </td>
                    <td v-if="view.active || update.active || remove.active">
                        <button v-if="view.active" class="btn btn-outline-primary btn-sm" :data-bs-toggle="view.dataToggle" :data-bs-target="view.dataTarget" @click="$setStore(obj)">Visualizar</button>
                        <button v-if="update.active" class="btn btn-outline-primary btn-sm" :data-bs-toggle="update.dataToggle" :data-bs-target="update.dataTarget" @click="$setStore(obj)">Atualizar</button>
                        <button v-if="remove.active" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remove.dataToggle" :data-bs-target="remove.dataTarget" @click="$setStore(obj)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['data', 'titles', 'keys', 'view', 'update', 'remove'],
        methods: {
        },
        computed: {
            filteredData() {
                let campos = Object.keys(this.titles);
                let dadosFiltrados = [];
                this.data.map((item, chave) => {
                    // console.log(chave, item);
                    let itemFiltrado = {};
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]; //utilizar a sintaxe de array para atribuir valores a objetos.
                    })

                    dadosFiltrados.push(itemFiltrado);
                });
                // console.log(dadosFiltrados);
                return dadosFiltrados;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
