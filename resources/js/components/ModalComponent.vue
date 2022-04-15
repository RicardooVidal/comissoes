<template>
    <!-- Modal -->
    <div class="modal fade" :id="id" tabindex="-1" aria-labelledby="label" aria-hidden="true">
        <div :class="'modal-dialog ' + width">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="label">{{titulo}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <slot name="alert"></slot>
                <slot name="body"></slot>
            </div>
            <div class="modal-footer">
                <slot name="footer"></slot>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo', 'width']
    }
</script>
