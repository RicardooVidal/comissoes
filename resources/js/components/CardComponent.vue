<template>
    <div class="card">
        <div class="card-header">{{title}}</div>
            <div style="width: 100%">
                <slot name="header"></slot>
            </div>
            <div class="card-body">
                <slot name="alert"></slot>
                <slot name="body"></slot>
            </div>
            <div class="card-footer">
                <slot name="footer"></slot>
            </div>
    </div>
</template>

<script>
    export default {
        props: ['title'],
        mounted() {}
    }
</script>
