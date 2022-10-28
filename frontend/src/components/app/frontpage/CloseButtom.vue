<template>

    <b-button
            @click="dismissFormData"
            variant="warning"
            :size="size"
    >{{ caption || "Fechar" }}</b-button>

</template>

<script>
    export default {
        name: "CloseButtom",
        props: {
            size: {
                type: String,
                required: true
            },
            caption: {
                type: String,
                required: false
            }
        },
        methods: {
            dismissFormData() {
                this.$root.$emit('bv::hide::modal', 'modal-form-1');
            }
        }
    }
</script>