<template>
    <b-container class="p-0 m-0" fluid>

        <b-col md="6" class="text-center">
            &nbsp;
        </b-col>
        <b-col md="6" class="text-center">
            <b-icon icon="key" scale="1" class="text-success" v-show="hasAToken()"></b-icon>&nbsp;
            <b-icon icon="hdd-network-fill" scale="1" class="text-success" v-if="hasNetwork()"></b-icon>
            <b-icon icon="hdd-network" scale="1" class="text-success" v-else></b-icon>
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
            }
        }
    }
</script>

<style scoped>

</style>