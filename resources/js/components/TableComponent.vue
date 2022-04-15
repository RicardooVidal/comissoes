<template>
    <div>
        <table class="table table-hover" style="font-size:90%">
            <thead>
                <tr>
                    <!-- {{titles}} -->
                    <th v-if="!Object.keys(t).length == 0" scope="col" 
                        v-for="t, key in titles" 
                        :key="key"
                        :style="'text-align:' + t.align">{{t.title}}
                    </th>
                    <th v-if="
                        extraButton && extraButton.active
                        || view && view.active
                        || infoButton && infoButton.active
                        || update && update.active
                        || remove && remove.active">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in filteredData" :key="key" @click="click ? click.function(obj.id, obj.nome) : ''">
                    <td v-for="value, field in obj" :key="field" :style="'text-align:' + titles[field].align" v-if="$emptyObject(titles[field]) == false">
                        <span v-if="titles[field].type == 'text' && !titles[field].mask" >
                            {{ !titles[field].function ? value :  titles[field].function(value) }}
                        </span>
                       <span v-else-if="titles[field].type == 'link'">
                            <a :href="obj[field + '_link']">{{value}}</a>
                        </span>
                       <span v-else-if="titles[field].type == 'function'" class="pointer-function">
                            <span @click="obj[field + '_function'](obj[titles[field].function_parameters])">{{value}}</span>
                        </span>
                        <span v-else-if="titles[field].mask" :class="titles[field].mask">
                            {{value}}
                        </span>
                        <span v-else-if="titles[field].type == 'percent'">
                            {{value}}%
                        </span>
                        <span v-else-if="titles[field].type == 'date'">
                            {{$formatDate(value)}}
                        </span>
                        <span v-else-if="titles[field].type == 'picture'">
                            <img :src="'/storage/' + value">
                        </span>
                    </td>
                    <td v-if="
                        extraButton && extraButton.active
                        || view && view.active
                        || infoButton && infoButton.active
                        || update && update.active
                        || remove && remove.active
                        ">
                        <button v-if="extraButton && extraButton.active" id="extraButton" class="btn btn-outline-secondary btn-sm" :data-bs-toggle="extraButton.dataToggle" :data-bs-target="extraButton.dataTarget" @click="$setStore(obj)">{{extraButton.name}}</button>
                        <button v-if="infoButton && infoButton.active" id="infoButton" class="btn btn-outline-success btn-sm" :data-bs-toggle="infoButton.dataToggle" :data-bs-target="infoButton.dataTarget" @click="$setStore(obj)">{{infoButton.name}}</button>
                        <button v-if="view.active" id="viewButton" class="btn btn-outline-primary btn-sm" :data-bs-toggle="view.dataToggle" :data-bs-target="view.dataTarget" @click="$setStore(obj)">Visualizar</button>
                        <button v-if="update.active" id="updateButton" class="btn btn-outline-primary btn-sm" :data-bs-toggle="update.dataToggle" :data-bs-target="update.dataTarget" @click="$setStore(obj)">Atualizar</button>
                        <button v-if="remove.active" id="removeButton" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remove.dataToggle" :data-bs-target="remove.dataTarget" @click="$setStore(obj)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['data', 'titles', 'keys', 'extraButton', 'click', 'view', 'infoButton', 'update', 'remove'],
        methods: {
        },
        computed: {
            filteredData() {
                let campos = Object.keys(this.titles);
                let dadosFiltrados = [];
                Object.values(this.data).map((item, chave) => {
                    // console.log(chave, item);
                    let itemFiltrado = {};
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]; //utilizar a sintaxe de array para atribuir valores a objetos.
                    })

                    dadosFiltrados.push(itemFiltrado);
                });

                console.log(dadosFiltrados);
                return dadosFiltrados;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
