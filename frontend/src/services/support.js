const support = {
    /**
     * Retorna qual a preposição a ser utilizada por estado (Unidade Federativa do Brasil
     *
     * @param estado é o objeto estado retornado pelo model restful state
     * @returns {string}
     */
    ufPreposicao: function(estado) {
        //
        let lastc = estado.name.substr(-1, 1);
        let r =
            lastc === 'a' && ! estado.state_short.match(/^(RO|RR|SC)$/) ? ' da ' :
                estado.state_short.match(/^(PE|SP|RO|RR|SC|SE)$/) ||
                (lastc === 's' && ! estado.state_short.match(/^(AM|TO)$/)) ? ' de ' : ' do ';
        //
        return r;
    },
    typeTextByKey: function(types, key) {
        for (let i in types) {
            let t = types[i];
            if (t.value === key) {
                return t.text;
            }
        }
        return "";
    }
}

export default support;