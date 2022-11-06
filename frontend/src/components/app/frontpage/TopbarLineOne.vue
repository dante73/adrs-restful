<template>
    <b-container class="p-0 m-0" fluid>

        <b-col md="12" class="text-center">

            <b-icon
                    icon="key-fill"
                    scale="1"
                    class="text-warning"
                    v-if="hasAToken()"
                    v-b-tooltip.hover
                    :title="toolTipData()"></b-icon>
            <b-icon icon="key" scale="1" class="text-danger" v-else></b-icon>
            &nbsp;

            <b-icon icon="hdd-network-fill" scale="1" class="text-warning" v-if="hasNetwork()"></b-icon>
            <b-icon icon="hdd-network" scale="1" class="text-danger" v-else></b-icon>
            &nbsp;

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

<style scoped>

</style>