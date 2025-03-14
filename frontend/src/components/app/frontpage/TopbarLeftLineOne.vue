<template>
    <b-container class="p-0 m-0 small" fluid>

        <b-col md="12">

            <b-icon
                    icon="key-fill"
                    class="text-primary"
                    v-if="hasAToken()"
                    v-b-tooltip.hover
                    :title="toolTipData()"></b-icon>
            <b-icon
                    icon="key"
                    class="text-danger"
                    v-else></b-icon>
            &nbsp;

            <b-icon
                    icon="hdd-network-fill"
                    class="text-primary"
                    v-if="hasNetwork()"
                    v-b-tooltip.hover
                    title="Online"></b-icon>
            <b-icon
                    icon="hdd-network"
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