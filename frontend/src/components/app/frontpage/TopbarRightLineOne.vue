<template>
    <b-container class="p-0 m-0 small text-right" fluid>
        <b-col md="12">
            <b-icon
                    icon="key-fill"
                    class="text-white"
                    v-b-tooltip.hover
                    :title="toolTipData()"></b-icon>
        </b-col>
    </b-container>
</template>

<script>
    import ls from "local-storage";

    export default {
        name: "TopbarLineOne",
        data() {
            return {
                userData: this.$jwt.decode(ls('token'))
            };
        },
        methods: {
            hasAToken() {
                return (this.userData !== null);
            },
            hasNetwork() {
                return navigator.onLine;
            },
            toolTipData() {
                return "Login: " + this.userData.login + "\n" + "Nome: " + this.userData.nome;
            }
        }
    }
</script>