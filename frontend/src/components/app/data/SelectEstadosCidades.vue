<template>

    <b-row>
        <b-col md="6" class="p-0 m-0 pl-1">
            <b-form-group
                    id="input-group-estado"
                    label="Estado"
                    label-for="select-estado"
                    label-class="bg-light text-center p-0 m-0">
                <b-form-select
                        id="select-estado"
                        v-model="form.estado"
                        :options="allStates"
                        @change="choseState"
                        size="sm">
                    <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
                </b-form-select>
            </b-form-group>
        </b-col>
        <b-col md="6" class="p-0 m-0 px-1">
            <b-form-group
                    id="input-group-cidade"
                    label="Cidade"
                    label-for="select-cidade"
                    label-class="bg-light text-center p-0 m-0">
                <b-form-select
                        id="select-cidade"
                        v-model="form.cidade"
                        :options="allCities"
                        size="sm">
                    <b-form-select-option :value="null" disabled>-- Selecione --</b-form-select-option>
                </b-form-select>
            </b-form-group>
        </b-col>
    </b-row>

</template>

<script>
    export default {
        name: "SelectEstadosCidades",
        data() {
            return {
                form: this.enderecoObj,
                allStates: [],
                allCities: []
            }
        },
        props: {
            enderecoObj: {
                type: Object,
                required: false
            }
        },
        mounted() {
            this.getAllStates();
        },
        methods: {
            choseState() {
                this.getAllCities();
            },
            async getAllStates() {
                let s = await this.$http.get('state');

                this.$set(this, 'allStates', s.data.map(elm => {
                    return { text: elm.name, value: elm.id };
                }))
            },
            async getAllCities() {
                let sid = this.form.estado;
                let s = await this.$http.get('city/loadAllByStateId/' + sid);

                this.$set(this, 'allCities', s.data.map(elm => {
                    return { text: elm.name, value: elm.id };
                }))
            }
        }
    }
</script>