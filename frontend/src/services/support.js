import moment from "moment";

const support = {
    /**
     * Flags que serão utilizados pelo CRUD
     */
    st: {
        SELECTING: 0x0,
        CREATING: 0x1,
        READING: 0x2,
        UPDATING: 0x4,
        DELETING: 0x8
    },
    /**
     * Retorna qual a preposição a ser utilizada por estado (Unidade Federativa do Brasil)
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
    },
    yearsBetween: function(from, to) {
        var firstDate = moment(from);
        var secondDate = moment(to);
        var yearDiff = firstDate.diff(secondDate, "year");

        return yearDiff * -1;
    }
}

export default support;