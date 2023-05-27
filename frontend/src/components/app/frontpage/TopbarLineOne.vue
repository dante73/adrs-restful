<template>
    <b-container class="p-0 m-0" fluid>

        <b-col md="12" class="text-center">

            <b-icon
                    icon="key-fill"
                    size="xl"
                    class="text-primary"
                    v-if="hasAToken()"
                    v-b-tooltip.hover
                    :title="toolTipData()"></b-icon>
            <b-icon size="xl" icon="key" class="text-danger" v-else></b-icon>
            &nbsp;

            <b-icon
                    icon="hdd-network-fill"
                    size="xl"
                    class="text-primary"
                    v-if="hasNetwork()"
                    v-b-tooltip.hover
                    title="Online"></b-icon>
            <b-icon
                    icon="hdd-network"
                    size="xl"
                    class="text-danger"
                    v-else
                    v-b-tooltip.hover
                    title="Offline"></b-icon>
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