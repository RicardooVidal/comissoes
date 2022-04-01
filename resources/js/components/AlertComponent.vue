<template>
    <div :class="styleType" role="alert">
        {{title}}
        <br>
        <ul v-if="details.data">
            <li v-for="e, key in details.data" :key="key">{{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['type', 'title', 'details'],
        computed: {
            styleType() {
                return 'alert alert-' + this.type
            }
        }
    }
</script>
